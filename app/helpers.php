<?php

use App\Models\Setting;

if (!function_exists('get_logo')) {
    function get_logo($name)
    {
        switch ($name) {
            case 'logo':
                $setting = Setting::where('attribute', 'logo')->first();
                return $setting->logo ?? '';
                break;

            case 'fav':
                $setting = Setting::where('attribute', 'fav')->first();
                return $setting->fav ?? '';
                break;

            default:
                # code...
                break;
        }
    }
}

if (!function_exists('get_site_name')) {
    function get_site_name()
    {
        $setting = Setting::where('setting_group_slug', 'general-information')->where('attribute', 'site_name')->first();
        return $setting?->value ?: 'Please Update Site Name';
    }
}


if (!function_exists('getRoutes')) {

    function getRoutes($model)
    {
        return [
            'routeCreate' => $model.".create",
            'routeStore' => $model.".store",
            'routeUpdate' => $model.".update",
            'routeList' => $model.".index",
            'routeEdit' => $model.".edit",
            'routeDelete' => $model.".destroy",
        ];
    }
}
if (!function_exists('getRoutesWithParams')) {
    function getRoutesWithParams($model,$params)
    {
        return [
            'routeCreate' => $model.".create",
            'routeList' => $model.".index",
            'routeStore' => $model.".store",
            'routeUpdate' => $model.".update",
            'routeEdit' => $model.".edit",
            'routeDelete' => $model.".destroy",
        ];
    }
}



if (!function_exists('getAvatarName')) {
    function getAvatarName($string): string
    {
        $words = explode(' ', $string);

        $first_letters = array_map(fn($word) => substr($word, 0, 1), array_slice($words, 0, 3));

        return implode('', $first_letters);
    }
}

if (!function_exists('getAvatarColor')) {
    function getAvatarColor($string): string
    {
        if (!$string){
            return 'primary';
        }
        return match (strtolower($string[0])) {
            'a', 'b', 'c', 'd' => 'secondary',
            'e', 'f', 'g', 'h' => 'success',
            'i', 'j', 'k', 'l' => 'danger',
            'm', 'n', 'o', 'p' => 'warning',
            'q', 'r', 's', 't' => 'info',
            'u', 'v', 'w', 'x' => 'dark',
            default => 'primary'
        };
    }
}
