<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = FAQ::query();

        if (request()->has('query')) {
            $query = '%' . request()->input('query') . '%';

            $faqs = $faqs->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('title', 'like', $query);
            });
        }

        $faqs = $faqs->paginate(15);

        return view('backend.faq.index', [
            'records' => $faqs,
            'modelName' => "FAQ",
        ]+ getRoutes('faq'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $faq = [];
        return view('backend.faq.form', [
            'model' => $faq ?? [],
            'modelName' => "FAQ",
        ] + getRoutes('faq'));
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
            'question' => 'nullable',
            'answer' => 'nullable',

        ]);

        $banner = FAQ::create($data);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $banner->addMediaFromRequest('image')->toMediaCollection('image');
        }
        return redirect()->route('faq.index')->with('success', 'Successfully Updated');
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
        $faq = FAQ::findOrFail($id);
        return view('backend.faq.form', [
            'model' => $faq ?? [],
            'modelName' => "FAQ",
            ] + getRoutes('faq'));
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
        $faq = FAQ::findorFail($id);

        $data = $this->validate($request, [
            'question' => 'nullable',
            'answer' => 'nullable',

        ]);
        $faq->update($data);

        return redirect()->route('faq.index')->with('success', 'Successfully Updated');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $faq = FAQ::findorFail($id);

        $faq->delete();

        session()->flash('danger', 'Deleted Successfully');
        return redirect()->back();
    }
}
