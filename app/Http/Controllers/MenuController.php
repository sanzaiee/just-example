<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuSetup;
use App\Models\SubMenuSetup;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = MenuSetup::all();
        $submenu = SubMenuSetup::where('parent_id', null)->get();
        return view('backend.menu.index', compact('menus', 'submenu'));
    }

    public function store(Request $request)
    {

        $data = $this->validate($request, [
            'title' => 'required',
            'tooltip' => 'nullable',
            'target' => 'required',
            'route' => 'nullable',
            'icon' => 'nullable',
            'enabled' => 'nullable',
            'mega_menu' => 'nullable',
            'submenus' => 'nullable',
            'position' => 'required',
            'store_id' => 'nullable',
        ]);
        $data['store_id'] = 2;
        $data['enabled'] = $this->checkEnabledStatus($data);

        $menu = MenuSetup::create($data);
        $menu->subMenus()->sync($data['submenus']);

        return redirect()->back()->with('success_message', 'Menu "' . $data['title'] . '" created successfully!');
    }

    public function subMenu()
    {
        $submenu = SubMenuSetup::where('parent_id', null)->get();
        $submenu_all = SubMenuSetup::all();
        $categories = Category::where('status', 1)->get();
        return view('backend.sub-menu.index', compact('submenu', 'categories', 'submenu_all'));
        return view('admin.menu.sub-menu', compact('submenu', 'products', 'categories'));
    }

    public function subMenuStore(Request $request)
    {
        $data = $this->validate($request, [
            'parent_id' => 'nullable',
            'title' => 'required',
            'tooltip' => 'nullable',
            'target' => 'required',
            'route' => 'nullable|unique:sub_menus,route',
            'icon' => 'nullable',
            'enabled' => 'nullable',
        ]);
        $data['enabled'] = $this->checkEnabledStatus($data);

        SubMenuSetup::create($data);

        return redirect()->back()->with('success_message', 'Sub Menu "' . $data['title'] . '" created successfully!');
    }
}
