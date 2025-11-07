
@extends('backend.form.list')
@section('table')
    <a href="{{ route('gallery.index') }}" class="m-3"> <i class="fa fa-arrow-circle-left"></i>  <u>Back to {{ ucfirst($records->name) }}</u></a>
    <table class="table card-table">
        <thead>
        <tr>
            <th>S/N</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
            @if($records->images)
                @foreach ($records->images as $index=>$item)
                    {{-- @if($index > 0) --}}
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td><img src="{{ asset($item->getFullUrl()) }}" width="50px" height="50px"></td>
                            <td>
                                <a href="{{ route('delete.gallery.image',$item->id) }}" class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    {{-- @endif --}}
                @endforeach
            @else
                <tr>
                    <td>Please add images...</td>
                </tr>
            @endif

        </tbody>
    </table>

@endsection
