<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="/assets/" data-template="vertical-menu-template-starter">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ get_site_name() }}</title>
    <meta name="description" content="" />

    @livewireStyles

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ get_logo('fav') }}" />

    <!-- Optimized Font Loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <!-- Font with display=swap for better performance -->
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Bootstrap Icons - defer loading -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" media="print"
        onload="this.media='all'">

    <!-- Load optimized assets based on page type -->
    @include('backend.includes.asset-loader', [
        'pageType' => $pageType ?? 'base',
        'requiresForms' => $requiresForms ?? false,
        'requiresTables' => $requiresTables ?? false,
        'requiresEditor' => $requiresEditor ?? false,
        'requiresCharts' => $requiresCharts ?? false,
    ])

    @yield('styles')
    {!! ToastMagic::styles() !!}
    @stack('css')

    <!-- Critical CSS inline for faster rendering -->
    <style>
        /* Critical rendering path styles */
        .layout-wrapper {
            display: none;
        }

        .layout-wrapper.loaded {
            display: block;
        }

        /* Add basic loading styles to prevent FOUC */
    </style>
</head>

<body>
    <!-- Page loader for slow connections -->
    <div id="page-loader"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #fff; z-index: 9999; display: flex; align-items: center; justify-content: center;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @if (auth()->check())
                @include('backend.includes.sidebar')
            @endif
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @if (auth()->check())
                    @include('backend.includes.navbar')
                @endif
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                    {{ $slot ?? '' }}
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('backend.includes.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
        <!-- Drag Target Area -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Optimized JS loading -->
    @if ($requiresEditor)
        @include('backend.includes.tinymce')
    @endif

    @if ($requiresForms)
        @include('backend.includes.fileupload')
    @endif

    @include('backend.includes.message')

    <!-- Stack for custom scripts -->
    @stack('custom-scripts')

    <!-- Livewire scripts at the end -->
    @livewireScripts
    {!! ToastMagic::scripts() !!}

    <!-- Performance monitoring and optimizations -->
    <script>
        // Hide loader once page is ready
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('page-loader');
            const layout = document.querySelector('.layout-wrapper');

            if (loader) loader.style.display = 'none';
            if (layout) layout.classList.add('loaded');

            // Performance mark for monitoring
            if (window.performance && window.performance.mark) {
                window.performance.mark('pageFullyLoaded');
            }
        });

        // Lazy load non-critical resources
        window.addEventListener('load', function() {
            // Load any deferred resources here
        });
    </script>
</body>

</html>
