<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category as ModelsCategory;
use App\Models\Product;

class FrontendController extends Controller
{
    public function index()
    {
        $query = request()->get('search', '');
        $category = request()->get('category', '');
        $brand = request()->get('brand', '');
        $sort = request()->get('sort', 'latest');
        $pageSize = request()->get('per_page', 8);

        // Base query with relationships
        $baseQuery = Product::with(['category', 'user', 'brand', 'tieredPrices']);

        // Apply filters
        $baseQuery->when($query, function ($q) use ($query) {
            $q->where(function ($sub) use ($query) {
                $sub->where('name', 'like', "%$query%")
                    ->orWhere('description', 'like', "%$query%")
                    ->orWhere('code', 'like', "%$query%");
            });
        });

        $baseQuery->when($category, fn ($q) => $q->where('category_id', $category));
        $baseQuery->when($brand, fn ($q) => $q->where('brand_id', $brand));

        // Sorting
        $baseQuery->orderBy('created_at', $sort === 'oldest' ? 'asc' : 'desc');

        // Paginated products
        $products = (clone $baseQuery)
            ->paginate($pageSize)
            ->appends(request()->all());

        $featuredProducts = (clone $baseQuery)
            ->where('feature', true)
            ->limit(8)
            ->get();

        $bestSellers = (clone $baseQuery)
            ->where('best_rated', true)
            ->limit(8)
            ->get();

        $onSaleProducts = (clone $baseQuery)
            ->where('on_sale', true)
            ->limit(8)
            ->get();

        // Filters
        $allCategories = ModelsCategory::orderBy('name')->get();
        $allBrands = Brand::orderBy('name')->get();

        return view('backend.index', compact(
            'products',
            'allCategories',
            'allBrands',
            'featuredProducts',
            'bestSellers',
            'onSaleProducts'
        ));
    }
}
