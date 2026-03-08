@extends('backend.blank')

@section('styles')
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" /> --}}
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
                        <p class="mb-4">Verify Your Email Address</p>

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('verify.otp.post', $user->email) }}">
                            @csrf
                            <div class="mb-3">
                                <label for="otp" class="form-label">Code</label>
                                <input type="text" class="form-control" id="otp" name="otp" required>
                            </div>
                            <button type="submit" class="btn btn-primary"
                                onclick="this.disabled=true; this.form.submit();"
                            >Verify</button>
                        </form>
                        <br>    
                        <div class="d-flex" style="float: right;">
                            <a href="{{ route('login') }}" class="text-primary text-decoration-none">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
