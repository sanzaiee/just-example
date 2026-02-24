<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\Tag;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('backend.master')]
class ProductSetup extends Component
{
    use WithFileUploads;

    public $product = null;

    public $brands = null;

    public $categories = null;

    public $tags = null;

    public $related_products = null;

    #[Validate('required|min:3|max:100')]
    public $name;

    public $code;

    #[Validate('nullable|min:5|max:80')]
    public $short = '';

    #[Validate('nullable|min:5|max:200')]
    public $description = '';

    public $video_url = '';

    #[Validate('required|numeric')]
    public $price = 0;

    #[Validate('nullable|numeric')]
    public $tax = 0;

    #[Validate('nullable')]
    public $tag = [];

    public $related_product = [];

    #[Validate('nullable|mimes:png,jpg,jpeg,gif')]
    public $image = null;

    // Multiple image upload properties
    #[Validate('nullable|array|max:5')]
    public $images = [];

    public $preview_images = [];

    public $existing_images = [];

    #[Validate('required|numeric', as: 'Strike Price')]
    public $strike_price = 0;

    #[Validate('nullable|numeric', as: 'Delivery Charges')]
    public $delivery_charges = 0;

    #[Validate('required|numeric', as: 'Brand')]
    public $brand_id;

    #[Validate('required|numeric', as: 'Category')]
    public $category_id;

    #[Validate('required|boolean')]
    public $status = 1;

    public $stock = 1;

    public $on_sale = 0;

    public $home_delivery = 0;

    public $best_rated = 0;

    public $feature = 0;

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

    // Tiered pricing properties
    public $tiered_prices = [];

    public $new_tier_quantity = '';

    public $new_tier_price = '';

    public function render()
    {
        return view('livewire.product-setup');
    }

    public function selectComponent($component)
    {
        $this->component = $component;
    }

    public function mount()
    {
        $this->brands = Brand::pluck('id', 'name');
        $this->tags = Tag::pluck('id', 'name');
        $this->related_products = Product::pluck('id', 'name');
        $this->categories = Category::where('parent_id', 0)->pluck('id', 'name');

        $this->product = request()->query('product');
        if ($this->product) {
            // Check if the product parameter is a slug or ID
            if (is_numeric($this->product)) {
                $this->setupProduct();
            } else {
                // It's a slug, find the product by slug and get its ID
                $productBySlug = Product::where('slug', $this->product)->first();
                if ($productBySlug) {
                    $this->product = $productBySlug->id;
                    $this->setupProduct();
                }
            }
        }

        // Initialize with one empty tier if it's a new product
        if (! $this->product) {
            $this->tiered_prices = [
                ['quantity' => '', 'price' => ''],
            ];
            // Initialize empty arrays for new product
            $this->preview_images = [];
            $this->existing_images = [];
        }
    }

    public $preview_image = null;

    public $existingProduct = null;

    public function save()
    {
        // Validate all form data including images
        $data = $this->validate([
            'name' => 'required|min:3|max:100',
            'code' => 'required|min:3|max:20',
            'short' => 'nullable|min:5|max:80',
            'description' => 'nullable|min:5|max:200',
            'video_url' => 'nullable|min:5|max:200',
            'price' => 'required|numeric',
            'tax' => 'nullable|numeric',
            'tag' => 'nullable|array',
            'related_product' => 'nullable|array',
            'image' => 'nullable|file|mimes:png,jpg,jpeg,gif',
            'images' => 'nullable|array|max:5',
            'images.*' => 'nullable|file|mimes:png,jpg,jpeg,gif',
            'strike_price' => 'required|numeric',
            'delivery_charges' => 'nullable|numeric',
            'brand_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'status' => 'required|boolean',
            'stock' => 'required|boolean',
            'on_sale' => 'required|boolean',
            'home_delivery' => 'required|boolean',
            'best_rated' => 'required|boolean',
            'feature' => 'required|boolean',
            'tiered_prices' => 'nullable|array',
            'tiered_prices.*.quantity' => 'nullable|numeric|min:1',
            'tiered_prices.*.price' => 'nullable|numeric|min:0',
        ]);

        $data['user_id'] = auth()->id();

        if ($this->existingProduct) {
            $product = $this->existingProduct;
            $product->update(collect($data)->except('image', 'images', 'tag', 'related_product', 'tiered_prices')->toArray());
        } else {
            $product = Product::create(collect($data)->except('image', 'images', 'tag', 'related_product', 'tiered_prices')->toArray());
        }

        if (isset($this->tag)) {
            $product->tags()->sync($this->tag);
        }

        if (isset($this->related_product)) {
            $product->relatedProducts()->sync($this->related_product);
        }

        // Save tiered prices
        $this->saveTieredPrices($product);

        // Handle single image (legacy support)
        if ($this->image && $this->image->isValid()) {
            $product->clearMediaCollection('image');
            $product->addMedia($this->image->getRealPath())
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('image');
        }

        // Handle multiple images
        $this->saveMultipleImages($product);

        return redirect()->route('product.index');

        // $this->clearImage();
        // $this->setupProduct($product);
    }

