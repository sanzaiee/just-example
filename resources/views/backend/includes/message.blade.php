{{-- <style>
    li {
        list-style-type: none ;
    }
</style> --}}

<script>
     // error message popup notification
    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif

    // success message popup notification
    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif

    // info message popup notification
    @if(Session::has('info'))
        toastr.info("{{ Session::get('info') }}");
    @endif

    // warning message popup notification
    @if(Session::has('warning'))
        toastr.warning("{{ Session::get('warning') }}");
    @endif
</script>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('alert', (data) => {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000"
            }

            switch(data[0].type){
                case 'success':
                    toastr.success(data[0].message);
                    break;
                case 'error':
                    toastr.error(data[0].message);
                    break;
                case 'warning':
                    toastr.warning(data[0].message);
                    break;
                default:
                    toastr.info(data[0].message);
            }
        });
    });
</script>


   {{-- @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif --}}


{{--

@if (Session::has('error'))
    <div class="alert alert-danger" id="message-alert" role="alert">
        <i class="fa fa-check"></i>
        EEERRRRRR
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-success" id="message-alert" role="alert">
        <i class="fa fa-check"></i>
        {{ Session::get('success') }}
    </div>
@endif

@if (Session::has('danger'))
    <div class="alert alert-danger" id="message-alert" role="alert">
        <i class="fa fa-check"></i>
        {{ Session::get('danger') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <i class="fa fa-warning"></i>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endif --}}

{{-- @push('custom-scripts')
    <script>
        $("#message-alert").fadeTo(2000, 500).slideUp(500);
    </script>
@endpush --}}
