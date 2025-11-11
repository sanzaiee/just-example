@extends('backend.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Welcome</h4>

        <div class="row g-4">
            @foreach ($products as $item)
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative overflow-hidden p-2" style="height: 200px;">
                            <img src="{{ $item->image }}"
                                alt="{{ $item->slug }}"
                                class="w-100 h-100 object-fit-cover rounded">
                        </div>

                        <div class="card-body text-center d-flex flex-column">
                            <a href="{{ route('product.show',$item->id) }}"><h5 class="card-title">{{ $item->name }}</h5>
                                <div>
                                    <span class="theme-color">$ {{ $item->price }}</span> &nbsp;
                                    @if($item->strike_price > 0 && $item->strike_price != $item->price)
                                        <del class="text-danger">$ {{ $item->strike_price }}</del>
                                    @endif
                                </div>
                            </a>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="badge {{ $item->stock ? 'bg-success' : 'bg-danger' }}">{{ $item->stock ? 'In Stock' : 'Out of Stock' }}</span>
                                <livewire:cart-setup :product="$item" :detail="false"/>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>




    </div>
@endsection
