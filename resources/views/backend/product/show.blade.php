@extends('backend.master')

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <a href="{{ route('admin.dashboard', ['brand' => $product->brand->slug]) }}"><span class="text-muted fw-light">{{ $product->brand->name }}</span></a> / {{ $product->name }}
        </h4>
        <div class="card h-90 shadow-sm">
            <div class="card-body d-flex flex-column">
                <div class="row">
                    <div class="col-md-4">
                        @if ($product->images)
                        <div id="extra-images">
                            <div class="carousel">
                                <ul class="carousel_inner">
                                    @if ($product->images)
                                        @foreach ($product->images as $image)
                                            <li class="item" style="background-image: url({{ $image->getfullUrl() }});"
                                                data-url="{{ $image->getfullUrl() }}">
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="item" style="background-image: url({{ $product->image }});"
                                            data-url="{{ $product->image }}">
                                        </li>
                                        <span class="avatar-initial rounded-circle bg-label-{{ getAvatarColor($item->name) }}">{{ getAvatarName($item->name) }}</span>
                                    @endif

                                </ul>
                            </div>
                        </div>
                        @else
                            <div class="avatar-container mb-3">
                                <div class="avatar-initial rounded-circle" style="min-height: 200px;display: flex; justify-content: center; align-items: center;">
                                    <span style="display: inline-flex; justify-content: center; align-items: center; width: 200px; height: 200px; border-radius: 50%;" class="avatar-initial rounded-circle bg-label-{{ getAvatarColor($product->name) }}">{{ getAvatarName($product->name) }}</span>
                                </div>
                            </div>
                        @endif

                        {{--
                        <div class="overflow-hidden p-2">
                            <img src="{{ $product->image }}" alt="{{ $product->slug }}"
                                class="w-100 h-100 object-fit-cover">
                        </div> --}}
                    </div>
                    <div class="col-md-8">
                        <h4 class="card-title">{{ $product->name }}</h4>
                        <hr>
                        {{-- <div class="d-flex justify-content-between">
                            <p class="fw-bold">By {{ $product->user->name }}</p>
                            <p class="badge bg-primary">{{ $product->brand->name }}</p>

                            <div class="badges">
                                <span class="badge bg-primary">{{ $product->category->name }}</span>
                            </div>
                        </div> --}}

                        <p>
                            {{ $product->description }}
                        </p>

                        <hr>

                        <div class="mt-2 d-flex justify-content-between">
                            <livewire:cart-setup :product="$product" :detail="true" />
                            <div class="badges">
                                @if ($product->best_rated)
                                    <span class="badge bg-warning">Best Rated</span>
                                @endif

                                @if ($product->on_sale)
                                    <span class="badge bg-primary">On Sale</span>
                                @endif
                                {{-- <span
                                    class="badge {{ $product->home_delivery ? 'bg-success' : 'bg-danger' }}">{{ $product->stock ? 'Delivery' : 'No Delivery' }}</span> --}}
                                <span
                                    class="badge {{ $product->stock ? 'bg-success' : 'bg-danger' }}">{{ $product->stock ? 'In Stock' : 'Out of Stock' }}</span>
                            </div>
                        </div>
                        <hr>
                        {{-- <p class="border p-2">
                            <span class="btn btn-primary btn-sm ">{{ $product->view_count }}</span> <span
                                class="text-decoration-underline fw-bold">people have viewed this product. </span>
                        </p> --}}

                    </div>

                    @if ($product->relatedProducts->count() > 0)
                        <hr class="m-3">
                        <div class="col-md-12">
                            <h4 class="card-title text-center">Related Products</h4>
                            <div class="card">
                                <div class="row">
                                    @forelse ($product->relatedProducts as $item)
                                        <div class="col-md-3 mb-1">
                                            <div class="card h-100 shadow-sm">
                                                <div class="position-relative overflow-hidden p-2" style="height: 200px;">
                                                    <img src="{{ $item->image }}" alt="{{ $item->slug }}"
                                                        class="w-100 h-100 object-fit-cover rounded">
                                                </div>

                                                <div class="card-body d-flex flex-column">
                                                    <a href="{{ route('product.show', $item->slug) }}">
                                                        <h5 class="card-title">{{ $item->name }}</h5>
                                                    </a>
                                                    <p class="text-muted small text-truncate">
                                                        {{ $item->short }}
                                                    </p>

                                                    <!-- Buttons -->
                                                    <div class="mt-auto d-flex justify-content-between gap-2">
                                                        <livewire:cart-setup :product="$item" :detail="false" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/wimmviewer/dist/css') }}/wimmViewer.css">
@endpush
@push('custom-scripts')
    <script src="{{ asset('assets/wimmviewer/dist/js') }}/wimmViewer.js"></script>
    <script>
        $(document).ready(function() {
            @if (session('inquiryError') == true)
                $('#inquiry').modal('show');
            @endif

            $('#extra-images').WimmCarousel({
                miniaturePosition: 'bottom',
                miniatureWidth: 100,
                miniatureHeight: 50,
                miniatureSpace: 5,
                viewerMaxHeight: false,
                nextText: 'NEXT <span class="fa fa-caret-right"></span>',
                prevText: '<span class="fa fa-caret-left"></span> PREV',
                onImgChange: function() {},
                onNext: function() {},
                onPrev: function() {}
            });
        });
    </script>
@endpush
