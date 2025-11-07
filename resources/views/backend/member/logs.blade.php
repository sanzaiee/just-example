@extends('backend.master')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">{{ ucfirst($of) }} Logs</h4>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Search</h5> <small class="text-muted float-end"></small>
            </div>
            <form method="get" action="{{route('activity.log',$of)}}">
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="mb-3  m-2">
                            <label class="form-label" for="basic-default-fullname">NMC No.</label>
                            <input type="text" name="nmc_no" value="{{ old('nmc_no',request('nmc_no')) }}" placeholder="enter NMC No." class="form-control">
                        </div>

                        <div class="mb-3  m-2">
                            <label class="form-label" for="basic-default-fullname">Name</label>
                            <input type="text" name="name" value="{{ old('name',request('name')) }}" placeholder="enter name" class="form-control">
                        </div>

                        <div class="mb-3  m-2">
                            <label class="form-label" for="basic-default-fullname">Email</label>
                            <input type="email" name="email" value="{{ old('email',request('email')) }}" placeholder="enter email" class="form-control">
                        </div>

                        <div class="mb-3  m-2">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Search</button>
                            <a href="{{route('activity.log',$of)}}" class="btn btn-info waves-effect waves-light">Clear</a>
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <div class="card">
            <h5 class="card-header">List</h5>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table card-table">
                        <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>NMC Number</th>
                                <th>Name</th>
                                <th>{{ ($of == "email") ? "Email" : "Mobile Number" }}</th>
                                <th>Status</th>
                                <th>View</th>
                                <th>Issued At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ get_member($log->causer_id)?->nmc_no ?? '-' }}</td>
                                    <td>{{ get_member($log->causer_id)?->name ?? '-' }}</td>
                                    <td>{{ ($of =="email") ? get_member($log->causer_id)?->email ?? '-' : get_member($log->causer_id)?->mobile ?? '-' }}</td>
                                    <td>{!! (isset($log->properties['name']) && $log->properties['name'] == "sent") ? '<span class="btn btn-xs btn-success">Success</span>' : '<span class="btn btn-xs btn-danger">Failed</span>' !!}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#categoryEdit{{ $log->id }}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </td>
                                    <td>
                                        On: {{ get_format_date($log->created_at)['date'] }} <br>
                                        Time: {{ get_format_date($log->created_at)['time'] }}
                                    </td>
                                </tr>
                                <!-- Log Description Modal -->
                                <div class="modal fade" id="categoryEdit{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel1">Log Description</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-2">
                                                    <div class="col mb-0">
                                                        <label for="status" class="form-label">Description</label>
                                                        <p>
                                                            {{ $log->description ?? '-' }}
                                                        </p>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <span class="btn btn-label-danger" data-bs-dismiss="modal">Close</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                    <span class="m-3">
                        {{$logs->withQueryString()->links("pagination::bootstrap-5")}}
                    </span>
                </div>

            </div>
        </div>

    </div>
@endsection
