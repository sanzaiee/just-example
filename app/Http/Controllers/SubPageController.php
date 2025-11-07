<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\SubPages;
use Illuminate\Http\Request;

class SubPageController extends Controller
{
    public function index($page){
        $page = Page::where('slug',$page)->first();
        $pages = SubPages::query();

        if (request()->has('query')) {
            $query = '%' . request()->input('query') . '%';

            $pages = $pages->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('title', 'like', $query)
                            ->orWhere('slug', 'like', $query)
                            ->orWhere('sub_title', 'like', $query);

            });
        }

        $pages = $pages->paginate(15);

        return view('backend.sub-page.index', [
            'page' => $page,
            'records' => $pages,
            'modelName' => "SubPage",
            'params' => $page->slug,
            ] + getRoutesWithParams('sub-pages',$page->slug));
    }

    public function create($slug)
    {
        $page = [];
        $pages = Page::all();
        return view('backend.sub-page.form', [
            'page' => $page,
            'pages' => $pages,
            'model' => $page ?? [],
            'modelName' => "Sub Page",
            'params' => $slug,
            ] + getRoutesWithParams('sub-pages',$slug));

    }

    public function store(Request $request,$page)
    {
        $page = Page::where('slug',$page)->first();
        $data = $this->validate($request, [
            'title' => 'required',
            'sub_title' => 'nullable',
            'position' => 'nullable',
            'description' => 'nullable',
            'short_description' => 'nullable',
            // 'image' => 'nullable|file|mimes:png,jpg,jpeg,gif',
        ]);

        $data['page_id'] = $page->id;
        $sub_page = SubPages::create($data);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $sub_page->addMediaFromRequest('image')->toMediaCollection('image');
        }
        return redirect()->route('sub-pages.index',$page->slug)->with('success', 'Successfully Updated');
    }

    public function edit(Request $request,$page,$id){
        $page = Page::where('slug',$page)->first();
        $pages = Page::all();
        $sub_page = SubPages::findOrFail($id);
        return view('backend.sub-page.form', [
            'page' => $page,
            'pages' => $pages,
            'model' => $sub_page ?? [],
            'modelName' => "Sub Page",
            'params' => $page->slug,
            ] + getRoutesWithParams('sub-pages',$page->slug));
    }

    public function update(Request $request,$page,$id)
    {
        $sub_page = SubPages::findorFail($id);
        $page = Page::where('slug',$page)->first();

        $data = $this->validate($request, [
            'title' => 'required',
            'sub_title' => 'nullable',
            'position' => 'nullable',
            'description' => 'nullable',
            'short_description' => 'nullable',
            // 'image' => 'nullable|file|mimes:png,jpg,jpeg,gif',
            'page_id' => 'required'
        ]);
        $sub_page->update($data);
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($sub_page->hasMedia('image')) {
                $sub_page->getMedia('image')[0]->delete();
            }
            $sub_page->addMediaFromRequest('image')->toMediaCollection('image');
        }
        return redirect()->route('sub-pages.index',$page->slug)->with('success', 'Successfully Updated');
    }

    public function destroy($id)
    {
        $page = SubPages::findorFail($id);

        $page->delete();

        // if ($page->hasMedia('image')) {
        //     $page->getMedia('image')[0]->delete();
        // }
        session()->flash('danger', 'Page Deleted Successfully');
        return redirect()->back();
    }
}
