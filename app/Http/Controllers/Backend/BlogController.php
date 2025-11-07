<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::query();

        if (request()->has('query')) {
            $query = '%' . request()->input('query') . '%';

            $blogs = $blogs->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('title', 'like', $query)
                            ->orWhere('slug', 'like', $query)
                            ->orWhere('author', 'like', $query);

            });
        }

        $blogs = $blogs->paginate(15);
        $tags = Tag::all();

        return view('backend.blog.index', [
            'records' => $blogs,
            'tags' => $tags,
            'modelName' => "Blog",
            ] + getRoutes('blog'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $blog = [];
        $tags = Tag::all();
        return view('backend.blog.form', [
            'blog' => $blog,
            'tags' => $tags,
            'model' => $blog ?? [],
            'modelName' => "Blog",
            ] + getRoutes('blog'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request['slug'] = Str::slug($request['name'], '-');
        $data = $this->validate($request, [
            'title' => 'required',
            // 'slug' => 'required|unique:blogs,slug',
            'description' => 'nullable',
            'short_description' => 'nullable',
            'image' => 'required|file|mimes:png,jpg,jpeg,gif',
            'tags' => 'nullable',
            'author' => 'nullable',
            'meta_title' => 'nullable',
            'meta_keywords' => 'nullable',
            'meta_description' => 'nullable',
        ]);

        $blog = Blog::create($data);

        if (isset($data['tags'])) {
            $blog->tags()->sync($data['tags']);
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $blog->addMediaFromRequest('image')->toMediaCollection('image');
        }
        return redirect()->route('blog.index')->with('success', 'Successfully Updated');
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
        $tags = Tag::all();
        $blog = Blog::findOrFail($id);
        $tagSelected = $blog->tags->pluck('name');
        return view('backend.blog.form', [
            'tags' => $tags,
            'tagSelected' => $tagSelected,
            'model' => $blog ?? [],
            'modelName' => "Blog",
            ] + getRoutes('blog'));

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
        $blog = Blog::findorFail($id);

        // $request['slug'] = Str::slug($request['name'], '-');
        $data = $this->validate($request, [
            'title' => 'required',
            // 'slug' => 'required|unique:blogs,slug,' . $id,
            'status' => 'required',
            'image' => 'nullable|file|mimes:png,jpg,jpeg,gif',
            'description' => 'nullable',
            'author' => 'nullable',
            'short_description' => 'nullable',
            'meta_title' => 'nullable',
            'meta_keywords' => 'nullable',
            'meta_description' => 'nullable',
            'tags' => 'nullable',
        ]);
        $blog->update($data);

        if (isset($data['tags'])) {
            $blog->tags()->sync($data['tags']);
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($blog->hasMedia('image')) {
                $blog->getMedia('image')[0]->delete();
            }
            $blog->addMediaFromRequest('image')->toMediaCollection('image');
        }
        return redirect()->route('blog.index')->with('success', 'Successfully Updated');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Blog::findorFail($id);

        $blog->delete();

        // if ($blog->hasMedia('image')) {
        //     $blog->getMedia('image')[0]->delete();
        // }
        session()->flash('danger', 'Blog Deleted Successfully');
        return redirect()->back();
    }


    // public function destroyComment($id)
    // {
    //     $blog = Comment::findorFail($id);

    //     $blog->delete();
    //     session()->flash('danger', 'Blog Comment Deleted Successfully');
    //     return redirect()->back();
    // }

    // public function comment($slug)
    // {
    //     $blog = Blog::where('slug', $slug)->first();

    //     $comments = Comment::orderBy('id', 'desc')->where('blog_id', $blog->id)->get();
    //     return view('backend.blog.comments', compact('comments'));
    // }
}
