@extends('backend.form.list')
@section('table')
    <table class="table card-table">
        <thead>
        <tr>
            <th>S/N</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
            @forelse ($records as $index=>$item)
            <tr>
                <td>{{ ++$index }}</td>
                <td>{!! $item->question !!}</td>
                <td>{!! $item->answer !!}</td>

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
