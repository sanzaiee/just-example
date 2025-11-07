<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Throwable;

class SettingController extends Controller
{
    public function view($slug)
    {
        $siteSettings = Setting::where('setting_group_slug', $slug)->get();
        return view('backend.setting.setting', compact('siteSettings', 'slug'));
    }


    public function update(Request $request)
    {
        try {
            $input = $request->input();
            unset($input['_token']);
            unset($input['slug']);

            $random = Str::random(10);
            if ($request->hasFile('logo')) {
                $site = Setting::where('attribute', 'logo')->first();
                if ($site->hasMedia('logo')) {
                    $site->getMedia('logo')[0]->delete();
                }
                $site->addMedia($request->logo)->toMediaCollection('logo');
            }

            if ($request->hasFile('fav')) {
                $site = Setting::where('attribute', 'fav')->first();
                if ($site->hasMedia('fav')) {
                    $site->getMedia('fav')[0]->delete();
                }
                $site->addMedia($request->fav)->toMediaCollection('fav');
            }

            if ($request->hasFile('about_banner_image')) {
                $site = Setting::where('attribute', 'about_banner_image')->first();
                if ($site->hasMedia('about_banner_image')) {
                    $site->getMedia('about_banner_image')[0]->delete();
                }
                $site->addMedia($request->about_banner_image)->toMediaCollection('about_banner_image');
            }



            if ($request->hasFile('about_image_first')) {
                $site = Setting::where('attribute', 'about_image_first')->first();
                if ($site->hasMedia('about_image_first')) {
                    $site->getMedia('about_image_first')[0]->delete();
                }
                $site->addMedia($request->about_image_first)->toMediaCollection('about_image_first');
            }


            if ($request->hasFile('about_image_second')) {
                $site = Setting::where('attribute', 'about_image_second')->first();
                if ($site->hasMedia('about_image_second')) {
                    $site->getMedia('about_image_second')[0]->delete();
                }
                $site->addMedia($request->about_image_second)->toMediaCollection('about_image_second');
            }

            if ($request->hasFile('about_image_third')) {
                $site = Setting::where('attribute', 'about_image_third')->first();
                if ($site->hasMedia('about_image_third')) {
                    $site->getMedia('about_image_third')[0]->delete();
                }
                $site->addMedia($request->about_image_third)->toMediaCollection('about_image_third');
            }



            foreach ($input as $attr => $value) {
                $siteSettings = Setting::firstOrNew([
                    'attribute' => $attr,
                    'setting_group_slug' => $request->slug,
                ]);

                $siteSettings->value = $value;
                $siteSettings->save();
            }

            Session::flash('success_message', 'Settings updated successfully !!');
            return redirect()->back();
        } catch (Throwable $e) {
            return response()->json($this->generalErrorResponse($e));
        }
    }
}
