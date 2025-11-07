@extends('backend.master')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Welcome</h4>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Categories</h5>

            <div class="nav-tabs-shadow nav-align-left">
                <ul class="nav nav-tabs" role="tablist">
                    @forelse ($categories as $index => $category)
                        <li class="nav-item">
                            <button type="button" class="nav-link @if($index == 0) active @endif" role="tab" data-bs-toggle="tab" data-bs-target="#navs-left-align-{{ $category->slug }}">{{ $category->name }}</button>
                        </li>
                    @empty
                    @endforelse
                </ul>
                <div class="tab-content">
                    @forelse ($categories as $index => $category)
                        <div class="tab-pane fade  @if($index == 0) show active @endif" id="navs-left-align-{{ $category->slug }}">
                            <div class="row">
                                @forelse ($category->product as $candidate)
                                    <div class="col-md-12 m-3">
                                        <div class="card mb-3">
                                            <div class="row g-0">
                                                <div class="col-md-3">
                                                    <img class="img-thumbnails card-img-left" src="{{ $candidate->image  }}" alt="Card image" width="100%" height = "200px"/>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="card-body">
                                                        <h5 class="card-title">
                                                            {{ $candidate->name }}

                                                        </h5>


                                                        <p class="card-text">
                                                            {{ $candidate->description }}
                                                        </p>
                                                        <div class="d-flex justify-content-between">
                                                            <p class="card-text">
                                                                <span class=" {{ $candidate->stock ? 'text-success' : 'text-danger' }}">
                                                                    {{ $candidate->stock ? 'In Stock' : 'Out Of Stuck' }}
                                                                </span>
                                                                <small class="text-muted">By {{ $candidate->user->name ?? '' }}</small>
                                                            </p>
                                                            <a href="{{ route('product.show', $candidate->id) }}" class="btn btn-primary btn-xs">More</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty

                                @endforelse
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
