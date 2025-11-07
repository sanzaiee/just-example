@extends('backend.master')
@section('content')

<style>
    .img-block img{
        max-width: 100px;
        max-height: 100px;

    }
</style>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="container-padding">
        <div class="page-header">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Site Settings</li>
            </ol>
        </div>
        @if($errors->count() > 0) {{ $errors }} @endif
      <!-- END BREADCRUMB -->
      <!-- START card -->
      <div class="card">
        {{-- <div class="card-title "> --}}
            <h5 class="card-header">Site Settings</h5>

          {{-- @include('admin.includes._messages') --}}
          {{-- <div class="card-title">
            Site Settings
          </div> --}}
        {{-- </div> --}}
        <div class="card-body">
          <form action="{{route('site.update')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class='row'>
                <input type="hidden" value="{{$slug}}" name="slug">

                @foreach ($siteSettings as $siteSetting)
                    <div class="col-md-6 mb-3">
                        <label for="{{$siteSetting->attribute}}">{{$siteSetting->field_name}}</label>
                        @switch($siteSetting->field_type)
                            @case('text')
                                <input type="{{$siteSetting->field_type}}" class="form-control" id="{{$siteSetting->attribute}}" name="{{$siteSetting->attribute}}" value="{{$siteSetting->value}}">
                            @break

                            @case('color')
                                <input type="{{$siteSetting->field_type}}" class="form-control" id="{{$siteSetting->attribute}}" name="{{$siteSetting->attribute}}" value="{{$siteSetting->value}}">
                            @break

                            @case('file')
                                <input type="{{$siteSetting->field_type}}" class="form-control" id="{{$siteSetting->attribute}}" name="{{$siteSetting->attribute}}" value="{{$siteSetting->value}}">
                            @break

                            @case('textarea')
                                <textarea class="form-control" id="summernote-{{$siteSetting->attribute}}" name="{{$siteSetting->attribute}}" rows="3" id="textarea1">{!! $siteSetting->value !!}</textarea>
                            @break

                            @default

                        @endswitch

                        @foreach(['logo', 'fav'] as $attribute)
                            @if($siteSetting->attribute == $attribute)
                                <div class="img-block">
                                    <img src="{{ $siteSetting->getImage($attribute) }}" />
                                </div>
                            @endif
                        @endforeach

                        @foreach(['about', 'service','blog','contact','gallery'] as $banner)
                            @if($siteSetting->attribute == $banner.'_banner_image')
                                <div class="img-block">
                                    <img src="{{ $siteSetting->getBannerImage($banner) }}" />
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
            <br>
            <div class="form-group text-center">
              <button type="submit" class="btn btn-info">Update</button>
            </div>
          </form>
        </div>
      </div>
      <!-- END card -->
    </div>
  </div>
@endsection
@section('roleJs')
<script>
    $('textarea').each(function() {
        var value = $(this).attr('id');
        $('#'+value).summernote();
    });
</script>
@endsection
