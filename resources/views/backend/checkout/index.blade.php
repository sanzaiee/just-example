@extends('backend.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- <div class="row g-4">
            <div class="col-md-3"> --}}
        <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Checkout / </span> </h4>

        <div class="card h-90 shadow-sm">
            <div class="card-body">
                <livewire:cart-checkout />


            </div>
        </div>
    {{-- </div>
</div> --}}



    </div>
@endsection
