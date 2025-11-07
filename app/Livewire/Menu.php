<?php

namespace App\Livewire;

use App\Models\MenuSetup;
use Livewire\Component;

class Menu extends Component
{
    public $routeList;
    public $menus, $stores;
    public $submenu, $menu;
    public $selectedSubmenu = [], $route, $store_id, $parent_id = null, $tooltip = '', $position = '', $title = '', $target = '', $icon = '',  $menu_id = '';

    public $mega_menu = '', $footer_menu = '';

    // public $enabled = '';
    protected $rules = [
        // 'parent_id' => 'nullable',
        'title' => 'required',
        'tooltip' => 'nullable',
        'target' => 'nullable',
        'route' => 'nullable',
        'icon' => 'nullable',
        // 'enabled' => 'nullable',
        // 'menu_id' => 'nullable',
        'position' => 'required',
        'mega_menu' => 'nullable',
        'footer_menu' => 'nullable',
        // 'selectedSubmenu' => 'nullable',
    ];

    protected $messages = [
        'position.required' => 'please enter position',
        'title.required' => 'please enter title',
    ];

    public function render()
    {
        return view('livewire.menu');
    }

    public function submit()
    {
        $data = $this->validate();
        $data['mega_menu'] = empty($data['mega_menu']);
        $data['footer_menu'] = empty($data['footer_menu']);

        if (empty(request()->menu_id)) {
            $menu = MenuSetup::create($data);
        } else {
            $menu = MenuSetup::find(request()->menu_id);
            $menu->update($data);
        }

        $menu->subMenus()->sync(request()->selectedSubmenu);

        $this->menus = MenuSetup::all();
        session()->flash('success_message', 'Updated Menu!');

        $this->route = '';
        // $this->enabled = '';
        $this->title = '';
        $this->tooltip = '';
        $this->icon = '';
        $this->target = '';
        $this->position = '';
        $this->menu_id = '';
        $this->mega_menu = '';
        // $this->selectedSubmenuCheck = '';


        // $this->dispatchBrowserEvent(
        //     'alert',
        //     ['type' => 'success',  'message' => 'Created Successfully!']
        // );


        return redirect()->back()->with('success_message', 'Menu "' . $data['title'] . '" created successfully!');
    }

    public function editMenu($menu)
    {
        $this->route = $menu['route'];
        // $this->enabled = $menu['enabled'];
        $this->title = $menu['title'];
        // $this->tooltip = $menu['tooltip'];
        // $this->icon = $menu['icon'];
        // $this->target = $menu['target'];
        $this->position = $menu['position'];
        $this->menu_id = $menu['id'];
        $this->mega_menu = $menu['mega_menu'];
        $data = MenuSetup::find($menu['id']);
        $this->selectedSubmenu = $data->subMenus->map(function ($subMenus, $key) {
            return $subMenus->id;
        });
    }


    public function deleteId($id)
    {
        $this->menu_id = $id;
    }

    public function deleteMenu()
    {
        $menu = MenuSetup::find($this->menu_id);
        $menu->delete();

        $this->menu = Menu::all();

        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success',  'message' => 'Deleted Menu!']
        );
        return redirect()->route('menu.index')->with('danger', 'Deleted SubMenu!');
    }
}
