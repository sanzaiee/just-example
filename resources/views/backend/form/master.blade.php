@extends('backend.master')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">{{ $modelName }}/</span> {{ ($model) ? "Update" : "Create" }}</h4>
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ ($model) ? "Update" : "Create" }}</h5>
            <small class="text-muted float-end">
                <a href="{{ route( $routeList,$params ?? '') }}" class="btn btn-primary">
                    List
                </a>
            </small>
        </div>
        <form class="m-t-40" method="post" action="{{ $model ? route($routeUpdate,['slug' => $params ?? '',Str::lower($modelName) =>$model->id]) : route($routeStore, $params ?? '') }}" enctype="multipart/form-data">
            @if($model)
                @method('PUT')
            @endif
            @csrf

            <div class="card-body">
                @yield('form-content')
            </div>

            <div class="card-footer float-end">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

        </form>
    </div>
</div>

@endsection

            {{-- @php
                 $data = [
                    'label' => 'Short Description',
                    'name' => 'short_description',
                    'required' => true,
                ];
            @endphp
            @include('backend.form.collection',['data' => $data, 'model' => $blog?? '']) --}}
        {{-- <div class="col-md-12">
            <label class="form-label" for="sub-description-ckeditor">Short Description</label>
            <textarea name="short_description" id="sub-description-ckeditor" class="tinymce">
                {!! old('short_description',$blog->short_description ?? '') !!}
            </textarea>
        </div> --}}




        {{-- <div class="col-md-12">
            <label class="form-label" for="description-ckeditor">Short Description</label>
                <textarea name="description" id="description-ckeditor" class="tinymce">
                {!! old('description',$blog->description ?? '') !!}
            </textarea>
        </div> --}}

        {{-- <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" for="fullname">Keyword</label>
                <input type="text" name = "keyword" class="form-control"  value="{{ $blog->keyword ?? '' }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" for="fullname">Meta Title</label>
                <input type="text" name = "meta_title" class="form-control"  value="{{ $blog->meta_title ?? '' }}">
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" for="fullname">Meta Description</label>
                <textarea name="meta_description" id="" cols="70" rows="5">{{ $blog->meta_description ?? '' }}</textarea>
            </div>
        </div> --}}



