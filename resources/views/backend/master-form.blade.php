{{-- Optimized master template for form pages --}}
@extends('backend.master-optimized')

@php
    $requiresTables = false;
    $requiresForms = true;
    $requiresEditor = true;
    $requiresCharts = false;
@endphp

@push('custom-scripts')
    <script>
        // Initialize form-related components
        $(document).ready(function() {
            // Initialize date pickers
            if (typeof flatpickr !== "undefined") {
                $('.datepicker').flatpickr({
                    dateFormat: "Y-m-d",
                    allowInput: true
                });
            }

            // Initialize select2
            if (typeof $().select2 !== "undefined") {
                $('.select2').select2({
                    theme: 'bootstrap-5'
                });
            }

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@endpush
