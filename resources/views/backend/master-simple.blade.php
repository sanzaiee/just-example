{{--
    Ultra-lightweight master template for simple pages
    Use for: login, dashboard view-only pages, settings view, etc.
--}}
@extends('backend.master-optimized')

@php
    $requiresTables = false;
    $requiresForms = false;
    $requiresEditor = false;
    $requiresCharts = false;
@endphp

{{-- No additional JavaScript needed for simple pages --}}
