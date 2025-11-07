@extends('backend.master')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">CANDIDATE</h4>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#candidateCreation">
        Create
    </button>

  <!-- Modal -->
  <div class="modal fade" id="candidateCreation" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog model-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel1">CANDIDATE</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('candidate.store') }}" id="categoryForm"  method="post" enctype="multipart/form-data">
            <div class="modal-body">
                @csrf
                <div class="row g-2">
                    <div class="col mb-0">
                        <label for="nameBasic" class="form-label">Name</label>
                        <input type="text" id="nameBasic" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter Name" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col mb-0">
                        <label for="post" class="form-label">Post</label>
                        <input type="text" class="form-control" name="post" value="{{ old('post') }}" id="post" />
                        @error('post')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col mb-0">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" id="image" required/>
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col mb-0">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="" class="form-control">--Select Category--</option>
                            @forelse ($categories as $item)
                                <option value="{{ $item->id }}" class="form-control" @selected(old('category_id') == $item->id)> {{ $item->name }}</option>
                            @empty

                            @endforelse
                        </select>

                        @error('category_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="3" name="description" spellcheck="false" data-ms-editor="true" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

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
            <form action="{{ route('candidate.index') }}" method="get">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="query">Search</label>
                            <input type="search" class="form-control" id="query" name="query" value="{{ request()->input('query') ?? '' }}" placeholder="name, post, category ...">
                        </div>
                    </div>
                    <div class="col-md-6 mt-4">
                        <button class="btn btn-info btn-m" type="submit"><i class="fa fa-search"></i></button>
                        <a href="{{ route('candidate.index') }}" class="btn btn-danger btn-m"> <i class="fa fa-refresh"></i> </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table card-table">
                    <thead>
                        <tr>
                            <th>S.N.</th>
                            <th></th>
                            <th>Name</th>
                            <th>Post</th>
                            <th>Category</th>
                            <th>Status</th>
                            @if(auth()->user()->role == App\Models\User::ROLE_SUPERADMIN)
                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($candidates as $candidate)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ asset('candidate/'.$candidate->image) }}" alt="{{ $candidate->name }}" width="50px" height="50px">
                                </td>
                                <td>{{ $candidate->name }}</td>
                                <td>
                                    {{ $candidate->post }}
                                </td>
                                <td>
                                    {{ $candidate->category?->name }}
                                </td>

                                <td>
                                    @if($candidate->status == 1)
                                        <span class="badge bg-label-success me-1">Active</span>
                                    @else
                                        <span class="badge bg-label-danger me-1">In Active</span>
                                    @endif
                                </td>
                                @if(auth()->user()->role == App\Models\User::ROLE_SUPERADMIN)

                                <td>
                                    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#categoryEdit{{ $candidate->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <button type="button" class="btn btn-danger mb-4" onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('delete-form-{{ $candidate->id }}').submit();">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $candidate->id }}" action="{{route('candidate.destroy',$candidate->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @if(auth()->user()->role == App\Models\User::ROLE_SUPERADMIN)
                                <!-- Edit Category Modal -->
                                <div class="modal fade" id="categoryEdit{{ $candidate->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">CANDIDATE UPDATE</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('candidate.update',$candidate->id) }}" id="categoryForm{{ $candidate->id }}" method="POST" enctype="multipart/form-data">
                                                @method('PUT')
                                                @csrf

                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <img src="{{ asset('candidate/'.$candidate->image) }}" alt="" width="100px" height="100px">
                                                    </div>
                                                </div>

                                                <div class="row g-2">
                                                    <div class="col mb-0">
                                                        <label for="nameBasic" class="form-label">Name</label>
                                                        <input type="text" id="nameBasic" name="name" class="form-control"  value = "{{ old('name',$candidate->name ?? '') }}">

                                                        @error('name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col mb-0">
                                                        <label for="post" class="form-label">Post</label>
                                                        <input type="text" class="form-control" name="post" value = "{{  old('post',$candidate->post ?? '') }}" id="post" />
                                                        @error('post')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row g-2">
                                                    <div class="col mb-0">
                                                        <label for="image" class="form-label">Image</label>
                                                        <input type="file" class="form-control" name="image" id="image"/>

                                                        @error('image')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col mb-0">
                                                        <label for="category_id" class="form-label">Category</label>
                                                        <select name="category_id" id="category_id" class="form-control">
                                                            <option value="" class="form-control">--Select Category--</option>
                                                            @forelse ($categories as $item)
                                                                <option value="{{ $item->id }}" class="form-control" @selected($candidate->category_id == $item->id)> {{ $item->name }}</option>
                                                            @empty

                                                            @endforelse
                                                        </select>
                                                        @error('category_id')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="row g-2">
                                                        <div class="col mb-0">
                                                            <label for="status" class="form-label">Status</label>
                                                            <select name="status" class="form-control" id="status">
                                                                <option value="1" @selected($candidate->status == 1)>Active</option>
                                                                <option value="0" @selected($candidate->status == 0)>InActive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row g-2">
                                                        <div class="col mb-3">
                                                            <label for="description" class="form-label">Description</label>
                                                            <textarea class="form-control" id="description" rows="3" name="description" spellcheck="false" data-ms-editor="true">{{  old('name',$candidate->description ?? '') }}</textarea>
                                                            @error('description')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="modal-footer">
                                                    <span class="btn btn-label-secondary" data-bs-dismiss="modal">Close</span>
                                                    <button type="submit" class="btn btn-primary" id="submitButton{{ $candidate->id }}">Save</button>
                                                    <div id="loader{{ $candidate->id }}" style="display:none;">Loading...</div>

                                                </div>
                                            </form>

                                            @push('custom-scripts')
                                                <script>
                                                    $(document).ready(function() {
                                                        $('#categoryForm{{ $candidate->id }}').submit(function(event) {
                                                            var submitButton = $('#submitButton{{ $candidate->id }}');
                                                            var loader = $('#loader{{ $candidate->id }}');

                                                            submitButton.prop('disabled', true);
                                                            loader.show();
                                                        });
                                                    });
                                                </script>
                                            @endpush
                                    </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                        @endforelse
                    </tbody>
                </table>
                <span class="m-3">
                    {{$candidates->withQueryString()->links("pagination::bootstrap-5")}}
                </span>
            </div>
        </div>
    </div>

</div>
@endsection
@push('custom-scripts')
<script>
    $(document).ready(function() {
        @if (session('createError') == true)
            $('#candidateCreation').modal('show');
        @endif

        @if (session('updateError') == true && session('candidate_id'))
            $('#categoryEdit{{ session('candidate_id') }}').modal('show');
        @endif

        $('#categoryForm').submit(function() {
            $('#submitButton').prop('disabled', true);
            $('#loader').show();
        });
    });
</script>

@endpush
