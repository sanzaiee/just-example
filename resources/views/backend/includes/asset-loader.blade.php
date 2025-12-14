{{-- Conditional Asset Loader --}}
@php
    $pageType = $pageType ?? 'base';
    $requiresForms = $requiresForms ?? false;
    $requiresTables = $requiresTables ?? false;
    $requiresEditor = $requiresEditor ?? false;
    $requiresCharts = $requiresCharts ?? false;
@endphp

{{-- Essential CSS - Always Loaded --}}
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/css/rtl/theme-default.css"
    class="template-customizer-theme-css" />
<link rel="stylesheet" href="{{ asset('') }}assets/css/demo.css" />

{{-- Essential Icons --}}
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/fonts/fontawesome.css" />

{{-- Conditional CSS based on page requirements --}}
@if ($requiresTables)
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet"
        href="{{ asset('') }}assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet"
        href="{{ asset('') }}assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
@endif

@if ($requiresForms)
    {{-- Form-related CSS --}}
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/toastr/toastr.css">
    <link rel="stylesheet" href="{{ asset('') }}assets/css/dropify.min.css">
    <link rel="stylesheet" href="{{ asset('') }}select2/4.0.11/css/select2.min.css">
@endif

@if ($requiresEditor)
    {{-- TinyMCE loaded only when needed --}}
    <script src="https://cdn.tiny.cloud/1/ao5f5se566nfpzgpqgdbvue3z6d21a5x3jp9l8hrjw648rm5/tinymce/6/tinymce.min.js"
        referrerpolicy="origin" defer></script>
@endif

@if ($requiresCharts)
    {{-- Chart libraries loaded only when needed --}}
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/apex-charts/apex-charts.css">
@endif

{{-- Essential JS - Always Loaded --}}
<script src="{{ asset('') }}assets/vendor/js/helpers.js"></script>
<script src="{{ asset('') }}assets/vendor/js/template-customizer.js"></script>
<script src="{{ asset('') }}assets/js/config.js"></script>

{{-- Core JS with defer for better performance --}}
<script src="{{ asset('') }}assets/vendor/libs/jquery/jquery.js" defer></script>
<script src="{{ asset('') }}assets/vendor/libs/popper/popper.js" defer></script>
<script src="{{ asset('') }}assets/vendor/js/bootstrap.js" defer></script>
<script src="{{ asset('') }}assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js" defer></script>
<script src="{{ asset('') }}assets/vendor/libs/node-waves/node-waves.js" defer></script>

{{-- Conditional JS based on page requirements --}}
@if ($requiresTables)
    <script src="{{ asset('') }}assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js" defer></script>
@endif

@if ($requiresForms)
    <script src="{{ asset('') }}assets/vendor/libs/flatpickr/flatpickr.js" defer></script>
    <script src="{{ asset('') }}assets/vendor/libs/toastr/toastr.js" defer></script>
    <script src="{{ asset('') }}assets/js/dropify.min.js" defer></script>
@endif

@if ($requiresCharts)
    <script src="{{ asset('') }}assets/vendor/libs/apex-charts/apex-charts.js" defer></script>
@endif

{{-- Main app JS - Always loaded at the end --}}
<script src="{{ asset('') }}assets/vendor/js/menu.js" defer></script>
<script src="{{ asset('') }}assets/js/main.js" defer></script>
<script src="{{ asset('') }}assets/js/ui-toasts.js" defer></script>
