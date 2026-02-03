@extends('backend.form.list')

@section('table')
    <table class="table card-table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Type</th>
                <th>Address</th>
                <th>City</th>
                <th>Postal Code</th>
                <th>Active</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $index => $item)
                <tr>
                    <td>{{ ++$index }}</td>
                    <td>{{ $item->type }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($item->address, 40) }}</td>
                    <td>{{ $item->city }}</td>
                    <td>{{ $item->postal_code }}</td>
                    <td>
                        @if ($item->active)
                            <span class="btn btn-xs btn-success waves-effect waves-light">Active</span>
                        @else
                            <form action="{{ route('shipping-address.set-active', $item->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-xs btn-outline-success waves-effect waves-light">Set
                                    Active</button>
                            </form>
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
