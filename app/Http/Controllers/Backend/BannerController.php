<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::query();

        if (request()->has('query')) {
            $query = '%' . request()->input('query') . '%';

            $banners = $banners->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('title', 'like', $query);
            });
        }

        $banners = $banners->paginate(15);

        return view('backend.banner.index', [
            'records' => $banners,
            'modelName' => "Banner",
            'routeList' => "banner.index",
            ] + getRoutes('banner'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banner = [];
        return view('backend.banner.form', [
            'banner' => $banner,
            'model' => $banner ?? [],
            'modelName' => "Banner",
            ] + getRoutes('banner'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'nullable',
            'description' => 'nullable',
            'short_description' => 'nullable',
            'image' => 'required|file|mimes:png,jpg,jpeg,gif',
            'position' => 'nullable',
            'button' => 'nullable',
            'link' => 'nullable',
        ]);

        $banner = Banner::create($data);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $banner->addMediaFromRequest('image')->toMediaCollection('image');
        }
        return redirect()->route('banner.index')->with('success', 'Successfully Updated');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('backend.banner.form', [
            'banner' => $banner,
            'model' => $banner ?? [],
            'modelName' => "Banner",
            ] + getRoutes('banner'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $banner = Banner::findorFail($id);

        $data = $this->validate($request, [
            'name' => 'nullable',
            'description' => 'nullable',
            'short_description' => 'nullable',
            'image' => 'required|file|mimes:png,jpg,jpeg,gif',
            'position' => 'nullable',
            'button' => 'nullable',
            'link' => 'nullable',
        ]);
        $banner->update($data);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($banner->hasMedia('image')) {
                $banner->getMedia('image')[0]->delete();
            }
            $banner->addMediaFromRequest('image')->toMediaCollection('image');
        }
        return redirect()->route('banner.index')->with('success', 'Successfully Updated');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $banner = Banner::findorFail($id);

        $banner->delete();

        // if ($banner->hasMedia('image')) {
        //     $banner->getMedia('image')[0]->delete();
        // }
        session()->flash('danger', 'banner Deleted Successfully');
        return redirect()->back();
    }

}
