@extends('backend.master')
@section('content')
@inject('menu','App\Helpers\MenuHelper')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Menu</h5>
            <small class="text-muted float-end">
                <a href="{{ route('submenu.index') }}" class="btn btn-primary">
                    Sub Menu
                </a>
            </small>
        </div>
        @livewire('menu', ['menus' => $menus,'submenu'=>$submenu])
    </div>
</div>

@endsection
