<?php

namespace App\Helpers;

use App\Models\MenuSetup;

class MenuHelper
{

    public $apphelper;
    public function __construct(AppHelper $apphelper)
    {
        $this->apphelper = $apphelper;
    }

    public function routeList()
    {
        return [
            'View About Page' => 'about.us',
            'View Contact Page' => 'contact.us',
            'View Contact FAQ' => 'faq',
            'View Blog List' => 'blog.list',
            'View Cart' => 'cart',
            'View Home Page' => 'landing.page',
            'View Product Search' => 'product.search',
            'View Client Dashboard' => 'client.dashboard',
            'View Client Wishlist' => 'client.wishlist',
            'View Client Register' => 'client.register',
            'View Client Login' => 'client.login',
            'View Client Logout' => 'client.logout',
            'View Terms and Condition' => 'terms.condition',
            'View Privacy and Policy' => 'privacy.policy',
        ];
    }

    public function menu()
    {
        return MenuSetup::where('enabled', 1)->orderBy('position', 'asc')->where('footer_menu', 0)->get();
    }

    public function footerMenu()
    {
        return MenuSetup::where('enabled', 1)->orderBy('position', 'asc')->where('footer_menu', 1)->get();
    }
}
