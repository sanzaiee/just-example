<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\SubMenuSetup;
use Illuminate\Support\Arr;
use Livewire\Component;

class SubMenu extends Component
{
    public $actionList;
    public $submenu;
    public $products;
    public $categories;
    public $submenu_all;
    public $selectedCategory = null;
    public $parent_id = '', $mega_menu = '', $tooltip = '', $position = '', $title = '', $target = '', $icon = '', $enabled = '', $submenu_id = '', $store_id = '';

    public $selectedProduct = null, $stores = [];
    public $categorySlug = null, $prod_action = '', $prod_attribute = '';
    public $productSlug = null, $action = '', $attribute = '';

    public function render()
    {
        return view('livewire.sub-menu');
    }

    public function updatedSelectedCategory($category_id)
    {
        $category = Category::find($category_id);
        $this->categorySlug = $category->slug;
        $this->action = "product-by.category";
        $this->attribute = $category->slug;

        $this->products = $category->products;
    }

    public function updatedSelectedProduct($product_id)
    {
        $product = Product::find($product_id);
        if ($product) {
            $this->prod_action = "product.detail";
            $this->prod_attribute = $product->slug;
        } else {
            $this->prod_action = '';
            $this->prod_attribute = '';
        }
    }

    public function mount()
    {
        $this->products = collect();
        // $this->action = null;
        // $this->attribute = null;
    }

    protected $rules = [
        'title' => 'required',
        'tooltip' => 'nullable',
        'target' => 'nullable',
        'action' => 'nullable',
        'attribute' => 'nullable',
        'icon' => 'nullable',
        // 'enabled' => 'nullable',
        'submenu_id' => 'nullable',
        'position' => 'required',
        'parent_id' => 'nullable',
        'store_id' => 'required',
    ];

    protected $messages = [
        'position.required' => 'Please Enter Position',
        'store_id.required' => 'Please Select A Store',
        'title.required' => 'Please Enter Title',
    ];

    public function submit()
    {
        $data = $this->validate();
        if ($this->prod_action && $this->prod_attribute) {
            $data['action'] = $this->prod_action;
            $data['attribute'] = $this->prod_attribute;
        }
        // if (!empty($data['enabled'])) {
        //     $data['enabled'] = 1;
        // } else {
        //     $data['enabled'] = 0;
        // }
        if (empty($data['parent_id'])) {
            $data['parent_id'] = NULL;
            // $data = Arr::except($data, 'parent_id');
        }
        if (empty($data['submenu_id'])) {
            SubMenuSetup::create($data);
        } else {
            $menu = SubMenuSetup::find($data['submenu_id']);
            $menu->update($data);
        }

        $this->submenu = SubMenuSetup::where('parent_id', null)->get();

        $this->action = '';
        $this->attribute = '';
        $this->enabled = '';
        $this->parent_id = '';
        $this->title = '';
        $this->tooltip = '';
        $this->icon = '';
        $this->target = '';
        $this->position = '';
        $this->submenu_id = '';
        $this->store_id = '';
        $this->selectedCategory = null;


        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success',  'message' => 'Updated SubMenu!']
        );


        return back();
    }

    public function deleteId($id)
    {
        $this->submenu_id = $id;
    }

    public function editMenu($menu)
    {
        $this->action = $menu['action'];
        $this->attribute = $menu['attribute'];

        $this->enabled = $menu['enabled'];
        $this->parent_id = $menu['parent_id'] ?? $this->parent_id;
        $this->title = $menu['title'];
        $this->tooltip = $menu['tooltip'];
        $this->icon = $menu['icon'];
        $this->target = $menu['target'];
        $this->position = $menu['position'];
        $this->submenu_id = $menu['id'];
        $this->store_id = $menu['store_id'];

        if ($menu['parent_id'] != null) {
            $this->selectedCategory = 1;
        }
    }

    public function deleteMenu()
    {
        $menu = SubMenuSetup::find($this->submenu_id);
        $menu->delete();

        $this->submenu = SubMenuSetup::where('parent_id', null)->get();

        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success',  'message' => 'Deleted SubMenu!']
        );
        return redirect()->route('submenu.index')->with('danger', 'Deleted SubMenu!');
    }
}
