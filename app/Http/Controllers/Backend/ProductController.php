<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public $product, $tag;

    public function __construct(Product $product, Tag $tag)
    {
        $this->product = $product;
        $this->tag = $tag;
    }
    public function index()
    {
        $products = $this->product->with('user');

        if (request()->has('query')) {
            $query = '%' . request()->input('query') . '%';

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
            'modelName' => "Product",
            ] + getRoutes('product'));
    }

    public function show($product)
    {
        $product = $this->product->with([
            'category','brand',
            'relatedProducts' => function ($query) {
                $query->limit(4);
            }
        ])->findOrFail($product);

        $cacheKey = 'product_viewed_' . $product->id . '_' . session()->getId();

        if (!Cache::has($cacheKey)) {
            $product->increment('view_count'); // atomic increment
            Cache::put($cacheKey, true, now()->addHour());
        }
        return view('backend.product.show',compact('product'));
    }
    public function edit($id)
    {
        return redirect()->route('product.create',['product' => $id]);
    }

    public function destroy($id)
    {
        $product = $this->product->findOrFail($id);
        $product->clearMediaCollection('image');

        $product->delete();

        return redirect()->route('product.index');

    }
}
