<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::query();

        if (request()->has('query')) {
            $query = '%' . request()->input('query') . '%';

            $testimonials = $testimonials->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('title', 'like', $query);
            });
        }

        $testimonials = $testimonials->paginate(15);

        return view('backend.testimonial.index', [
            'records' => $testimonials,
            'modelName' => "Testimonial",
        ] + getRoutes('testimonial'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $testimonial = [];
        return view('backend.testimonial.form', [
            'testimonial' => $testimonial,
            'model' => $testimonial ?? [],
            'modelName' => "Testimonial",
            ]+ getRoutes('testimonial'));
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
            'name' => 'required',
            'description' => 'nullable',
            'post' => 'required',
            'image' => 'required|file|mimes:png,jpg,jpeg,gif',
            'position' => 'required',
            'facebook' => 'nullable',
            'instagram' => 'nullable',
            'youtube' => 'nullable',
            'linkedin' => 'nullable',
            'twitter' => 'nullable',
        ]);

        $testimonial = Testimonial::create($data);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $testimonial->addMediaFromRequest('image')->toMediaCollection('image');
        }
        return redirect()->route('testimonial.index')->with('success', 'Successfully Updated');
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
        $testimonial = Testimonial::findOrFail($id);
        return view('backend.testimonial.form', [
            'testimonial' => $testimonial,
            'model' => $testimonial ?? [],
            'modelName' => "Testimonial",
        ]+ getRoutes('testimonial'));
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
        $testimonial = Testimonial::findorFail($id);

         $data = $this->validate($request, [
            'name' => 'nullable',
            'description' => 'nullable',
            'post' => 'required',
            'image' => 'nullable|file|mimes:png,jpg,jpeg,gif',
            'position' => 'required',
            'facebook' => 'nullable',
            'instagram' => 'nullable',
            'youtube' => 'nullable',
            'linkedin' => 'nullable',
            'twitter' => 'nullable',
        ]);
        $testimonial->update(Arr::except($data,'image'));

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($testimonial->hasMedia('image')) {
                $testimonial->getMedia('image')[0]->delete();
            }
            $testimonial->addMediaFromRequest('image')->toMediaCollection('image');
        }
        return redirect()->route('testimonial.index')->with('success', 'Successfully Updated');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $testimonial = Testimonial::findorFail($id);

        $testimonial->delete();

        // if ($testimonial->hasMedia('image')) {
        //     $testimonial->getMedia('image')[0]->delete();
        // }
        session()->flash('danger', 'Deleted Successfully');
        return redirect()->back();
    }

}
