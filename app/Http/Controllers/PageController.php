<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::query();

        if (request()->has('query')) {
            $query = '%' . request()->input('query') . '%';

            $pages = $pages->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('title', 'like', $query)
                            ->orWhere('slug', 'like', $query)
                            ->orWhere('sub_title', 'like', $query);

            });
        }

        $pages = $pages->paginate(15);

        return view('backend.page.index', [
            'records' => $pages,
            'modelName' => "Page",
            ] + getRoutes('page'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = [];
        return view('backend.page.form', [
            'page' => $page,
            'model' => $page ?? [],
            'modelName' => "Page",
            ] + getRoutes('page'));

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
            'title' => 'required',
            'sub_title' => 'nullable',
            'position' => 'nullable',
            'description' => 'nullable',
            'short_description' => 'nullable',
            'image' => 'nullable|file|mimes:png,jpg,jpeg,gif',
        ]);

        $page = Page::create($data);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $page->addMediaFromRequest('image')->toMediaCollection('image');
        }
        return redirect()->route('page.index')->with('success', 'Successfully Updated');
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
        $page = Page::findOrFail($id);
        return view('backend.page.form', [
            'model' => $page ?? [],
            'modelName' => "Page",
            ] + getRoutes('page'));

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
        $page = Page::findorFail($id);

        $data = $this->validate($request, [
            'title' => 'required',
            'sub_title' => 'nullable',
            'position' => 'nullable',
            'description' => 'nullable',
            'short_description' => 'nullable',
            'image' => 'nullable|file|mimes:png,jpg,jpeg,gif',
        ]);
        $page->update($data);
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($page->hasMedia('image')) {
                $page->getMedia('image')[0]->delete();
            }
            $page->addMediaFromRequest('image')->toMediaCollection('image');
        }
        return redirect()->route('page.index')->with('success', 'Successfully Updated');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::findorFail($id);

        $page->delete();

        // if ($page->hasMedia('image')) {
        //     $page->getMedia('image')[0]->delete();
        // }
        session()->flash('danger', 'Page Deleted Successfully');
        return redirect()->back();
    }
}
