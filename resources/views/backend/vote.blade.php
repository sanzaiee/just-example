@extends('backend.master')
@section('content')
    @inject('website','App\Helpers\WebsiteHelper')

    <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">VOTES</h4>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Search</h5> <small class="text-muted float-end"></small>
        </div>
        <form method="get" action="{{ route('votes') }}">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="mb-3  m-2">
                        <label class="form-label" for="basic-default-fullname">Category</label>
                        <select name="category" class="form-control">
                            <option value="">-- choose category --</option>
                            @forelse($website->category() as $category)
                                <option value="{{$category->slug}}" @selected(old('category', request('category')) == $category->slug)>{{$category->name}}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>

                    <div class="mb-3  m-2">
                        <label class="form-label" for="basic-default-fullname">Candidate</label>
                        <select name="candidate" class="form-control">
                            <option value="">-- choose candidate --</option>
                            @forelse($website->candidates() as $candidate)
                                <option value="{{$candidate->id}}" @selected(old('candidate', request('candidate')) == $candidate->id)>{{$candidate->name}}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>

                    <div class="mb-3  m-2">
                        <label class="form-label" for="basic-default-fullname">Search</label>
                        <input type="text" class="form-control" name="query" placeholder="enter name" value="{{ request()->input('query') ?? '' }}">
                    </div>

                    <div class="mb-3  m-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Search</button>
                        <a href="{{route('votes')}}" class="btn btn-info waves-effect waves-light">Clear</a>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <div class="card">
        <h5 class="card-header">List</h5>
        <div class="table-responsive table-bordered table-info">
            <table class="table card-table">
                <thead>
                <tr>
                     <th>S.N.</th>
                    <th>Category</th>
                    <th>Candidate</th>
                    <th>Member</th>
                    <th>IP Address</th>
                    <th>Device</th>
                    <th>Created At</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($votes as $vote)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $vote->category?->name ?? '-' }}</td>
                            <td>{{ $vote->candidate?->name ?? '-' }}</td>
                            <td>
                                {{ $vote->member?->name ?? '-' }}
                            </td>
                            <td>{{ $vote->ip_address ?? '-' }}</td>
                            <td>
                                <p>
                                    <strong>Name : </strong> {{$vote->device ?? '-'}} ,
                                    <strong>Platform : </strong> {{$vote->platform ?? '-'}} <Br/>
                                    <strong>Device Type : </strong> {{$vote->deviceType ?? '-'}} ,
                                    <strong>Browser : </strong> {{$vote->browser ?? '-'}}
                                </p>
                            </td>
                            <td>
                                On: {{ get_format_date($vote->created_at)['date'] }} <br>
                                Time: {{ get_format_date($vote->created_at)['time'] }}
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        <span class="m-3">
            {{$votes->withQueryString()->links("pagination::bootstrap-5")}}
        </span>

    </div>

</div>
@endsection
