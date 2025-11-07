<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $contacts = [];
        $tags = Tag::paginate(15);
        return view('backend.tag.index', compact('tags', 'contacts'));
    }

    public function store(Request $request)
    {
        $request['slug'] = Str::slug($request['name'], '-');
        $data = $this->validate($request, [
            'name' => 'required',
            'slug' => 'required|unique:tags,slug',

        ]);
        Tag::create($data);

        return redirect()->back()->with('success', 'Successfully Updated');
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $request['slug'] = Str::slug($request['name'], '-');
        $data = $this->validate($request, [
            'name' => 'required',
            'slug' => 'required|unique:tags,slug,' . $id,
        ]);

        $tag->update($data);
        return redirect()->back()->with('success', 'Successfully Updated');
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        session()->flash('danger', 'Deleted Successfully');
        return redirect()->back();
    }
}
