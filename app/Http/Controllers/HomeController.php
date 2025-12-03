<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

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
    public function index()
    {
        $products = Product::with(['brand', 'category'])
            ->when(request('query'), function ($q) {
                $search = request('query');
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when(request('category'), function ($q) {
                $category = request('category');
                $q->whereHas('category', function ($q) use ($category) {
                    $q->where('name', 'like', "%{$category}%")
                        ->orWhere('slug', 'like', "%{$category}%");
                });
            })
            ->when(request('brand'), function ($q) {
                $brand = request('brand');
                $q->whereHas('brand', function ($q) use ($brand) {
                    $q->where('name', 'like', "%{$brand}%")
                        ->orWhere('slug', 'like', "%{$brand}%");
                });
            })
            ->when(request('sort'), function ($q) {
                $sort = request('sort');
                $q->orderBy('created_at', $sort);
            })
            ->when(request('status'), function ($q) {
                $status = request('status');
                $q->where($status, 1);
            })

            ->paginate(request()->get('per_page') ?? 10);

        $categories = Category::pluck('name', 'slug');

        return view('home', [
            'products' => $products,
            'categories' => $categories,
        ]);
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
}
