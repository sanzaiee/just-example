@extends('backend.master')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4">
    <span class="text-muted fw-light">{{ $modelName }}/</span> List</h4>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">List</h5>
            <small class="text-muted float-end">
                <a href="{{ route($routeCreate,$params ?? '') }}" class="btn btn-primary">
                    Create
                </a>
            </small>
          </div>

        <div class="card-body">
            @if(!(isset($search) && $search == false))
                @include('backend.form.search',['route'=> $routeList])
            @endif

            <div class="table-responsive">
                @yield('table')

                @if(!(isset($pagination) && $pagination == false))
                    <span class="m-3">
                        {{$records->withQueryString()->links("pagination::bootstrap-5")}}
                    </span>
                @endif

            </div>
        </div>
    </div>

</div>
@endsection
@push('custom-scripts')


@endpush
