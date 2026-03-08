<?php

namespace App\Http\Controllers\Backend;

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
        //TO:DO avoid fetching tierred pricing for index data
        $products = $this->product->with('category','brand');//->with('user');

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
        try {
            $product = $this->product->where('slug', $slug)->firstOrFail();
            // $product->clearMediaCollection('image');

            $product->delete();

            return redirect()->route('product.index')->with('success', 'Product deleted successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting product: '.$e->getMessage());
        }
    }
}
