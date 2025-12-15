<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function index()
    // {
    //     $products = Product::with(['brand', 'category'])
    //         ->when(request('query'), function ($q) {
    //             $search = request('query');
    //             $q->where(function ($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%")
    //                     ->orWhere('slug', 'like', "%{$search}%");
    //             });
    //         })
    //         ->when(request('category'), function ($q) {
    //             $category = request('category');
    //             $q->whereHas('category', function ($q) use ($category) {
    //                 $q->where('name', 'like', "%{$category}%")
    //                     ->orWhere('slug', 'like', "%{$category}%");
    //             });
    //         })
    //         ->when(request('brand'), function ($q) {
    //             $brand = request('brand');
    //             $q->whereHas('brand', function ($q) use ($brand) {
    //                 $q->where('name', 'like', "%{$brand}%")
    //                     ->orWhere('slug', 'like', "%{$brand}%");
    //             });
    //         })
    //         ->when(request('sort'), function ($q) {
    //             $sort = request('sort');
    //             $q->orderBy('created_at', $sort);
    //         })
    //         ->when(request('status'), function ($q) {
    //             $status = request('status');
    //             $q->where($status, 1);
    //         })

    //         ->paginate(request()->get('per_page') ?? 10);

    //     $categories = Category::pluck('name', 'slug');

    //     return view('home', [
    //         'products' => $products,
    //         'categories' => $categories,
    //     ]);
    // }

    public function index()
    {
        $query = request()->get('search', '');
        $category = request()->get('category', '');
        $brand = request()->get('brand', '');
        $sort = request()->get('sort', 'latest');
        $pageSize = request()->get('per_page', 8);

        // Hide all products 
        $hideAllProducts = false;
        if($query == '' && $category == '' && $brand == '') {
            $hideAllProducts = true;
        }

        // Base query with relationships
        $baseQuery = Product::select('id', 'name', 'slug', 'price', 'feature', 'description', 'stock', 'best_rated', 'on_sale', 'category_id', 'brand_id', 'user_id')
            ->with(['category', 'user', 'brand', 'tieredPrices']);

        // Apply filters
        $baseQuery->when($query, function ($q) use ($query) {
            $q->where(function ($sub) use ($query) {
                $sub->where('name', 'like', "%$query%")
                    ->orWhere('description', 'like', "%$query%")
                    ->orWhere('code', 'like', "%$query%");
            });
        });

        //$baseQuery->when($category, fn ($q) => $q->where('category_id', $category));
        $baseQuery->when($category, function ($q) use ($category) {
            $q->whereHas('category', function ($cat) use ($category) {
                $cat->where('slug', $category);
            });
        });

        //$baseQuery->when($brand, fn ($q) => $q->where('brand_id', $brand));
        $baseQuery->when($brand, function ($q) use ($brand) {
            $q->whereHas('brand', function ($cat) use ($brand) {
                $cat->where('slug', $brand);
            });
        });

        // Sorting
        $baseQuery->orderBy('created_at', $sort === 'oldest' ? 'asc' : 'desc');

        // dd($baseQuery->toSql());
        // Paginated products
        if ($hideAllProducts) {
            $products = [];
        } else {
            $products = (clone $baseQuery)
                ->paginate($pageSize)
                ->appends(request()->all());
        }

        // $featuredProducts = (clone $baseQuery)
        //     ->where('feature', true)
        //     ->limit(8)
        //     ->get();
        $featuredProducts = [];

        $bestSellers = (clone $baseQuery)
            ->where('best_rated', true)
            ->limit(8)
            ->get();

        $onSaleProducts = (clone $baseQuery)
            ->where('on_sale', true)
            ->limit(8)
            ->get();

        // Filters
        $allCategories = Category::orderBy('name')->get();
        $allBrands = Brand::orderBy('name')->get();

        return view('backend.index', compact(
            'products',
            'allCategories',
            'allBrands',
            'featuredProducts',
            'bestSellers',
            'onSaleProducts',
            'hideAllProducts'
        ));
    }

    //not in use
    public function products()
    {
        $status = request()->get('status', 'all');
        $query = request()->get('search', '');
        $category = request()->get('category', '');
        $brand = request()->get('brand', '');
        $sort = request()->get('sort', 'latest');
        $pageSize = request()->get('per_page', 8);

        $baseQuery = Product::with(['category', 'user', 'brand', 'tieredPrices']);

        $baseQuery->when($query, function ($q) use ($query) {
            $q->where(function ($sub) use ($query) {
                $sub->where('name', 'like', "%$query%")
                    ->orWhere('description', 'like', "%$query%")
                    ->orWhere('code', 'like', "%$query%");
            });
        });

        $baseQuery->when($category, fn ($q) => $q->where('category_id', $category));
        $baseQuery->when($brand, fn ($q) => $q->where('brand_id', $brand));

        $baseQuery->orderBy('created_at', $sort === 'oldest' ? 'asc' : 'desc');

        $products = (clone $baseQuery)
            ->paginate($pageSize)
            ->appends(request()->all());
        $allCategories = Category::orderBy('name')->get();
        $allBrands = Brand::orderBy('name')->get();
        $products = (clone $baseQuery)
            ->paginate($pageSize)
            ->appends(request()->all());

        $title = $this->getTitle(request()->status);

        return view('backend.userView.products', compact('products', 'allCategories', 'allBrands', 'title'));
    }

    public function getTitle($status)
    {
        switch ($status) {
            case 'feature':
                return 'Featured Products';
            case 'best_rated':
                return 'Best Rated Products';
            case 'on_sale':
                return 'On Sale Products';
        }

        return 'All Products';
    }

    /* Tiny MCE image Upload */
    public function uploadImage(Request $request)
    {
        $fileName = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs('uploads', $fileName, 'public');

        return response()->json(['location' => "/storage/$path"]);
    }

    public function checkout()
    {
        return view('backend.checkout.index');
    }

    public function productShow($slug)
    {
        $product = Product::with([
            'category', 'brand',
            'relatedProducts' => function ($query) {
                $query->limit(4);
            },
        ])->where('slug', $slug)->firstOrFail();

        // Load media collections
        $product->loadMedia('images');

        // $cacheKey = 'product_viewed_'.$product->id.'_'.session()->getId();

        // if (! Cache::has($cacheKey)) {
        //     $product->increment('view_count'); // atomic increment
        //     Cache::put($cacheKey, true, now()->addHour());
        // }

        return view('backend.product.show', compact('product'));
    }

    public function brands()
    {
        $query = request()->get('search', '');
        $pageSize = request()->get('per_page', 4);

        $baseQuery = Brand::query();

        $baseQuery->when($query, function ($q) use ($query) {
            $q->where('name', 'like', "%$query%");
        });

        $brands = (clone $baseQuery)
            ->paginate($pageSize)
            ->appends(request()->all());

        return view('backend.brand.index', compact('brands', 'query'));
    }
}
