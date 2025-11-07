@extends('backend.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Product / </span>{{ $product->name }} </h4>

        <div class="card h-90 shadow-sm">
            <div class="card-body d-flex flex-column">
                <div class="row">
                    <div class="col-md-4">
                        <div class="overflow-hidden p-2">
                            <img src="{{ $product->image }}"
                                alt="{{ $product->slug }}"
                                class="w-100 h-100 object-fit-cover">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4 class="card-title">{{ $product->name }}  | {{ $product->code }}</h4>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <p class="fw-bold">By {{ $product->user->name }}</p>

                            <div class="badges">
                                <span class="badge bg-primary">{{ $product->category->name }}</span>
                                <span class="badge bg-primary">{{ $product->brand->name }}</span>
                            </div>
                        </div>

                        <p>
                            {{ $product->description }}
                        </p>

                        <div>
                            <span class="theme-color">Rs. {{ $product->price }}</span>
                            <del class="text-danger">Rs. {{ $product->strike_price }}</del>
                        </div>

                        <hr>

                        <div class="mt-2 d-flex justify-content-between">
                            <livewire:cart-setup :product="$product" :detail="true"/>
                            <div class="badges">
                                <span class="badge {{ $product->home_delivery ? 'bg-success' : 'bg-danger' }}">{{ $product->stock ? 'Delivery' : 'No Delivery' }}</span>
                                <span class="badge {{ $product->stock ? 'bg-success' : 'bg-danger' }}">{{ $product->stock ? 'In Stock' : 'Out of Stock' }}</span>
                            </div>
                        </div>
                        <hr>
                        <p class="border p-2">
                            {{-- <i class="fa fa-eye"></i> &nbsp; --}}
                            <span class="btn btn-primary btn-sm ">{{ $product->view_count }}</span> <span class="text-decoration-underline fw-bold">people have viewed this product. </span>
                        </p>

                    </div>

                    @if($product->relatedProducts->count() > 0)
                        <hr class="m-3">

                        <div class="col-md-12">
                            <h4 class="card-title text-center">Related Products</h4>
                            <div class="card">
                                <div class="row">
                                    @forelse ($product->relatedProducts as $item)
                                        <div class="col-md-3 mb-3">
                                            <div class="card h-100 shadow-sm">
                                                <div class="position-relative overflow-hidden p-2" style="height: 200px;">
                                                    <img src="{{ $item->image }}"
                                                        alt="{{ $item->slug }}"
                                                        class="w-100 h-100 object-fit-cover rounded">
                                                </div>

                                                <div class="card-body text-center d-flex flex-column">
                                                    <h5 class="card-title">{{ $item->name }}</h5>
                                                    <p class="card-text" style="text-align: justify; text-align-last: center;">
                                                        {{ $item->short }}
                                                    </p>

                                                    <!-- Buttons -->
                                                    <div class="mt-auto d-flex justify-content-between gap-2">
                                                        <livewire:cart-setup :product="$item" :detail="false" />
                                                        {{-- @livewire('cart-setup', ['product' => $item], key($item->id)) --}}
                                                        <a href="{{ route('product.show',$item->id) }}" class="btn btn-sm btn-outline-secondary">
                                                            <i class="bi bi-eye"></i> View More
                                                        </a>
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
            {{-- <div class="mt-auto d-flex justify-content-between gap-2">

            </div> --}}



            </div>
        </div>
    {{-- </div>
</div> --}}



    </div>
@endsection
