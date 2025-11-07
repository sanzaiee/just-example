<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSetup extends Model
{
    use HasFactory;

    protected $fillable = [
        'footer_menu', 'mega_menu', 'title', 'position', 'route', 'target', 'tooltip', 'icon', 'enabled'
    ];

    public function subMenus()
    {
        return $this->belongsToMany('App\Models\SubMenuSetup', 'sub_menu_setup_menu_setup')->withPivot('sub_menu_setup_id');
    }

    public function subMenusId()
    {
        return $this->belongsToMany('App\Models\SubMenuSetup', 'sub_menu_setup_menu_setup')->select('sub_menu_setup_id')->withPivot('sub_menu_setup_id');
    }

    public function menu()
    {
        return $this->belongsToMany('App\Models\MenuSetup', 'sub_menu_setup_menu_setup')->withPivot('menu_setup_id');
    }
}
