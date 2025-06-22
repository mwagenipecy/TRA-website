<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard - Vilabu vya Kodi')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-yellow: #F9E510;
            --primary-black: #000000;
            --light-yellow: #fef9c3;
            --dark-gray: #1f2937;
            --medium-gray: #6b7280;
            --light-gray: #f3f4f6;
            --sidebar-width: 280px;
        }
        
        .bg-primary-yellow { background-color: var(--primary-yellow); }
        .bg-primary-black { background-color: var(--primary-black); }
        .text-primary-yellow { color: var(--primary-yellow); }
        .text-primary-black { color: var(--primary-black); }
        .border-primary-yellow { border-color: var(--primary-yellow); }
        .border-primary-black { border-color: var(--primary-black); }
        
        .sidebar {
            width: var(--sidebar-width);
            transition: transform 0.3s ease-in-out;
        }
        
        .sidebar-collapsed {
            transform: translateX(-100%);
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease-in-out;
        }
        
        .main-content-expanded {
            margin-left: 0;
        }
        
        .menu-item {
            transition: all 0.2s ease-in-out;
        }
        
        .menu-item:hover {
            background-color: rgba(249, 229, 16, 0.1);
            border-left: 4px solid var(--primary-yellow);
        }
        
        .menu-item.active {
            background-color: rgba(249, 229, 16, 0.15);
            border-left: 4px solid var(--primary-yellow);
            color: var(--primary-yellow);
        }
        
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }
        
        .submenu.open {
            max-height: 500px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 50;
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <livewire:component.sidebar />
        
        <!-- Mobile sidebar overlay -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 md:hidden" @click="mobileMenuOpen = false"></div>
        
        <!-- Main Content -->
        <div class="main-content" :class="{ 'main-content-expanded': !sidebarOpen }">
            <!-- Top Navigation -->
            <livewire:component.navbar />
            
            <!-- Page Content -->
            <main class="p-6">
                <!-- Breadcrumbs -->
                @hasSection('breadcrumbs')
                    <nav class="flex mb-6" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            @yield('breadcrumbs')
                        </ol>
                    </nav>
                @endif
                
                <!-- Page Header -->
                @hasSection('header')
                    <div class="mb-6">
                        @yield('header')
                    </div>
                @endif
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert" x-data="{ show: true }" x-show="show">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="show = false">
                            <i class="fas fa-times cursor-pointer"></i>
                        </span>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert" x-data="{ show: true }" x-show="show">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="show = false">
                            <i class="fas fa-times cursor-pointer"></i>
                        </span>
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert" x-data="{ show: true }" x-show="show">
                        <span class="block sm:inline">{{ session('warning') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="show = false">
                            <i class="fas fa-times cursor-pointer"></i>
                        </span>
                    </div>
                @endif
                
                <!-- Main Content Area -->
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Alpine.js -->
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>