<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('backend.master')]
class ProductSetup extends Component
{
    use WithFileUploads;

    public $product = NULL, $brands = NULL, $categories = NULL , $tags = NULL, $related_products = NULL;

    #[Validate('required|min:3|max:20')]
    public $name, $code;

    #[Validate('nullable|min:5|max:80')]
    public $short = '';

    #[Validate('nullable|min:5|max:200')]
    public $description = '',  $video_url = '';

    #[Rule('required|numeric')]
    public $price = 0;

    #[Rule('nullable|numeric')]
    public $tax = 0;

    #[Rule('nullable')]
    public $tag = [], $related_product = [];

    #[Rule('nullable|file|mimes:png,jpg,jpeg,gif')]
    public $image;

    #[Validate('required|numeric', as: 'Strike Price')]
    public $strike_price = 0;

    #[Validate('nullable|numeric', as: 'Delivery Charges')]
    public $delivery_charges = 0;

    #[Validate('required|numeric', as: 'Brand')]
    public $brand_id;

    #[Validate('required|numeric', as: 'Category')]
    public $category_id;

    #[Rule('required|boolean')]
    public $status = 1, $stock = 1, $on_sale = 0, $home_delivery = 0,
            $best_rated = 0, $feature  = 0;

    #[Url]
    public ?string $query = '';

    public $model;
    /**
     *  Component Information
     *  1 => General Overview
     *  2 => Descriptions
     *  3 => Images
     *  4 => Related Products
     *  5 => Handle Status
     *  6 => Price Setup
     */

    public $component = 1;
    public function render()
    {
        return view('livewire.product-setup');
    }

    public function selectComponent($component){
        $this->component = $component;
    }

    public function mount(){
        $this->brands = Brand::pluck('id','name');
        $this->tags = Tag::pluck('id','name');
        $this->related_products = Product::pluck('id','name');
        $this->categories = Category::where('parent_id',0)->pluck('id','name');

        $this->product = request()->query('product');
        if($this->product){
            $this->setupProduct();
        }
    }

    public $preview_image = NULL;

    public $existingProduct = NULL;
    public function save(){
        $data = $this->validate();
        $data['user_id'] = auth()->id();

        if($this->existingProduct){
            $product = $this->existingProduct;
            $product->update(collect($data)->except('image','tag','related_product')->toArray());
        }else{
            $product = Product::create(collect($data)->except('image','tag','related_product')->toArray());
        }

        if (isset($this->tag)) {
            $product->tags()->sync($this->tag);
        }

        if (isset($this->related_product)) {
            $product->relatedProducts()->sync($this->related_product);
        }

        if ($this->image && $this->image->isValid()) {
            $product->clearMediaCollection('image');
            $product->addMedia($this->image->getRealPath())
                    ->usingFileName($this->image->getClientOriginalName())
                    ->toMediaCollection('image');
        }

        return redirect()->route('product.index');

        // $this->clearImage();
        // $this->setupProduct($product);
    }

    public function setupProduct()
    {
        $this->existingProduct = Product::find($this->product);
        $this->related_products = Product::where('id','!=',$this->existingProduct->id)->pluck('id','name');

        $this->name = $this->existingProduct->name;
        $this->code = $this->existingProduct->code;
        $this->short = $this->existingProduct->short;
        $this->category_id = $this->existingProduct->category_id;
        $this->brand_id = $this->existingProduct->brand_id;
        $this->description = $this->existingProduct->description;
        $this->video_url = $this->existingProduct->video_url;
        $this->price = $this->existingProduct->price;
        $this->strike_price = $this->existingProduct->strike_price;
        $this->tax = $this->existingProduct->tax;
        $this->delivery_charges = $this->existingProduct->delivery_charges;
        // $this->view_count = $this->existingProduct->view_count;
        // $this->warrenty_period = $this->existingProduct->warrenty_period;
        // $this->warrenty_policy = $this->existingProduct->warrenty_policy;
        // $this->warrenty_type = $this->existingProduct->warrenty_type;
        $this->on_sale = $this->existingProduct->on_sale;
        $this->best_rated = $this->existingProduct->best_rated;
        $this->feature = $this->existingProduct->feature;
        $this->home_delivery = $this->existingProduct->home_delivery;
        $this->stock = $this->existingProduct->stock;
        $this->status = $this->existingProduct->status;
        $this->preview_image = $this->existingProduct->image;
        $this->tag = $this->existingProduct->tags->pluck('id');
        $this->related_product = $this->existingProduct->relatedProducts->pluck('id');
    }

    public function clearImage()
    {
        $this->image = NULL;
    }
}
