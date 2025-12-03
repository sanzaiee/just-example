<?php

namespace App\Http\Controllers;

use App\Models\Category as ModelsCategory;

class FrontendController extends Controller
{
    public function index()
    {
        $query = request()->get('search', '');
        $category = request()->get('category', '');
        $brand = request()->get('brand', '');
        $sort = request()->get('sort', 'latest');
        $page = request()->get('page', 1);

        // Query products directly with relationships for better performance
        $productsQuery = \App\Models\Product::with(['category', 'user', 'brand', 'tieredPrices']);

        // Apply search filter
        if ($query) {
            $productsQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', '%'.$query.'%')
                    ->orWhere('description', 'like', '%'.$query.'%')
                    ->orWhere('code', 'like', '%'.$query.'%');
            });
        }

        // Apply category filter
        if ($category) {
            $productsQuery->where('category_id', $category);
        }

        // Apply brand filter
        if ($brand) {
            $productsQuery->where('brand_id', $brand);
        }

        // Apply sorting
        switch ($sort) {
            case 'oldest':
                $productsQuery->orderBy('created_at', 'asc');
                break;
            default: // latest
                $productsQuery->orderBy('created_at', 'desc');
        }

        // Get paginated products
        $products = $productsQuery->paginate(12);

        // Get all categories and brands for filters
        $allCategories = ModelsCategory::orderBy('name')->get();
        $allBrands = \App\Models\Brand::orderBy('name')->get();

        return view('backend.index', compact('products', 'allCategories', 'allBrands'));
    }
}
