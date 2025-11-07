<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::query();

        if (request()->has('query')) {
            $query = '%' . request()->input('query') . '%';

            $galleries = $galleries->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('title', 'like', $query);
            });
        }

        $galleries = $galleries->paginate(15);

        return view('backend.gallery.index', [
            'records' => $galleries,
            'modelName' => "Gallery",
            'routeList' => "gallery.index",
            ] + getRoutes('gallery'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gallery = [];
        return view('backend.gallery.form', [
            'gallery' => $gallery,
            'model' => $gallery ?? [],
            'modelName' => "Gallery",
            ] + getRoutes('gallery'));

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
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $gallery = Gallery::create($data);

        if ($request->hasFile('image')) {
            foreach ($request->image as $image) {
                $gallery->addMedia($image)->toMediaCollection('image');
            }
        }
        return redirect()->route('gallery.index')->with('success', 'Successfully Updated');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $galleries = Gallery::find($id);

        return view('backend.gallery.show',[
                'pagination' => false,
                'search' => false,
                'records' => $galleries,
                'modelName' => "Gallery",
                'routeList' => "gallery.index",
                ] + getRoutes('gallery'));
        }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('backend.gallery.form', [
            'gallery' => $gallery,
            'model' => $gallery ?? [],
            'modelName' => "Gallery",
            ] + getRoutes('gallery'));

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
        $gallery = Gallery::findorFail($id);

        $validated = $this->validate($request, [
            'name' => 'nullable',
            'description' => 'nullable',
            'image' => 'nullable',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $gallery->update(Arr::except($validated, ['image']));

        if ($request->hasFile('image')) {
            foreach ($request->image as $image) {
                $gallery->addMedia($image)->toMediaCollection('image');
            }
        }

        return redirect()->route('gallery.index')->with('success', 'Successfully Updated');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gallery = Gallery::findorFail($id);

        $gallery->delete();

        // if ($gallery->hasMedia('image')) {
        //     $gallery->getMedia('image')[0]->delete();
        // }
        session()->flash('danger', 'Deleted Successfully');
        return redirect()->back();
    }

    public function deleteProductConfigImage($media_id)
    {
        DB::table('media')
            ->where('id', $media_id)
            ->delete();


            session()->flash('danger', 'Deleted Successfully');
            return redirect()->back();

            return response()->json([
            'status' => true,
            'message' => 'Deleted successfully.',
        ]);
    }
}