    public function setupProduct()
    {
        $this->existingProduct = Product::find($this->product);
        $this->related_products = Product::where('id', '!=', $this->existingProduct->id)->pluck('id', 'name');

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

        // Load existing tiered prices
        $this->tiered_prices = $this->existingProduct->tieredPrices->map(function ($price) {
            return [
                'quantity' => $price->quantity,
                'price' => $price->price,
            ];
        })->toArray();

        // If no tiered prices exist, add empty row
        if (empty($this->tiered_prices)) {
            $this->tiered_prices = [
                ['quantity' => '', 'price' => ''],
            ];
        }

        // Load existing multiple images
        $this->loadExistingImages();
    }

    public function clearImage()
    {
        $this->image = null;
    }

    // Tiered pricing methods
    public function addTier()
    {
        $this->tiered_prices[] = ['quantity' => '', 'price' => ''];
    }

    public function removeTier($index)
    {
        unset($this->tiered_prices[$index]);
        $this->tiered_prices = array_values($this->tiered_prices);

        // Ensure at least one empty row exists
        if (empty($this->tiered_prices)) {
            $this->tiered_prices = [['quantity' => '', 'price' => '']];
        }
    }

    public function saveTieredPrices($product)
    {
        // Remove existing tiered prices
        $product->tieredPrices()->delete();

        // Save new tiered prices
        foreach ($this->tiered_prices as $tier) {
            if (! empty($tier['quantity']) && ! empty($tier['price'])) {
                ProductPrice::create([
                    'product_id' => $product->id,
                    'quantity' => $tier['quantity'],
                    'price' => $tier['price'],
                ]);
            }
        }
    }

    // Multiple image handling methods
    public function loadExistingImages()
    {
        if ($this->existingProduct) {
            $mediaItems = $this->existingProduct->getMedia('images');
            $this->existing_images = $mediaItems->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'name' => $media->file_name,
                    'uuid' => $media->uuid,
                ];
            })->toArray();
        }
    }

    public function saveMultipleImages($product)
    {
        // Save new uploaded images
        if (! empty($this->images)) {
            foreach ($this->images as $image) {
                if ($image && $image->isValid()) {
                    $product->addMedia($image->getRealPath())
                        ->usingFileName($image->getClientOriginalName())
                        ->toMediaCollection('images');
                }
            }
            // Clear the images array after processing
            $this->images = [];
        }
    }

    public function removeExistingImage($index)
    {
        if (isset($this->existing_images[$index])) {
            $mediaId = $this->existing_images[$index]['id'];
            $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
            if ($media) {
                $media->delete();
            }
            unset($this->existing_images[$index]);
            $this->existing_images = array_values($this->existing_images);
        }
    }

    public function removePreviewImage($index)
    {
        if (isset($this->preview_images[$index])) {
            unset($this->preview_images[$index]);
            $this->preview_images = array_values($this->preview_images);
        }
    }

    public function updatedImages()
    {
        // This method is called when images are uploaded
        foreach ($this->images as $image) {
            if ($image && $image->isValid()) {
                $this->preview_images[] = [
                    'url' => $image->temporaryUrl(),
                    'name' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                    'type' => $image->getMimeType(),
                ];
            }
        }
        // Don't clear the images array here - let the save method handle it
        // $this->images = [];
    }
}
