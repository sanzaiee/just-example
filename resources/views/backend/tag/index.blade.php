@extends('backend.master')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">TAG</h4>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#categoryCreation">
        Create  {{ session('createSuccess') }}
    </button>


  <!-- Modal -->
  <div class="modal fade" id="categoryCreation" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel1">TAG</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('tag.store') }}" id="categoryForm" method="post">
            @csrf
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label">Name</label>
                        <input type="text" id="nameBasic" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter Name">

                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- <div class="row g-2">
                    <div class="col mb-0">
                    <label for="flatpickr-datetime-start" class="form-label">Vote Start At</label>
                    <input type="text" class="form-control" name="vote_start" placeholder="YYYY-MM-DD HH:MM" id="flatpickr-datetime-start" required/>
                </div>
                    <div class="col mb-0">
                    <label for="flatpickr-datetime-end" class="form-label">Vote End At</label>
                    <input type="text" class="form-control" name="vote_end" placeholder="YYYY-MM-DD HH:MM" id="flatpickr-datetime-end" required/>
                    </div>
                </div> --}}

                </div>
                <div class="modal-footer">
                    <span class="btn btn-label-secondary" data-bs-dismiss="modal">Close</span>
                    <button type="submit" class="btn btn-primary" id="submitButton">Save</button>
                    <div id="loader" style="display:none;">Loading...</div>
                </div>
            </form>
      </div>
    </div>
  </div>

    <div class="card">
        <h5 class="card-header">List</h5>

        <div class="card-body">
            <form action="{{ route('tag.index') }}" method="get">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="query">Search</label>
                            <input type="search" class="form-control" id="query" name="query" value="{{ request()->input('query') ?? '' }}" placeholder="enter name">
                        </div>
                    </div>
                    <div class="col-md-6 mt-4">
                        <button class="btn btn-info btn-m" type="submit"><i class="fa fa-search"></i></button>
                        <a href="{{ route('tag.index') }}" class="btn btn-danger btn-m"> <i class="fa fa-refresh"></i> </a>
                    </div>
                </div>
            </form>


            <div class="table-responsive">
            <table class="table card-table">
                <thead>
                <tr>
                    <th>S.N.</th>
                    <th>Name</th>

                    {{-- @if(auth()->user()->role == App\Models\User::ROLE_SUPERADMIN) --}}
                        <th>Actions</th>
                    {{-- @endif --}}
                </tr>
                </thead>
                <tbody>
                    @forelse ($tags as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>


                            {{-- @if(auth()->user()->role == App\Models\User::ROLE_SUPERADMIN) --}}
                                <td>
                                    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#categoryEdit{{ $category->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <button type="button" class="btn btn-danger mb-4" onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('delete-form-{{ $category->id }}').submit();">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $category->id }}" action="{{route('tag.destroy',$category->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </td>
                            {{-- @endif --}}
                        </tr>

                        {{-- @if(auth()->user()->role == App\Models\User::ROLE_SUPERADMIN) --}}
                            <!-- Edit Category Modal -->
                            <div class="modal fade" id="categoryEdit{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel1">CATEGORY UPDATE</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                        <form action="{{ route('tag.update',$category->id) }}" id="categoryForm{{ $category->id }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="nameBasic" class="form-label">Name</label>
                                                        <input type="text" id="nameBasic" name="name" class="form-control" value="{{ old('name',$category->name) }}" placeholder="Enter Name">
                                                    </div>

                                                    @error('name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>


                                            <div class="modal-footer">
                                                <span class="btn btn-label-secondary" data-bs-dismiss="modal">Close</span>
                                                <button type="submit" class="btn btn-primary" id="submitButton{{ $category->id }}">Save</button>
                                                <div id="loader{{ $category->id }}" style="display:none;">Loading...</div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        {{-- @endif --}}
                        @push('custom-scripts')
                            <script>
                                $(document).ready(function() {
                                    $('#categoryForm{{ $category->id }}').submit(function(event) {
                                        var submitButton = $('#submitButton{{ $category->id }}');
                                        var loader = $('#loader{{ $category->id }}');

                                        submitButton.prop('disabled', true);
                                        loader.show();
                                    });
                                });
                            </script>

                        {{-- <script>
                            var flatpickrDateTimeStart{{ $category->id }} = document.querySelector("#flatpickr-datetime-start-{{ $category->id }}");
                            var flatpickrDateTimeEnd{{ $category->id }} = document.querySelector("#flatpickr-datetime-end-{{ $category->id }}");

                            flatpickrDateTimeStart{{ $category->id }}.flatpickr({
                                enableTime: true,
                                dateFormat: "Y-m-d H:i"
                            });
                            flatpickrDateTimeEnd{{ $category->id }}.flatpickr({
                                enableTime: true,
                                dateFormat: "Y-m-d H:i"
                            });
                        </script> --}}
                        @endpush



                    @empty

                    @endforelse


                </tbody>
            </table>
                <span class="m-3">
                    {{$tags->withQueryString()->links("pagination::bootstrap-5")}}
                </span>

            </div>
      </div>
    </div>

</div>
@endsection
@push('custom-scripts')
    {{-- <script>
        var flatpickrDateTimeStart = document.querySelector("#flatpickr-datetime-start");
        var flatpickrDateTimeEnd = document.querySelector("#flatpickr-datetime-end");

        flatpickrDateTimeStart.flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i"
        });
        flatpickrDateTimeEnd.flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i"
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            @if (session('createError') == true)
                $('#categoryCreation').modal('show');
            @endif

            @if (session('updateError') == true && session('category_id'))
                $('#categoryEdit{{ session('category_id') }}').modal('show');
            @endif

            $('#categoryForm').submit(function() {
                $('#submitButton').prop('disabled', true);
                $('#loader').show();
            });
        });
    </script>

@endpush
