@extends('backend.master')
@section('content')
@inject('menu','App\Helpers\MenuHelper')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Sub Menu</h5>
            <small class="text-muted float-end">
                <a href="{{ route('menu.index') }}" class="btn btn-primary">
                    Menu
                </a>
            </small>
        </div>
        @livewire('sub-menu', ['submenu' => $submenu,'categories' => $categories,'submenu_all'=>$submenu_all])
    </div>
</div>


@endsection
