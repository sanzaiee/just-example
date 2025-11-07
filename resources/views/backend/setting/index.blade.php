@extends('backend.master')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">SETTINGS</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{route('site.update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col mb-3">
                            <label for="nameBasic" class="form-label">Name</label>
                            <input type="text" id="nameBasic" name="site_name" class="form-control" value="{{ $siteSetting->site_name ?? '' }}" placeholder="Enter Name">
                            @error('site_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="flatpickr-datetime-start" class="form-label">Vote Start At</label>
                                <input type="text" class="form-control" name="vote_start" value="{{$siteSetting->vote_start ?? ''}}" placeholder="YYYY-MM-DD HH:MM" id="flatpickr-datetime-start" required/>
                                @error('vote_start')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col mb-0">
                                <label for="flatpickr-datetime-end" class="form-label">Vote End At</label>
                                <input type="text" class="form-control" name="vote_end" value="{{$siteSetting->vote_end ?? ''}}" placeholder="YYYY-MM-DD HH:MM" id="flatpickr-datetime-end" required/>
                                @error('vote_end')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col mb-3 mt-3">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
@push('custom-scripts')
    <script>
        function setupFlatpickr(element) {
            flatpickr(element, {
                enableTime: true,
                dateFormat: "Y-m-d H:i"
            });
        }

        setupFlatpickr("#flatpickr-datetime-start");
        setupFlatpickr("#flatpickr-datetime-end");
    </script>

@endpush
