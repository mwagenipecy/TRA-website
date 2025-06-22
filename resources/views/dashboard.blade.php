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
    <!-- Dashboard Livewire Component -->
 

    @livewire('dashboard')

    <!-- Quick Setup Notice for New Users -->
    @if(auth()->user()->member && auth()->user()->member->status === 'pending')
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Account Pending Approval</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Your membership application is currently under review. You'll receive an email notification once your account has been approved by your institution's leadership.</p>
                        <div class="mt-3">
                            <a href="{{ route('profile.edit') }}" class="text-yellow-800 font-medium underline hover:text-yellow-900">
                                Complete your profile →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- System Status for TRA Officers -->
    @can('system-admin')
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-blue-800">System Status</h3>
                    <p class="text-sm text-blue-700 mt-1">All systems operational</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.settings') }}" class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50">
                        <i class="fas fa-cog mr-2"></i>
                        System Settings
                    </a>
                    <a href="{{ route('admin.audit') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-history mr-2"></i>
                        Audit Logs
                    </a>
                </div>
            </div>
        </div>
    @endcan

    <!-- Performance Tips -->
    @if(auth()->user()->isLeader())
        <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-6">
            <h3 class="text-sm font-medium text-green-800 mb-3">Leadership Tips</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-green-700">
                <div class="flex items-start">
                    <i class="fas fa-lightbulb text-green-500 mt-1 mr-2"></i>
                    <span>Regular member engagement increases event attendance by 40%</span>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-chart-line text-green-500 mt-1 mr-2"></i>
                    <span>Submit budget proposals early for faster approval</span>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-users text-green-500 mt-1 mr-2"></i>
                    <span>Collaborate with other institutions for joint events</span>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-certificate text-green-500 mt-1 mr-2"></i>
                    <span>Issue certificates promptly to maintain member motivation</span>
                </div>
            </div>
        </div>
    @endif
@endsection



@push('scripts')
<script>
    // Dashboard-specific JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize dashboard features
        console.log('Dashboard initialized for {{ auth()->user()->name }}');
        
        // Listen for Livewire events
        document.addEventListener('livewire:navigated', () => {
            console.log('Dashboard navigated');
        });

        // Handle real-time notifications
        window.addEventListener('show-notification', event => {
            const { title, message, type } = event.detail;
            
            // Check if browser supports notifications
            if ('Notification' in window) {
                // Request permission if not granted
                if (Notification.permission === 'default') {
                    Notification.requestPermission();
                }
                
                // Show notification if permission granted
                if (Notification.permission === 'granted') {
                    new Notification(title, {
                        body: message,
                        icon: '/favicon.ico',
                        badge: '/favicon.ico'
                    });
                }
            }
            
            // Also show in-app notification
            showInAppNotification(title, message, type);
        });

        // Auto-refresh dashboard every 5 minutes for TRA officers
        @if(auth()->user()->isTraOfficer())
            setInterval(function() {
                Livewire.dispatch('refresh-dashboard');
            }, 300000); // 5 minutes
        @endif
    });

    function showInAppNotification(title, message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 transform transition-all duration-300 translate-x-full`;
        
        const bgColor = type === 'success' ? 'bg-green-50 border-green-200' : 
                       type === 'error' ? 'bg-red-50 border-red-200' : 
                       type === 'warning' ? 'bg-yellow-50 border-yellow-200' : 
                       'bg-blue-50 border-blue-200';
        
        const iconColor = type === 'success' ? 'text-green-400' : 
                         type === 'error' ? 'text-red-400' : 
                         type === 'warning' ? 'text-yellow-400' : 
                         'text-blue-400';
        
        const icon = type === 'success' ? 'fas fa-check-circle' : 
                    type === 'error' ? 'fas fa-exclamation-circle' : 
                    type === 'warning' ? 'fas fa-exclamation-triangle' : 
                    'fas fa-info-circle';
        
        notification.innerHTML = `
            <div class="p-4 ${bgColor} border rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="${icon} ${iconColor}"></i>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">${title}</p>
                        <p class="mt-1 text-sm text-gray-500">${message}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Slide in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }

    // Keyboard shortcuts for power users
    document.addEventListener('keydown', function(e) {
        // Alt + D = Dashboard
        if (e.altKey && e.key === 'd') {
            e.preventDefault();
            window.location.href = '{{ route("dashboard") }}';
        }
        
        @if(auth()->user()->hasPermission('manage-events'))
        // Alt + E = Create Event
        if (e.altKey && e.key === 'e') {
            e.preventDefault();
            window.location.href = '{{ route("events.create") }}';
        }
        @endif
        
        @if(auth()->user()->hasPermission('manage-members'))
        // Alt + M = Add Member
        if (e.altKey && e.key === 'm') {
            e.preventDefault();
            window.location.href = '{{ route("members.create") }}';
        }
        @endif
        
        @if(auth()->user()->hasPermission('manage-budgets'))
        // Alt + B = Create Budget
        if (e.altKey && e.key === 'b') {
            e.preventDefault();
            window.location.href = '{{ route("budgets.create") }}';
        }
        @endif
    });
</script>
@endpush


