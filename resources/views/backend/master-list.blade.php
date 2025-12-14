{{-- Optimized master template for list pages with tables --}}
@extends('backend.master-optimized')

@php
    $requiresTables = true;
    $requiresForms = true; // For search filters
    $requiresEditor = false;
    $requiresCharts = false;
@endphp

@push('custom-scripts')
    <script>
        // Initialize DataTables with optimized settings
        $(document).ready(function() {
            if ($.fn.DataTable) {
                $('.table').DataTable({
                    responsive: true,
                    pageLength: 25,
                    language: {
                        searchPlaceholder: "Search..."
                    },
                    initComplete: function() {
                        // DataTable is ready
                    }
                });
            }
        });
    </script>
@endpush
