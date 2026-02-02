@extends('backend.blank')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <span class="app-brand-text demo text-body fw-bold ms-1">{{ config('app.name') }}</span>
                        </div>
                        <!-- /Logo -->
                        <p class="mb-4">Please fill in the following details to create your account. </p>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">First Name</label>
                                <input type="text" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                    placeholder="Enter your first name" autofocus />
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="lname" class="form-label">Last Name</label>
                                <input type="text" value="{{ old('lname') }}"
                                    class="form-control @error('lname') is-invalid @enderror" id="lname" name="lname"
                                    placeholder="Enter your last name" autofocus />
                                @error('lname')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mobile" class="form-label">Phone Number</label>
                                <input type="text" value="{{ old('mobile') }}"
                                    class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile"
                                    placeholder="+12345678901" autofocus />
                                @error('mobile')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="text" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                    placeholder="Enter your email" autofocus />
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Enter your password" autofocus />
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class=" mb-3">
                                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div> --}}

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                            <br>
                            <div class="d-flex" style="float: right;">
                                <a href="{{ route('login') }}" class="text-primary text-decoration-none">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
