@extends('backend.master')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">Recently Deleted</h4>
    <div class="row mx-0 gy-3 px-lg-5">
        <div class="col-lg mb-md-0 mb-4">
          <div class="card border rounded shadow-none">
            <div class="card-body">

              <h3 class="card-title text-center text-capitalize mb-1">Blogs</h3>
              <p class="text-center">view to restore records</p>
              <div class="text-center">
                <div class="d-flex justify-content-center">
                  <h1 class="display-4 mb-0 text-primary">{{ $blogs ?? 0 }}</h1>
                  <sub class="h6 pricing-duration mt-auto mb-2 text-muted fw-normal">records</sub>
                </div>
              </div>

              <a href="{{ route('recycle.bin','blog') }}" class="btn btn-label-primary d-grid w-100 waves-effect">View</a>
            </div>
          </div>
        </div>

        <div class="col-lg mb-md-0 mb-4">
            <div class="card border rounded shadow-none">
              <div class="card-body">

                <h3 class="card-title text-center text-capitalize mb-1">Our Team</h3>
                <p class="text-center">view to restore records</p>
                <div class="text-center">
                  <div class="d-flex justify-content-center">
                    <h1 class="display-4 mb-0 text-primary">{{ $teams ?? 0 }}</h1>
                    <sub class="h6 pricing-duration mt-auto mb-2 text-muted fw-normal">records</sub>
                  </div>
                </div>

                <a href="{{ route('recycle.bin','team') }}" class="btn btn-label-primary d-grid w-100 waves-effect">View</a>
              </div>
            </div>
          </div>

          <div class="col-lg mb-md-0 mb-4">
            <div class="card border rounded shadow-none">
              <div class="card-body">

                <h3 class="card-title text-center text-capitalize mb-1">Banner</h3>
                <p class="text-center">view to restore records</p>
                <div class="text-center">
                  <div class="d-flex justify-content-center">
                    <h1 class="display-4 mb-0 text-primary">{{ $banners ?? 0 }}</h1>
                    <sub class="h6 pricing-duration mt-auto mb-2 text-muted fw-normal">records</sub>
                  </div>
                </div>

                <a href="{{ route('recycle.bin','team') }}" class="btn btn-label-primary d-grid w-100 waves-effect">View</a>
              </div>
            </div>
          </div>

    </div>
      <div class="row mx-0 gy-3 px-lg-5 mt-4">
          <div class="col-lg mb-md-0 mb-4">
            <div class="card border rounded shadow-none">
              <div class="card-body">

                <h3 class="card-title text-center text-capitalize mb-1">Testimonials</h3>
                <p class="text-center">view to restore records</p>
                <div class="text-center">
                  <div class="d-flex justify-content-center">
                    <h1 class="display-4 mb-0 text-primary">{{ $testimonials ?? 0 }}</h1>
                    <sub class="h6 pricing-duration mt-auto mb-2 text-muted fw-normal">records</sub>
                  </div>
                </div>

                <a href="{{ route('recycle.bin','testimonial') }}" class="btn btn-label-primary d-grid w-100 waves-effect">View</a>
              </div>
            </div>
          </div>


          <div class="col-lg mb-md-0 mb-4">
            <div class="card border rounded shadow-none">
              <div class="card-body">

                <h3 class="card-title text-center text-capitalize mb-1">FAQs</h3>
                <p class="text-center">view to restore records</p>
                <div class="text-center">
                  <div class="d-flex justify-content-center">
                    <h1 class="display-4 mb-0 text-primary">{{ $faqs ?? 0 }}</h1>
                    <sub class="h6 pricing-duration mt-auto mb-2 text-muted fw-normal">records</sub>
                  </div>
                </div>

                <a href="{{ route('recycle.bin','faq') }}" class="btn btn-label-primary d-grid w-100 waves-effect">View</a>
              </div>
            </div>
          </div>



      </div>


</div>
@endsection
