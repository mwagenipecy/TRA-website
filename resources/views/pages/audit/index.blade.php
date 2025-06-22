@extends('layouts.app')

@section('title', 'Dashboard - Vilabu vya Kodi')

@section('page-title', 'Dashboard')

@section('page-subtitle', 'Welcome back, {{ auth()->user()->name }}')

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <span class="text-gray-500">Dashboard</span>
    </li>
@endsection



@section('content')

<livewire:admin.audit-logs />

@endsection