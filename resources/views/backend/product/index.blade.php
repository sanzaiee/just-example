
@extends('backend.form.list')
@section('table')
    <table class="table card-table">
        <thead>
        <tr>
            <th>S/N</th>
            {{-- <th>Image</th> --}}
            <th>Title</th>
            {{-- <th>Created By</th> --}}
            <th>Category</th>
            <th>Brand</th>
            <th>Type</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
            @forelse ($records as $index=>$item)
            <tr>
                <td>{{ ++$index }}</td>
                <td>
                    <div class="d-flex justify-content-start align-items-center user-name">
                        <div class="avatar-wrapper">
                            <div class="avatar me-3">
                                @if($item->image)
                                    <img src="{{ $item->image }}" alt="{{ $item->name }}"
                                         class="rounded-circle">
                                @else
                                    <span class="avatar-initial rounded-circle bg-label-{{ getAvatarColor($item->name) }}">{{ getAvatarName($item->name) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fw-medium">{{ str($item->name)->limit(30) }}</span>
                        </div>
                    </div>
                </td>


                {{-- <td>{{ $item->user->name ?? '-' }}</td> --}}
                <td>{{ $item->category->name ?? '-' }}</td>
                <td>{{ $item->brand->name ?? '-' }}</td>
                <td>{{ $item->tag->name ?? '-' }}</td>

                {{-- <td>
                    <a href="{{ route('blog.comment',$item->slug) }}" target="_blank">
                        <span class="badge badge-info m-r-5 m-b-5">Comment List</span>
                    </a>
                </td> --}}

                <td>
                    @if($item->status == 1)
                        <span class="btn btn-xs btn-success waves-effect waves-light">Active</span>
                    @else
                        <span class="btn btn-xs btn-danger waves-effect waves-light">Banned</span>
                    @endif
                </td>

                <td>
                    <a href="{{ route($routeEdit,$item->slug) }}" class="btn btn-rounded btn-sm btn-info m-r-5" data-toggle="tooltip" data-original-title="Edit">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="" class="btn btn-rounded btn-sm btn-danger m-r-5" data-toggle="tooltip"
                    data-original-title="Delete"
                    onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('delete-form-{{ $item->id }}').submit();">
                    <i class="fa fa-trash"></i>
                    </a>
                    <form id="delete-form-{{ $item->id }}" action="{{route($routeDelete,$item->slug)}}" method="post">
                    @csrf
                    @method('delete')
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td>Please add some content...</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection

