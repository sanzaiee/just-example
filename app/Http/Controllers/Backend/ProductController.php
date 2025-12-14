<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;

class ProductController extends Controller
{
    public $product;

    public $tag;

    public function __construct(Product $product, Tag $tag)
    {
        $this->product = $product;
        $this->tag = $tag;
    }

    public function index()
    {
        $products = $this->product->with('user');

        if (request()->has('query')) {
            $query = '%'.request()->input('query').'%';

            $products = $products->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', $query)
                    ->orWhere('slug', 'like', $query)
                    ->orWhere('code', 'like', $query);
            });
        }

        $products = $products->paginate(15);
        $tags = $this->tag->get();

        return view('backend.product.index', [
            'records' => $products,
            'tags' => $tags,
            'modelName' => 'Product',
        ] + getRoutes('product'));
    }

    public function edit($slug)
    {
        $product = $this->product->where('slug', $slug)->firstOrFail();

        return redirect()->route('product.create', ['product' => $product->id]);
    }

    public function destroy($slug)
    {
        $product = $this->product->where('slug', $slug)->firstOrFail();
        $product->clearMediaCollection('image');

        $product->delete();

        return redirect()->route('product.index');

    }
}
