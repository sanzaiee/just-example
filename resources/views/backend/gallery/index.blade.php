
@extends('backend.form.list')
@section('table')
    <table class="table card-table">
        <thead>
        <tr>
            <th>S/N</th>
            <th>Image</th>
            <th>Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
            @forelse ($records as $index=>$item)
            <tr>
                <td>{{ ++$index }}</td>

                    @if ($item->image)
                        <td><img src="{{ asset($item->image) }}" width="50px" height="50px"></td>
                    @else
                        <td>No image</td>
                    @endif

                <td>{{ $item->name }}</td>

                <td>
                    @if($item->status == 1)
                        <span class="btn btn-xs btn-success waves-effect waves-light">Active</span>
                    @else
                        <span class="btn btn-xs btn-danger waves-effect waves-light">Banned</span>
                    @endif
                </td>

                <td >
                    @include('backend.form.action')

                    <a href="{{ route('gallery.show',$item->id) }}" class="btn btn-rounded btn-sm btn-primary m-1" data-toggle="tooltip" data-original-title="Delete">
                        <i class="fa fa-eye"></i>
                    </a>
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
