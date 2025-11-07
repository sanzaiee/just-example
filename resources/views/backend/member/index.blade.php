@extends('backend.master')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">MEMBER</h4>

    <div class="card mb-4">
        <div class="card-body row">
            <div class="col-md-4">
                <h5 class="card-title">Total Members</h5>
                <p class="card-text">{{ $votedMembers + $nonVotedMembers }}</p>

            </div>

            <div class="col-md-4">
                <h5 class="card-title">Voted Members</h5>
                <p class="card-text">{{ $votedMembers }}</p>

            </div>
            <div class="col-md-4">
                <h5 class="card-title">Non Voted Members</h5>
                <p class="card-text">{{ $nonVotedMembers }}</p>
            </div>
        </div>
    </div>


    <div class="card mb-4">
        <div class="card-body row">
            <div class="col-md-6">
                <h5 class="card-title">Bulk Email</h5>
                <p class="card-text">To send an email to every active member, click the button below.</p>
                <form action="{{ route('bulk.email') }}" id="bulk-email" method="post">
                    @csrf
                    <button class="btn btn-info" type="submit">Send Email</button>
                </form>
            </div>
            <div class="col-md-6">
                <h5 class="card-title">Bulk SMS</h5>
                <p class="card-text">To send an sms to every active member, click the button below.</p>
                <form action="{{ route('bulk.sms') }}" id="bulk-sms" method="post">
                    @csrf
                    <button class="btn btn-info" type="submit">Send SMS</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="categoryCreation" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel1">CREATE MEMBER</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <form action="{{ route('member.store') }}" id="categoryForm" method="post" enctype="multipart/form-data">
                    @csrf
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="nmc_no" class="form-label">NMC Number</label>
                                <input type="text" class="form-control" name="nmc_no" placeholder="Enter NMC Number" id="nmc_no" value="{{ old('nmc_no') }}" required/>
                                @error('nmc_no')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col mb-0">
                                <label for="nameBasic" class="form-label">Name</label>
                                <input type="text" id="nameBasic" name="name" class="form-control" placeholder="Enter Name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="mobile" class="form-label">Mobile</label>
                                <input type="number" class="form-control" name="mobile" placeholder="Enter Mobile Number" value="{{ old('mobile') }}" id="mobile" />
                                @error('mobile')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col mb-0">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Email Address" value="{{ old('email') }}" id="email" />
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <span class="btn btn-label-secondary" data-bs-dismiss="modal">Close</span>
                        <button type="submit" class="btn btn-primary" id="submitButton">Save</button>
                        <div id="loader" style="display:none;">Loading...</div>
                    </div>
                </form>
        </div>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            List
            <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#categoryCreation">
                Create
            </button>
        </h5>

        <div class="card-body">
            <form action="{{ route('member.index') }}" method="get">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="query">Search</label>
                            <input type="search" class="form-control" id="query" name="query" value="{{ request()->input('query') ?? '' }}" placeholder="name or mobile or email">
                        </div>
                    </div>
                    <div class="col-md-6 mt-4">
                        <button class="btn btn-info btn-m" type="submit"><i class="fa fa-search"></i></button>
                        <a href="{{ route('member.index') }}" class="btn btn-danger btn-m"> <i class="fa fa-refresh"></i> </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table id="example" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.N.</th>
                            <th>NMC_NO.</th>
                            <th>Name</th>
                            <th>Mobie</th>
                            <th>Email</th>
                            @if(auth()->user()->role == App\Models\User::ROLE_SUPERADMIN)
                                <th>Viewed Count</th>
                            @endif
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($members as $member)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $member->nmc_no }}</td>
                                <td>{{ $member->name }}
                                @if(has_voted($member->id))
                                        <span class="btn btn-xs btn-success">Voted</span>
                                @endif
                                </td>
                                <td>
                                    {{ $member->mobile ?? '-' }}
                                </td>

                                <td>{{ $member->email ?? '-' }}</td>
                                @if(auth()->user()->role == App\Models\User::ROLE_SUPERADMIN)
                                    <td>
                                        <a href="{{ route('ballot.view.detail',$member->id) }}" tooltip = "View Detail">

                                            <button type="button" class="btn btn-primary btn-m mb-4" data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail">

                                                {{ $member->ballotViewCount->count() ?? 0 }}
                                            </button>
                                            <span class="badge bg-info rounded-pill badge-notifications">
                                                VIEW
                                            </span>

                                        </a>
                                    </td>
                                @endif

                                <td>@if($member->status == 1)
                                    <span class="badge bg-label-success me-1">Active</span>
                                    @else
                                    <span class="badge bg-label-danger me-1">In Active</span>
                                    @endif</td>
                                <td>

                                    <a href="{{ route('send.mail', $member) }}" id="emailAnchor{{ $member->id }}" tooltip="Send Email">
                                        <button type="button" class="btn btn-info mb-4" id="sendEmailLink{{ $member->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Send Email"
                                            >
                                            <i class="fa fa-envelope"></i>
                                        </button>
                                    </a>

                                    <a href="{{ route('send.sms',$member) }}" id="smsAnchor{{ $member->id }}" tooltip = "Send SMS">
                                        <button type="button" class="btn btn-warning mb-4" id="sendSmsLink{{ $member->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Send SMS">
                                            <i class="fa fa-sms"></i>
                                        </button>
                                    </a>

                                    @push('custom-scripts')
                                        <script>
                                            $(document).ready(function() {
                                                $('#sendEmailLink{{ $member->id }}').on('click', function() {
                                                    $('#sendEmailLink{{ $member->id }}').prop("disabled", "disabled");
                                                    $('#emailAnchor{{ $member->id }}').attr("disabled","disabled");

                                                });

                                                $('#sendSmsLink{{ $member->id }}').on('click', function() {
                                                    $('#sendSmsLink{{ $member->id }}').prop("disabled", "disabled");
                                                    $('#smsAnchor{{ $member->id }}').attr("disabled","disabled");

                                                });
                                            });
                                        </script>
                                    @endpush

                                    @if(auth()->user()->role == App\Models\User::ROLE_SUPERADMIN)
                                        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#categoryEdit{{ $member->id }}">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <button type="button" class="btn btn-danger mb-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" onclick="event.preventDefault(); if(confirm('Are You Sure ?')) document.getElementById('delete-form-{{ $member->id }}').submit();">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $member->id }}" action="{{route('member.destroy',$member->id)}}" method="post">
                                            @csrf
                                            @method('delete')
                                        </form>
                                    @endif
                                </td>
                            </tr>


                            {{-- @push('custom-scripts')
                                <script>
                                        @if ($errors->any())
                                            $(document).ready(function() {
                                                $('#categoryEdit'.{{ $member->id }}).modal('show');
                                            });
                                        @endif
                                </script>
                            @endpush --}}
                            @if(auth()->user()->role == App\Models\User::ROLE_SUPERADMIN)
                                <!-- Edit Member Modal -->
                                <div class="modal fade" id="categoryEdit{{ $member->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">MEMBER UPDATE</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('member.update',$member->id) }}" id="categoryForm{{ $member->id }}" method="POST" enctype="multipart/form-data">
                                                @method('PUT')
                                            @csrf

                                                <div class="row g-2">
                                                    <div class="col mb-0">
                                                        <label for="nmc_no" class="form-label">NMC Number</label>
                                                        <input type="text" class="form-control" name="nmc_no" value = "{{ $member->nmc_no ?? '' }}" id="nmc_no" />


                                                        @error('nmc_no')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col mb-0">
                                                        <label for="nameBasic" class="form-label">Name</label>
                                                        <input type="text" id="nameBasic" name="name" class="form-control"  value = "{{ $member->name }}">

                                                        @error('name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                </div>
                                                <div class="row g-2">
                                                    <div class="col mb-0">
                                                        <label for="mobile" class="form-label">Mobile</label>
                                                        <input type="text" class="form-control" name="mobile" value = "{{ $member->mobile ?? '' }}" id="mobile" />


                                                        @error('mobile')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col mb-0">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="text" class="form-control" name="email" value = "{{ $member->email ?? '' }}" id="email" />


                                                        @error('email')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="row g-2">
                                                        <div class="col mb-0">
                                                            <label for="status" class="form-label">Status</label>
                                                            <select name="status" class="form-control" id="status">
                                                                <option value="1" @selected($member->status == 1)>Active</option>
                                                                <option value="0" @selected($member->status == 0)>InActive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="modal-footer">
                                                    <span class="btn btn-label-secondary" data-bs-dismiss="modal">Close</span>
                                                    <button type="submit" class="btn btn-primary" id="submitButton{{ $member->id }}">Save</button>
                                                    <div id="loader{{ $member->id }}" style="display:none;">Loading...</div>
                                                </div>
                                            </form>


                                            @push('custom-scripts')
                                                <script>
                                                    $(document).ready(function() {
                                                        $('#categoryForm{{ $member->id }}').submit(function(event) {
                                                            var submitButton = $('#submitButton{{ $member->id }}');
                                                            var loader = $('#loader{{ $member->id }}');

                                                            submitButton.prop('disabled', true);
                                                            loader.show();
                                                        });
                                                    });
                                                </script>


                                                    {{-- <script>
                                                        function disableButton(id) {
                                                            // Disable the button
                                                            // document.getElementById('sendEmailLink').onclick = function (event) {
                                                            //     event.preventDefault();
                                                            // };
                                                            document.getElementById('sendEmailLink{{ $member->id }}').onclick = null;
                                                            document.querySelector('#sendEmailLink{{ $member->id }} button').classList.add('disabled');
                                                        }
                                                    </script> --}}
                                            @endpush
                                    </div>
                                    </div>
                                </div>
                            @endif

                        @empty

                        @endforelse


                    </tbody>
                </table>
                <span class="m-3">
                    {{$members->withQueryString()->links("pagination::bootstrap-5")}}
                </span>
            </div>
        </div>

    </div>

</div>

@endsection

@push('custom-scripts')
    <script>
        $(document).ready(function() {
            @if (session('createError') == true)
                $('#categoryCreation').modal('show');
            @endif

            @if (session('updateError') == true && session('member_id'))
                $('#categoryEdit{{ session('member_id') }}').modal('show');
            @endif

            $('#categoryForm').submit(function() {
                $('#submitButton').prop('disabled', true);
                $('#loader').show();
            });

        });
    </script>


    <script>
        $(document).ready(function() {
            var formSubmitted = false;
            var formSubmittedSms = false;

            $('#bulk-email').submit(function() {
                if (formSubmitted) {
                    alert('Email sending. Please wait.');
                    return false; // Prevent form submission
                }

                formSubmitted = true;
                return true; // Submit the form
            });

            $('#bulk-sms').submit(function() {
                if (formSubmittedSms) {
                    alert('Sms sending. Please wait.');
                    return false; // Prevent form submission
                }

                formSubmittedSms = true;
                return true; // Submit the form
            });
        });
    </script>
@endpush
