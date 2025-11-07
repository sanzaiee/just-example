<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OurTeam;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = OurTeam::query();

        if (request()->has('query')) {
            $query = '%' . request()->input('query') . '%';

            $teams = $teams->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('title', 'like', $query);
            });
        }

        $teams = $teams->paginate(15);

        return view('backend.team.index', [
            'records' => $teams,
            'modelName' => "Team",
        ] + getRoutes('team'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $team = [];
        return view('backend.team.form', [
            'team' => $team,
            'model' => $team ?? [],
            'modelName' => "Team",
            ]+ getRoutes('team'));
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
            'description' => 'nullable',
            'short_description' => 'nullable',
            'post' => 'required',
            'image' => 'required|file|mimes:png,jpg,jpeg,gif',
            'position' => 'required',
            'facebook' => 'nullable',
            'instagram' => 'nullable',
            'youtube' => 'nullable',
            'linkedin' => 'nullable',
            'twitter' => 'nullable',
        ]);

        $team = OurTeam::create($data);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $team->addMediaFromRequest('image')->toMediaCollection('image');
        }
        return redirect()->route('team.index')->with('success', 'Successfully Updated');
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
        $team = OurTeam::findOrFail($id);
        return view('backend.team.form', [
            'team' => $team,
            'model' => $team ?? [],
            'modelName' => "Team",
        ]+ getRoutes('team'));
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
        $team = OurTeam::findorFail($id);

         $data = $this->validate($request, [
            'title' => 'nullable',
            'description' => 'nullable',
            'post' => 'required',
            'short_description' => 'nullable',
            'image' => 'nullable|file|mimes:png,jpg,jpeg,gif',
            'position' => 'required',
            'facebook' => 'nullable',
            'instagram' => 'nullable',
            'youtube' => 'nullable',
            'linkedin' => 'nullable',
            'twitter' => 'nullable',
        ]);
        $team->update($data);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($team->hasMedia('image')) {
                $team->getMedia('image')[0]->delete();
            }
            $team->addMediaFromRequest('image')->toMediaCollection('image');
        }
        return redirect()->route('team.index')->with('success', 'Successfully Updated');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $team = OurTeam::findorFail($id);

        $team->delete();

        // if ($team->hasMedia('image')) {
        //     $team->getMedia('image')[0]->delete();
        // }
        session()->flash('danger', 'Deleted Successfully');
        return redirect()->back();
    }
}
