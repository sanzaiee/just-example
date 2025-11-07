
@extends('backend.form.list')
@section('table')
    <table class="table card-table">
        <thead>
        <tr>
            <th>S/N</th>
            {{-- <th>Image</th> --}}
            <th>Title</th>
            <th>Author</th>
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
                                    <img src="{{ $item->image }}" alt="{{ $item->title }}"
                                         class="rounded-circle">
                                @else
                                    <span class="avatar-initial rounded-circle bg-label-{{ getAvatarColor($item->title) }}">{{ getAvatarName($item->title) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fw-medium">{{ str($item->title)->limit(30) }}</span>
                        </div>
                    </div>
                </td>


                {{--

                    @if ($item->image)
                        <td><img src="{{ asset($item->image) }}" width="50px" height="50px"></td>
                    @else
                        <td>No image</td>
                    @endif

                <td>{{ $item->title }}</td> --}}
                <td>{{ $item->author ?? '-' }}</td>

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
                    @include('backend.form.action')

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

