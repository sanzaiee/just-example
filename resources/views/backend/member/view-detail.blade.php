@extends('backend.master')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-3">View History</h4>
    @if($voteToWinner->count() > 0)
        <div class="card mb-3">
            <h5 class="card-header">{{ $member->name }} has selected following candidates</h5>
            <div class="card-body">
                <div class="d-flex justify-content-evenly">
                    <span style="padding: 20px" class="d-flex flex-column bd-highlight mb-3">
                        @foreach ($voteToWinner as $win)
                            @if($win->category?->status == true)
                                <div class="d-flex">
                                    <img src="{{ asset('candidate/'. $win->candidate?->image) }}" class="img-thumbnail m-2"  alt="{{$win->candidate?->name}}" width="50px" height="50px">
                                    <span class="m-2">
                                        <strong>{{$win->category?->name}} :</strong>
                                        <h6>{{$win->candidate?->name}}</h6>
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </span>
                    <span style="padding: 20px" class="d-flex flex-column bd-highlight mb-3">
                        <h5>Details</h5>
                        <p><strong>On Date : </strong> {{get_format_date($voteToWinner[0]->created_at)['date']}}</p>
                        <p><strong>At Time : </strong>{{get_format_date($voteToWinner[0]->created_at)['time']}}</p>
                        <p><strong>By Device: </strong></p>
                        <p><strong>Name : </strong> {{$voteToWinner[0]->device}}</p>
                        <p><strong>Platform : </strong> {{$voteToWinner[0]->platform}}</p>
                        <p><strong>Device Type : </strong> {{$voteToWinner[0]->deviceType}}</p>
                        <p><strong>Browser : </strong> {{$voteToWinner[0]->browser}}</p>
                    </span>
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <h5 class="card-header">List</h5>
        <div class="table-responsive">
            <table class="table card-table">
                <thead>
                    <tr>
                        <th>S.N.</th>
                        <th>Name</th>
                        <th>Detail</th>
                        <th>Viewed At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ballotViews as $member)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$member->member?->name}} </td>
                            <td>
                                <p>
                                    <strong>Ip Address : </strong> {{$member->ip_address ?? ''}} ,
                                    <strong>Device Name : </strong> {{$member->device ?? ''}} ,
                                    <strong>Platform : </strong> {{$member->platform ?? ''}} <Br/>
                                    <strong>Device Type : </strong> {{$member->deviceType ?? ''}} ,
                                    <strong>Browser : </strong> {{$member->browser ?? ''}}
                                </p>
                            </td>
                            <td>
                                On: {{ get_format_date($member->created_at)['date'] }} <br>
                                Time: {{ get_format_date($member->created_at)['time'] }}
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
            {{-- <span class="m-3">
                {{$ballotViews->links("pagination::bootstrap-5")}}
            </span> --}}
        </div>
    </div>

</div>
@endsection
