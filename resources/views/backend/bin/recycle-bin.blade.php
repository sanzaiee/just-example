@extends('backend.master')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">Recently deleted records of {{ ucfirst($parameter) }}</h4>

    <div class="card">
        <div class="card-body">
            <a href="{{ route('recycleBin') }}" style="float: right">
                <button class="btn btn-primary"> Back</button>
            </a>
            <h5 class="card-title">List</h5>

            <div class="table-responsive">
                <table class="table card-table">
                    <thead>
                        <tr>
                            <th>S.N.</th>

                            @switch($parameter)
                                @case('category')
                                    <th>Name</th>
                                @break

                                @case('candidate')
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Post</th>
                                    <th>Category</th>
                                @break

                                @case('member')
                                    <th>NMC_NO.</th>
                                    <th>Name</th>
                                    <th>Mobie</th>
                                    <th>Email</th>
                                    <th>Viewed Count</th>
                                @break

                            {{-- @case('votes')
                                    <th>Category</th>
                                    <th>item</th>
                                    <th>Member</th>
                                    <th>IP Address</th>
                                    <th>Device</th>
                                    <th>Created At</th>
                                @break --}}
                            @default

                            @endswitch

                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                @switch($parameter)
                                    @case('category')
                                        <td>{{ $item->name }}</td>
                                    @break

                                    @case('candidate')
                                        <td>
                                            <img src="{{ asset('candidate/'.$item->image) }}" alt="{{ $item->name }}" width="50px" height="50px">
                                        </td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            {{ $item->post }}
                                        </td>
                                        <td>
                                            {{ $item->category?->name ?? '-' }}
                                        </td>
                                    @break

                                    @case('member')
                                        <td>{{ $item->nmc_no }}</td>
                                        <td>{{ $item->name }}
                                            @if(has_voted($item->id))
                                                    <span class="btn btn-xs btn-success">Voted</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->mobile ?? '-' }}
                                        </td>

                                        <td>{{ $item->email ?? '-' }}</td>
                                        @if(auth()->user()->role == App\Models\User::ROLE_SUPERADMIN)
                                            <td>
                                                <a href="{{ route('ballot.view.detail',$item->id) }}" tooltip = "View Detail">

                                                    <button type="button" class="btn btn-primary btn-m mb-4" data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail">

                                                        {{ $item->ballotViewCount->count() ?? 0 }}
                                                    </button>
                                                    <span class="badge bg-info rounded-pill badge-notifications">
                                                        VIEW
                                                    </span>

                                                </a>
                                            </td>
                                        @endif
                                    @break

                                    {{-- @case('votes')
                                            <td>{{ $item->category->name }}</td>
                                            <td>{{ $item->item->name }}</td>
                                            <td>
                                                {{ $item->member->name }}
                                            </td>
                                            <td>{{ $item->ip_address }}</td>
                                            <td>
                                                <p>
                                                    <strong>Name : </strong> {{$item->device}} ,
                                                    <strong>Platform : </strong> {{$item->platform}} <Br/>
                                                    <strong>Device Type : </strong> {{$item->deviceType}} ,
                                                    <strong>Browser : </strong> {{$item->browser}}
                                                </p>
                                            </td>
                                            <td>
                                                On: {{ get_format_date($item->created_at)['date'] }} <br>
                                                Time: {{ get_format_date($item->created_at)['time'] }}
                                            </td>
                                        @break --}}
                                @default

                                @endswitch

                                <td>
                                    @if($item->status == 1)
                                        <span class="badge bg-label-success me-1">Active</span>
                                    @else
                                        <span class="badge bg-label-danger me-1">In Active</span>
                                    @endif
                                </td>

                                @if(auth()->user()->role == App\Models\User::ROLE_SUPERADMIN)
                                    <td>
                                        <button type="button" class="btn btn-danger mb-4" onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('restore-form-{{ $item->id }}').submit();">
                                            <i class="fa fa-trash-restore-alt"></i>
                                        </button>
                                        <form id="restore-form-{{ $item->id }}" action="{{route('restore',['id'=>$item->id,'parameter' => $parameter])}}" method="post">
                                            @csrf
                                            @method('put')
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
                <span class="m-3">
                    {{$data->withQueryString()->links("pagination::bootstrap-5")}}
                </span>
            </div>

        </div>
    </div>

</div>
@endsection
