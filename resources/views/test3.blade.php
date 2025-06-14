<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRA Clubs - Professional Hero Section</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'tra-yellow': '#F9E510',
                        'tra-black': '#000000',
                    },
                    animation: {
                        'fade-in': 'fadeIn 1s ease-out',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'slide-down': 'slideDown 0.8s ease-out',
                        'scale-in': 'scaleIn 0.6s ease-out',
                        'blur-in': 'blurIn 1s ease-out',
                        'text-reveal': 'textReveal 1.2s ease-out',
                        'shimmer': 'shimmer 3s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        
        @keyframes blurIn {
            from { opacity: 0; filter: blur(10px); }
            to { opacity: 1; filter: blur(0px); }
        }
        
        @keyframes textReveal {
            from { 
                opacity: 0; 
                transform: translateY(20px) rotateX(-90deg);
                transform-origin: bottom;
            }
            to { 
                opacity: 1; 
                transform: translateY(0) rotateX(0deg);
            }
        }
        
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .slide-image {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.5s ease-in-out, transform 8s ease-out;
            transform: scale(1.1);
        }
        
        .slide-image.active {
            opacity: 1;
            transform: scale(1);
        }
        
        .gradient-overlay {
            background: linear-gradient(
                135deg,
                rgba(0, 0, 0, 0.9) 0%,
                rgba(0, 0, 0, 0.7) 25%,
                rgba(0, 0, 0, 0.4) 50%,
                rgba(0, 0, 0, 0.2) 75%,
                transparent 100%
            );
        }
        
        .shimmer-effect {
            background: linear-gradient(
                90deg,
                transparent 0%,
                rgba(249, 229, 16, 0.1) 50%,
                transparent 100%
            );
            background-size: 1000px 100%;
            animation: shimmer 3s linear infinite;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #F9E510 0%, #fff3a0 50%, #F9E510 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .slide-indicator {
            transition: all 0.3s ease;
        }
        
        .slide-indicator.active {
            width: 3rem;
            background-color: #F9E510;
        }
        
        /* Navbar styles for integration */
        .navbar-hero {
            background: transparent;
            transition: all 0.3s ease;
        }
        
        .navbar-scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-black overflow-x-hidden">


    <!-- Navigation Component -->
    <nav class="fixed w-full z-50 glass-effect shadow-lg">
        <!-- Top Utility Bar -->
        <div class="bg-tra-black text-tra-yellow border-b border-tra-yellow/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-10 sm:h-12">
                    <!-- Left Side - Contact Info -->
                    <div class="hidden md:flex items-center space-x-6 text-sm">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                            <span>info@traclubs.tz</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            <span>+255 123 456 789</span>
                        </div>
                    </div>

                    <!-- Center - Quick Actions for Mobile -->
                    <div class="md:hidden text-center">
                        <span class="text-xs font-semibold">TRA CLUBS SYSTEM</span>
                    </div>
                    
                    <!-- Right Side - Utility Links -->
                    <div class="flex items-center space-x-4 text-sm">
                        <div class="hidden lg:flex items-center space-x-4">
                            <a href="#" class="hover:text-white transition-colors duration-300 flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Help Center</span>
                            </a>
                            <a href="#" class="hover:text-white transition-colors duration-300 flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        </div>
                        
                        <!-- Social Media Links -->
                        <div class="flex items-center space-x-2">
                            <a href="#" class="w-6 h-6 bg-tra-yellow/20 rounded flex items-center justify-center hover:bg-tra-yellow hover:text-tra-black transition-all duration-300">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-6 h-6 bg-tra-yellow/20 rounded flex items-center justify-center hover:bg-tra-yellow hover:text-tra-black transition-all duration-300">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-6 h-6 bg-tra-yellow/20 rounded flex items-center justify-center hover:bg-tra-yellow hover:text-tra-black transition-all duration-300">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation Bar -->
        <div class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 sm:h-20">
                    
                    <!-- Logo Component - Left Side -->
                    <div class="flex items-center space-x-3 sm:space-x-4 animate-fade-in-left">
                        <!-- Logo Image Placeholder -->
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-tra-yellow rounded-xl flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-tra-black" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L3 7v10c0 5.55 3.84 9.739 9 9.739S21 22.55 21 17V7l-9-5zm0 2.236L19 8.236v8.764c0 4.45-3.05 7.764-7 7.764s-7-3.314-7-7.764V8.236l7-4zm0 2.764c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-tra-black tracking-tight">TRA CLUBS</div>
                            <div class="text-xs sm:text-sm font-medium text-gray-600 uppercase tracking-widest">Management System</div>
                        </div>
                    </div>
                    
                    <!-- Navigation Links & Actions - Right Side -->
                    <div class="flex items-center space-x-6 lg:space-x-8">
                        
                        <!-- Desktop Navigation Links -->
                        <div class="hidden lg:flex items-center space-x-6 xl:space-x-8 animate-fade-in-up">
                            <a href="#home" class="nav-link group relative px-4 py-2 text-tra-black hover:text-tra-yellow transition-all duration-300 font-semibold text-base">
                                <span class="relative z-10">Home</span>
                                <div class="absolute inset-0 bg-tra-yellow rounded-lg transform scale-0 group-hover:scale-100 transition-transform duration-300 opacity-10"></div>
                            </a>
                            <a href="#about" class="nav-link group relative px-4 py-2 text-tra-black hover:text-tra-yellow transition-all duration-300 font-semibold text-base">
                                <span class="relative z-10">About</span>
                                <div class="absolute inset-0 bg-tra-yellow rounded-lg transform scale-0 group-hover:scale-100 transition-transform duration-300 opacity-10"></div>
                            </a>
                            <a href="#institutions" class="nav-link group relative px-4 py-2 text-tra-black hover:text-tra-yellow transition-all duration-300 font-semibold text-base">
                                <span class="relative z-10">Institutions</span>
                                <div class="absolute inset-0 bg-tra-yellow rounded-lg transform scale-0 group-hover:scale-100 transition-transform duration-300 opacity-10"></div>
                            </a>
                            <a href="#events" class="nav-link group relative px-4 py-2 text-tra-black hover:text-tra-yellow transition-all duration-300 font-semibold text-base">
                                <span class="relative z-10">Events</span>
                                <div class="absolute inset-0 bg-tra-yellow rounded-lg transform scale-0 group-hover:scale-100 transition-transform duration-300 opacity-10"></div>
                            </a>
                            <a href="#funding" class="nav-link group relative px-4 py-2 text-tra-black hover:text-tra-yellow transition-all duration-300 font-semibold text-base">
                                <span class="relative z-10">Funding</span>
                                <div class="absolute inset-0 bg-tra-yellow rounded-lg transform scale-0 group-hover:scale-100 transition-transform duration-300 opacity-10"></div>
                            </a>
                        </div>
                        
                        <!-- Search & Auth Buttons -->
                        <div class="flex items-center space-x-3 sm:space-x-4 animate-fade-in-right">
                            
                            <!-- Search Icon -->
                            <button class="hidden md:flex items-center justify-center w-10 h-10 text-gray-600 hover:text-tra-yellow hover:bg-gray-100 rounded-lg transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                            
                            <!-- Desktop Auth Buttons -->
                            <div class="hidden md:flex items-center space-x-3">
                                <button class="bg-tra-yellow text-tra-black px-5 lg:px-6 py-2.5 rounded-lg font-semibold hover:bg-yellow-400 transition-all duration-300 hover:scale-105 shadow-md hover:shadow-lg text-sm">
                                    Login
                                </button>
                                <button class="border-2 border-tra-black text-tra-black px-5 lg:px-6 py-2.5 rounded-lg font-semibold hover:bg-tra-black hover:text-white transition-all duration-300 hover:scale-105 text-sm">
                                    Register
                                </button>
                            </div>
                            
                            <!-- Mobile Menu Button -->
                            <button onclick="toggleMobileMenu()" class="lg:hidden flex items-center justify-center w-11 h-11 text-tra-black hover:text-tra-yellow hover:bg-gray-100 rounded-lg transition-all duration-300 border border-gray-200">
                                <svg id="menuIcon" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <svg id="closeIcon" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Navigation Menu -->
            <div id="mobileMenu" class="lg:hidden mobile-menu-hidden transition-all duration-300 ease-in-out bg-white border-t border-gray-200 shadow-lg">
                <div class="px-4 py-6 space-y-4">
                    
                    <!-- Mobile Search Bar -->
                    <div class="relative mb-6">
                        <input type="text" placeholder="Search institutions, events..." class="w-full bg-gray-50 border border-gray-200 text-gray-800 placeholder-gray-500 px-4 py-3 rounded-lg focus:outline-none focus:border-tra-yellow focus:ring-2 focus:ring-tra-yellow/20">
                        <svg class="absolute right-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    
                    <!-- Mobile Navigation Links -->
                    <div class="space-y-1">
                        <a href="#home" onclick="toggleMobileMenu()" class="block text-tra-black hover:text-tra-yellow hover:bg-gray-50 transition-all duration-300 font-semibold text-lg py-4 px-4 rounded-lg border-l-4 border-transparent hover:border-tra-yellow">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                <span>Home</span>
                            </div>
                        </a>
                        
                        <a href="#about" onclick="toggleMobileMenu()" class="block text-tra-black hover:text-tra-yellow hover:bg-gray-50 transition-all duration-300 font-semibold text-lg py-4 px-4 rounded-lg border-l-4 border-transparent hover:border-tra-yellow">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <span>About</span>
                            </div>
                        </a>
                        
                        <a href="#institutions" onclick="toggleMobileMenu()" class="block text-tra-black hover:text-tra-yellow hover:bg-gray-50 transition-all duration-300 font-semibold text-lg py-4 px-4 rounded-lg border-l-4 border-transparent hover:border-tra-yellow">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Institutions</span>
                            </div>
                        </a>
                        
                        <a href="#events" onclick="toggleMobileMenu()" class="block text-tra-black hover:text-tra-yellow hover:bg-gray-50 transition-all duration-300 font-semibold text-lg py-4 px-4 rounded-lg border-l-4 border-transparent hover:border-tra-yellow">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Events</span>
                            </div>
                        </a>
                        
                        <a href="#funding" onclick="toggleMobileMenu()" class="block text-tra-black hover:text-tra-yellow hover:bg-gray-50 transition-all duration-300 font-semibold text-lg py-4 px-4 rounded-lg border-l-4 border-transparent hover:border-tra-yellow">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"></path>
                                </svg>
                                <span>Funding</span>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Mobile Utility Links -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <a href="#" class="flex items-center space-x-2 text-gray-600 hover:text-tra-yellow transition-colors duration-300 p-3 bg-gray-50 rounded-lg">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm">Help Center</span>
                            </a>
                            <a href="#" class="flex items-center space-x-2 text-gray-600 hover:text-tra-yellow transition-colors duration-300 p-3 bg-gray-50 rounded-lg">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                </svg>
                                <span class="text-sm">Dashboard</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Mobile Action Buttons -->
                    <div class="border-t border-gray-200 pt-6 space-y-3">
                        <button class="w-full bg-tra-yellow text-tra-black py-3 rounded-lg font-semibold hover:bg-yellow-400 transition-all duration-300 shadow-md">
                            Login
                        </button>
                        <button class="w-full border-2 border-tra-black text-tra-black py-3 rounded-lg font-semibold hover:bg-tra-black hover:text-white transition-all duration-300">
                            Register
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>





    <!-- Professional Hero Section with Dynamic Slideshow -->
    <section class="relative h-screen overflow-hidden">
        
        <!-- Dynamic Slideshow Background -->
        <div class="absolute inset-0 z-0">
            <!-- Slide Images Container -->
            <div id="slideshowContainer" class="relative w-full h-full">
                <!-- Dynamic images will be inserted here -->
                <!-- Example structure for blade template:
               
                -->
                
                <!-- Demo images for preview -->
                <div class="slide-image active" data-slide="0">
                    <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2070" alt="Traffic Safety" class="w-full h-full object-cover">
                </div>
                <div class="slide-image" data-slide="1">
                    <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2069" alt="Road Safety Education" class="w-full h-full object-cover">
                </div>
                <div class="slide-image" data-slide="2">
                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?q=80&w=2070" alt="Student Activities" class="w-full h-full object-cover">
                </div>
                <div class="slide-image" data-slide="3">
                    <img src="https://images.unsplash.com/photo-1472214103451-9374bd1c798e?q=80&w=2070" alt="Community Engagement" class="w-full h-full object-cover">
                </div>
            </div>
            
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 gradient-overlay z-10"></div>
            
            <!-- Animated Pattern Overlay -->
            <div class="absolute inset-0 opacity-10 z-20">
                <div class="absolute inset-0 shimmer-effect"></div>
            </div>
        </div>
        
        <!-- Hero Content -->
        <div class="relative z-30 h-full flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                    
                    <!-- Left Content -->
                    <div class="text-left space-y-6 lg:space-y-8">
                        
                        <!-- Badge -->
                        <div class="inline-flex animate-slide-down">
                            <div class="glass-panel px-4 sm:px-6 py-2 sm:py-3 rounded-full inline-flex items-center space-x-2 group hover:scale-105 transition-transform duration-300">
                                <div class="w-2 h-2 bg-tra-yellow rounded-full animate-pulse-slow"></div>
                                <span class="text-tra-yellow font-semibold text-xs sm:text-sm uppercase tracking-wider">
                                    Empowering Road Safety
                                </span>
                                <svg class="w-4 h-4 text-tra-yellow group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Main Title -->
                        <div class="space-y-2">
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight">
                                <span class="block text-white animate-text-reveal">
                                    Building Safer
                                </span>
                                <span class="block text-gradient animate-text-reveal" style="animation-delay: 0.2s;">
                                    Communities
                                </span>
                                <span class="block text-white text-2xl sm:text-3xl lg:text-4xl font-light animate-text-reveal" style="animation-delay: 0.4s;">
                                    Through Education
                                </span>
                            </h1>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-gray-300 text-base sm:text-lg lg:text-xl max-w-xl leading-relaxed animate-slide-up" style="animation-delay: 0.6s;">
                            Tanzania's premier platform connecting traffic safety clubs across educational institutions. Join us in creating a culture of road safety awareness.
                        </p>
                        
                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 animate-scale-in" style="animation-delay: 0.8s;">
                            <a href="#" class="group relative px-8 py-4 bg-tra-yellow text-tra-black font-bold rounded-full overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-tra-yellow/30">
                                <span class="relative z-10 flex items-center justify-center space-x-2">
                                    <span>Get Started</span>
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </span>
                                <div class="absolute inset-0 bg-yellow-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                            </a>
                            
                            <a href="#" class="group relative px-8 py-4 border-2 border-tra-yellow text-tra-yellow font-bold rounded-full overflow-hidden transition-all duration-300 hover:text-tra-black">
                                <span class="relative z-10">Learn More</span>
                                <div class="absolute inset-0 bg-tra-yellow transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                            </a>
                        </div>
                        
                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-6 lg:gap-8 pt-8 border-t border-white/10 animate-blur-in" style="animation-delay: 1s;">
                            <div class="text-center lg:text-left">
                                <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-tra-yellow counter" data-target="150">0</div>
                                <div class="text-xs sm:text-sm text-gray-400 uppercase tracking-wider mt-1">Active Clubs</div>
                            </div>
                            <div class="text-center lg:text-left">
                                <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-tra-yellow counter" data-target="25000">0</div>
                                <div class="text-xs sm:text-sm text-gray-400 uppercase tracking-wider mt-1">Members</div>
                            </div>
                            <div class="text-center lg:text-left">
                                <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-tra-yellow counter" data-target="95">0</div>
                                <div class="text-xs sm:text-sm text-gray-400 uppercase tracking-wider mt-1">Institutions</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Content - Feature Cards -->
                    <div class="hidden lg:block relative">
                        <div class="relative w-full h-full">
                            
                            <!-- Floating Feature Cards -->
                            <div class="absolute top-0 right-0 glass-panel p-6 rounded-2xl shadow-2xl animate-float max-w-xs">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="w-12 h-12 bg-tra-yellow rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-tra-black" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-bold">Community Driven</h3>
                                        <p class="text-gray-400 text-sm">Join 25,000+ members</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="flex -space-x-2">
                                        <div class="w-8 h-8 bg-tra-yellow rounded-full border-2 border-black"></div>
                                        <div class="w-8 h-8 bg-blue-500 rounded-full border-2 border-black"></div>
                                        <div class="w-8 h-8 bg-green-500 rounded-full border-2 border-black"></div>
                                        <div class="w-8 h-8 bg-red-500 rounded-full border-2 border-black"></div>
                                    </div>
                                    <span class="text-xs text-gray-400">+25K members</span>
                                </div>
                            </div>
                            
                            <div class="absolute bottom-0 left-0 glass-panel p-6 rounded-2xl shadow-2xl animate-float max-w-xs" style="animation-delay: 0.5s;">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-400 text-sm">Weekly Activities</span>
                                        <span class="text-tra-yellow font-bold">+45%</span>
                                    </div>
                                    <div class="w-full bg-white/10 rounded-full h-2">
                                        <div class="bg-tra-yellow h-2 rounded-full" style="width: 75%"></div>
                                    </div>
                                    <p class="text-white text-sm">Engagement rate increased this month</p>
                                </div>
                            </div>
                            
                            <div class="absolute top-1/2 right-1/4 transform -translate-y-1/2 glass-panel p-4 rounded-2xl shadow-xl animate-float" style="animation-delay: 1s;">
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                    <span class="text-white font-medium">Live Event Now</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Slide Indicators -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-40 flex items-center space-x-3">
            <!-- Dynamic indicators will be generated based on slides -->
            <button class="slide-indicator w-12 h-1 bg-white/30 rounded-full active" data-slide="0"></button>
            <button class="slide-indicator w-6 h-1 bg-white/30 rounded-full" data-slide="1"></button>
            <button class="slide-indicator w-6 h-1 bg-white/30 rounded-full" data-slide="2"></button>
            <button class="slide-indicator w-6 h-1 bg-white/30 rounded-full" data-slide="3"></button>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 right-8 z-40 hidden lg:block">
            <div class="animate-bounce">
                <a href="#next-section" class="flex items-center space-x-2 text-white/70 hover:text-tra-yellow transition-colors duration-300">
                    <span class="text-sm uppercase tracking-wider">Scroll</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>



   <!-- TRA Clubs by Category Section -->
<section id="clubs" class="py-16 sm:py-20 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <div class="inline-flex items-center justify-center mb-4 animate-fade-in-down">
                <div class="h-1 w-12 bg-tra-yellow rounded-full mr-3"></div>
                <span class="text-tra-yellow font-bold text-sm sm:text-base uppercase tracking-wider">Our Network</span>
                <div class="h-1 w-12 bg-tra-yellow rounded-full ml-3"></div>
            </div>
            
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-tra-black mb-4 animate-fade-in-up">
                TRA Clubs Across Tanzania
            </h2>
            
            <p class="text-gray-600 text-base sm:text-lg max-w-3xl mx-auto animate-fade-in-up" style="animation-delay: 0.1s;">
                Discover our extensive network of traffic safety clubs in universities and schools, 
                working together to create safer roads for all Tanzanians.
            </p>
        </div>

        <!-- Category Tabs -->
        <div class="flex justify-center mb-8 sm:mb-12">
            <div class="bg-white rounded-full shadow-lg p-1 inline-flex animate-scale-in">
                <button 
                    onclick="switchCategory('universities')" 
                    id="universities-tab"
                    class="category-tab px-6 sm:px-8 py-3 sm:py-4 rounded-full font-semibold text-sm sm:text-base transition-all duration-300 bg-tra-yellow text-tra-black"
                >
                    <span class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                        <span>Universities</span>
                    </span>
                </button>
                <button 
                    onclick="switchCategory('schools')" 
                    id="schools-tab"
                    class="category-tab px-6 sm:px-8 py-3 sm:py-4 rounded-full font-semibold text-sm sm:text-base transition-all duration-300 text-gray-600 hover:text-tra-black"
                >
                    <span class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>Schools</span>
                    </span>
                </button>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-12 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="bg-white rounded-xl p-4 sm:p-6 text-center shadow-md hover:shadow-xl transition-shadow duration-300">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-tra-yellow/10 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-tra-yellow" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div class="text-2xl sm:text-3xl font-bold text-tra-black mb-1" id="universities-count">{{ $universitiesCount ?? 25 }}</div>
                <div class="text-xs sm:text-sm text-gray-600">Universities</div>
            </div>
            
            <div class="bg-white rounded-xl p-4 sm:p-6 text-center shadow-md hover:shadow-xl transition-shadow duration-300">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-tra-yellow/10 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-tra-yellow" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="text-2xl sm:text-3xl font-bold text-tra-black mb-1" id="schools-count">{{ $schoolsCount ?? 100 }}</div>
                <div class="text-xs sm:text-sm text-gray-600">Schools</div>
            </div>
            
            <div class="bg-white rounded-xl p-4 sm:p-6 text-center shadow-md hover:shadow-xl transition-shadow duration-300">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-tra-yellow/10 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-tra-yellow" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
                <div class="text-2xl sm:text-3xl font-bold text-tra-black mb-1">{{ number_format($totalMembers ?? 25000) }}</div>
                <div class="text-xs sm:text-sm text-gray-600">Total Members</div>
            </div>
            
            <div class="bg-white rounded-xl p-4 sm:p-6 text-center shadow-md hover:shadow-xl transition-shadow duration-300">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-tra-yellow/10 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-tra-yellow" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="text-2xl sm:text-3xl font-bold text-tra-black mb-1">{{ $activeEvents ?? 45 }}</div>
                <div class="text-xs sm:text-sm text-gray-600">Active Events</div>
            </div>
        </div>

        <!-- Universities Content -->
        <div id="universities-content" class="category-content">
            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-8 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input 
                            type="text" 
                            placeholder="Search universities..."
                            class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-tra-yellow focus:ring-2 focus:ring-tra-yellow/20 transition-all duration-300"
                            onkeyup="filterClubs('universities', this.value)"
                        >
                        <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <select class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-tra-yellow focus:ring-2 focus:ring-tra-yellow/20 transition-all duration-300">
                        <option>All Regions</option>
                        <option>Dar es Salaam</option>
                        <option>Dodoma</option>
                        <option>Arusha</option>
                        <option>Mwanza</option>
                        <option>Morogoro</option>
                    </select>
                    <select class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-tra-yellow focus:ring-2 focus:ring-tra-yellow/20 transition-all duration-300">
                        <option>Sort by Name</option>
                        <option>Sort by Members</option>
                        <option>Sort by Activity</option>
                        <option>Sort by Region</option>
                    </select>
                </div>
            </div>

            <!-- Universities Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 0.4s;">
                @forelse($universities ?? [] as $university)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
                        <div class="relative h-48 overflow-hidden">
                            <img 
                                src="{{ asset($university->image ?? 'images/default-university.jpg') }}" 
                                alt="{{ $university->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute top-4 right-4">
                                <span class="bg-tra-yellow text-tra-black px-3 py-1 rounded-full text-xs font-bold">
                                    {{ $university->members_count ?? 0 }} Members
                                </span>
                            </div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <h3 class="text-white font-bold text-lg mb-1">{{ $university->name }}</h3>
                                <p class="text-white/80 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $university->location }}
                                </p>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-1">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-4 h-4 {{ $i < ($university->rating ?? 4) ? 'text-tra-yellow' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600">Est. {{ $university->established ?? '2020' }}</span>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $university->description ?? 'Promoting road safety awareness and education through various programs and initiatives.' }}
                            </p>
                            
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex -space-x-2">
                                    @for($i = 0; $i < min(4, $university->members_count ?? 4); $i++)
                                        <div class="w-8 h-8 bg-gray-300 rounded-full border-2 border-white"></div>
                                    @endfor
                                    @if(($university->members_count ?? 0) > 4)
                                        <div class="w-8 h-8 bg-tra-yellow rounded-full border-2 border-white flex items-center justify-center">
                                            <span class="text-xs font-bold text-tra-black">+{{ $university->members_count - 4 }}</span>
                                        </div>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500">{{ $university->active_events ?? 3 }} active events</span>
                            </div>
                            
                            <div class="flex gap-2">
                                <a href="{{ route('clubs.show', $university->id ?? 1) }}" class="flex-1 bg-tra-yellow text-tra-black py-2 rounded-lg font-semibold text-center hover:bg-yellow-400 transition-colors duration-300">
                                    View Details
                                </a>
                                <button class="px-4 py-2 border border-gray-200 rounded-lg hover:border-tra-yellow hover:text-tra-yellow transition-colors duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Default University Cards -->
                    @for($i = 0; $i < 6; $i++)
                        <div class="bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
                            <div class="relative h-48 overflow-hidden">
                                <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 group-hover:scale-110 transition-transform duration-500"></div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                                <div class="absolute top-4 right-4">
                                    <span class="bg-tra-yellow text-tra-black px-3 py-1 rounded-full text-xs font-bold">
                                        {{ rand(50, 500) }} Members
                                    </span>
                                </div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h3 class="text-white font-bold text-lg mb-1">University of {{ ['Dar es Salaam', 'Dodoma', 'Mwanza', 'Arusha', 'Morogoro', 'Mbeya'][$i] }}</h3>
                                    <p class="text-white/80 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ ['Dar es Salaam', 'Dodoma', 'Mwanza', 'Arusha', 'Morogoro', 'Mbeya'][$i] }}
                                    </p>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-1">
                                        @for($j = 0; $j < 5; $j++)
                                            <svg class="w-4 h-4 {{ $j < 4 ? 'text-tra-yellow' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-600">Est. {{ 2015 + $i }}</span>
                                </div>
                                
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    Promoting road safety awareness and education through various programs and initiatives across campus.
                                </p>
                                
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex -space-x-2">
                                        @for($j = 0; $j < 4; $j++)
                                            <div class="w-8 h-8 bg-gray-300 rounded-full border-2 border-white"></div>
                                        @endfor
                                        <div class="w-8 h-8 bg-tra-yellow rounded-full border-2 border-white flex items-center justify-center">
                                            <span class="text-xs font-bold text-tra-black">+{{ rand(10, 100) }}</span>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ rand(2, 8) }} active events</span>
                                </div>
                                
                                <div class="flex gap-2">
                                    <a href="#" class="flex-1 bg-tra-yellow text-tra-black py-2 rounded-lg font-semibold text-center hover:bg-yellow-400 transition-colors duration-300">
                                        View Details
                                    </a>
                                    <button class="px-4 py-2 border border-gray-200 rounded-lg hover:border-tra-yellow hover:text-tra-yellow transition-colors duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endforelse
            </div>
            
            <!-- Load More Button -->
            <div class="text-center mt-12">
                <button class="bg-tra-black text-white px-8 py-3 rounded-full font-semibold hover:bg-gray-800 transition-colors duration-300 inline-flex items-center space-x-2">
                    <span>Load More Universities</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Schools Content -->
        <div id="schools-content" class="category-content hidden">
            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-8 animate-fade-in-up">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input 
                            type="text" 
                            placeholder="Search schools..."
                            class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-tra-yellow focus:ring-2 focus:ring-tra-yellow/20 transition-all duration-300"
                            onkeyup="filterClubs('schools', this.value)"
                        >
                        <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <select class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-tra-yellow focus:ring-2 focus:ring-tra-yellow/20 transition-all duration-300">
                        <option>All Regions</option>
                        <option>Dar es Salaam</option>
                        <option>Dodoma</option>
                        <option>Arusha</option>
                        <option>Mwanza</option>
                        <option>Morogoro</option>
                    </select>
                    <select class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-tra-yellow focus:ring-2 focus:ring-tra-yellow/20 transition-all duration-300">
                        <option>All Types</option>
                        <option>Primary Schools</option>
                        <option>Secondary Schools</option>
                        <option>High Schools</option>
                    </select>
                </div>
            </div>

            <!-- Schools Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up">
                @forelse($schools ?? [] as $school)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
                        <div class="relative h-40 overflow-hidden">
                            <img 
                                src="{{ asset($school->image ?? 'images/default-school.jpg') }}" 
                                alt="{{ $school->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute top-3 right-3">
                                <span class="bg-tra-yellow text-tra-black px-2 py-1 rounded-full text-xs font-bold">
                                    {{ $school->members_count ?? 0 }} Members
                                </span>
                            </div>
                            <div class="absolute bottom-3 left-3 right-3">
                                <span class="bg-white/20 backdrop-blur-sm text-white text-xs px-2 py-1 rounded-full">
                                    {{ $school->type ?? 'Secondary School' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-lg text-tra-black mb-1 line-clamp-1">{{ $school->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $school->location }}
                            </p>
                            
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-1">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-3 h-3 {{ $i < ($school->rating ?? 4) ? 'text-tra-yellow' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-500">{{ $school->active_events ?? 2 }} events</span>
                            </div>
                            
                            <a href="{{ route('clubs.show', $school->id ?? 1) }}" class="block w-full bg-tra-yellow text-tra-black py-2 rounded-lg font-semibold text-center text-sm hover:bg-yellow-400 transition-colors duration-300">
                                View Club
                            </a>
                        </div>
                    </div>
                @empty
                    <!-- Default School Cards -->
                    @php
                        $schoolNames = [
                            'Kilakala Secondary School',
                            'Azania Secondary School',
                            'Jangwani High School',
                            'Tambaza High School',
                            'Kibasila Secondary School',
                            'Benjamin Mkapa High School',
                            'Makongo Secondary School',
                            'Kigamboni Secondary School'
                        ];
                        $locations = ['Dar es Salaam', 'Morogoro', 'Dodoma', 'Arusha', 'Mwanza', 'Mbeya', 'Tanga', 'Kilimanjaro'];
                        $types = ['Secondary School', 'High School', 'Primary School'];
                    @endphp
                    
                    @for($i = 0; $i < 8; $i++)
                        <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
                            <div class="relative h-40 overflow-hidden">
                                <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 group-hover:scale-110 transition-transform duration-500"></div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute top-3 right-3">
                                    <span class="bg-tra-yellow text-tra-black px-2 py-1 rounded-full text-xs font-bold">
                                        {{ rand(30, 200) }} Members
                                    </span>
                                </div>
                                <div class="absolute bottom-3 left-3 right-3">
                                    <span class="bg-white/20 backdrop-blur-sm text-white text-xs px-2 py-1 rounded-full">
                                        {{ $types[array_rand($types)] }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-lg text-tra-black mb-1 line-clamp-1">{{ $schoolNames[$i] }}</h3>
                                <p class="text-gray-600 text-sm mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $locations[$i] }}
                                </p>
                                
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-1">
                                        @for($j = 0; $j < 5; $j++)
                                            <svg class="w-3 h-3 {{ $j < 4 ? 'text-tra-yellow' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-500">{{ rand(1, 5) }} events</span>
                                </div>
                                
                                <a href="#" class="block w-full bg-tra-yellow text-tra-black py-2 rounded-lg font-semibold text-center text-sm hover:bg-yellow-400 transition-colors duration-300">
                                    View Club
                                </a>
                            </div>
                        </div>
                    @endfor
                @endforelse
            </div>
            
            <!-- Load More Button -->
            <div class="text-center mt-12">
                <button class="bg-tra-black text-white px-8 py-3 rounded-full font-semibold hover:bg-gray-800 transition-colors duration-300 inline-flex items-center space-x-2">
                    <span>Load More Schools</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Featured Club of the Month -->
        <div class="mt-16 bg-gradient-to-r from-tra-black to-gray-900 rounded-2xl p-8 sm:p-12 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0 bg-tra-yellow transform rotate-45 scale-150"></div>
            </div>
            <div class="relative z-10">
                <div class="flex flex-col lg:flex-row items-center gap-8">
                    <div class="flex-1">
                        <span class="bg-tra-yellow text-tra-black px-4 py-2 rounded-full text-sm font-bold inline-block mb-4">
                            🏆 Club of the Month
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-bold mb-4">
                            {{ $featuredClub->name ?? 'University of Dar es Salaam TRA Club' }}
                        </h3>
                        <p class="text-gray-300 mb-6 leading-relaxed">
                            {{ $featuredClub->achievement ?? 'Recognized for outstanding contribution to road safety education with over 50 awareness campaigns conducted this year, reaching more than 10,000 students and community members.' }}
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-tra-yellow" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                                <span>{{ $featuredClub->members_count ?? '500+' }} Active Members</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-tra-yellow" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $featuredClub->events_count ?? '25' }} Events This Year</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-tra-yellow" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $featuredClub->impact ?? '10,000+' }} People Reached</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-96 h-64 bg-white/10 backdrop-blur-sm rounded-xl overflow-hidden">
                        <img 
                            src="{{ asset($featuredClub->image ?? 'images/featured-club.jpg') }}" 
                            alt="Featured Club"
                            class="w-full h-full object-cover"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript for Category Switching and Filtering -->
<script>
    function switchCategory(category) {
        // Update tab styles
        const tabs = document.querySelectorAll('.category-tab');
        tabs.forEach(tab => {
            tab.classList.remove('bg-tra-yellow', 'text-tra-black');
            tab.classList.add('text-gray-600', 'hover:text-tra-black');
        });
        
        const activeTab = document.getElementById(category + '-tab');
        activeTab.classList.remove('text-gray-600', 'hover:text-tra-black');
        activeTab.classList.add('bg-tra-yellow', 'text-tra-black');
        
        // Show/hide content
        document.querySelectorAll('.category-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        const activeContent = document.getElementById(category + '-content');
        activeContent.classList.remove('hidden');
        
        // Reset animations
        activeContent.querySelectorAll('.animate-fade-in-up').forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
                el.style.transition = 'all 0.6s ease-out';
            }, index * 100);
        });
    }
    
    function filterClubs(category, searchTerm) {
        // This would typically make an AJAX request to filter results
        console.log('Filtering', category, 'with term:', searchTerm);
        // Implementation would go here
    }
    
    // Initialize animations on scroll
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        document.querySelectorAll('.animate-fade-in-up, .animate-fade-in-down, .animate-scale-in').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });
    });
</script>

<style>
    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }
    
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out;
    }
    
    .animate-fade-in-down {
        animation: fadeInDown 0.8s ease-out;
    }
    
    .animate-scale-in {
        animation: scaleIn 0.6s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>







<!-- Events & Activities Section -->
<section id="events" class="py-16 sm:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <div class="inline-flex items-center justify-center mb-4 animate-fade-in-down">
                <div class="h-1 w-12 bg-tra-yellow rounded-full mr-3"></div>
                <span class="text-tra-yellow font-bold text-sm sm:text-base uppercase tracking-wider">Upcoming Events</span>
                <div class="h-1 w-12 bg-tra-yellow rounded-full ml-3"></div>
            </div>
            
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-tra-black mb-4 animate-fade-in-up">
                Events & Activities
            </h2>
            
            <p class="text-gray-600 text-base sm:text-lg max-w-3xl mx-auto animate-fade-in-up" style="animation-delay: 0.1s;">
                Join our upcoming road safety events and activities. From workshops to awareness campaigns, 
                there's always something happening in our community.
            </p>
        </div>

        <!-- Event Filters -->
        <div class="flex flex-wrap justify-center gap-3 mb-10 animate-fade-in-up" style="animation-delay: 0.2s;">
            <button onclick="filterEvents('all')" class="event-filter active px-6 py-2 rounded-full font-semibold text-sm transition-all duration-300">
                All Events
            </button>
            <button onclick="filterEvents('workshop')" class="event-filter px-6 py-2 rounded-full font-semibold text-sm transition-all duration-300">
                Workshops
            </button>
            <button onclick="filterEvents('campaign')" class="event-filter px-6 py-2 rounded-full font-semibold text-sm transition-all duration-300">
                Campaigns
            </button>
            <button onclick="filterEvents('training')" class="event-filter px-6 py-2 rounded-full font-semibold text-sm transition-all duration-300">
                Training
            </button>
            <button onclick="filterEvents('competition')" class="event-filter px-6 py-2 rounded-full font-semibold text-sm transition-all duration-300">
                Competitions
            </button>
            <button onclick="filterEvents('seminar')" class="event-filter px-6 py-2 rounded-full font-semibold text-sm transition-all duration-300">
                Seminars
            </button>
        </div>

        <!-- Events Calendar View Toggle -->
        <div class="flex justify-end mb-8">
            <div class="bg-white rounded-lg shadow-sm p-1 inline-flex">
                <button onclick="switchView('grid')" id="grid-view" class="view-toggle px-4 py-2 rounded-md font-medium text-sm transition-all duration-300 bg-tra-yellow text-tra-black">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </button>
                <button onclick="switchView('list')" id="list-view" class="view-toggle px-4 py-2 rounded-md font-medium text-sm transition-all duration-300 text-gray-600 hover:text-tra-black">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Featured Event Banner -->
        <div class="mb-12 animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="bg-gradient-to-r from-tra-yellow to-yellow-400 rounded-2xl overflow-hidden shadow-xl">
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-2/3 p-8 sm:p-12">
                        <div class="flex items-center space-x-2 mb-4">
                            <span class="bg-tra-black text-tra-yellow px-3 py-1 rounded-full text-xs font-bold uppercase">Featured Event</span>
                            <span class="bg-white/20 text-tra-black px-3 py-1 rounded-full text-xs font-bold">Limited Seats</span>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-bold text-tra-black mb-4">
                            {{ $featuredEvent->title ?? 'National Road Safety Awareness Week 2025' }}
                        </h3>
                        <p class="text-tra-black/80 mb-6 text-base sm:text-lg">
                            {{ $featuredEvent->description ?? 'Join us for a week-long series of activities focused on promoting road safety across Tanzania. Featuring expert speakers, interactive workshops, and community outreach programs.' }}
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-tra-black/60" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-tra-black font-medium">{{ $featuredEvent->date ?? 'March 15-21, 2025' }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-tra-black/60" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-tra-black font-medium">{{ $featuredEvent->location ?? 'Multiple Locations' }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-tra-black/60" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                                <span class="text-tra-black font-medium">{{ $featuredEvent->attendees ?? '500+' }} Attending</span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('events.show', $featuredEvent->id ?? 1) }}" class="bg-tra-black text-tra-yellow px-6 py-3 rounded-lg font-bold hover:bg-gray-900 transition-colors duration-300 inline-flex items-center space-x-2">
                                <span>Register Now</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                            <button class="bg-white/20 backdrop-blur-sm text-tra-black px-6 py-3 rounded-lg font-bold hover:bg-white/30 transition-colors duration-300">
                                Learn More
                            </button>
                        </div>
                    </div>
                    <div class="lg:w-1/3 relative h-64 lg:h-auto">
                        <img 
                            src="{{ asset($featuredEvent->image ?? 'images/featured-event.jpg') }}" 
                            alt="{{ $featuredEvent->title ?? 'Featured Event' }}"
                            class="absolute inset-0 w-full h-full object-cover"
                        >
                        <div class="absolute inset-0 bg-gradient-to-r from-tra-yellow/20 to-transparent lg:from-transparent lg:to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Grid View -->
        <div id="events-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 0.4s;">
            @forelse($events ?? [] as $event)
                <div class="event-card bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden" data-category="{{ $event->category ?? 'workshop' }}">
                    <div class="relative">
                        <img 
                            src="{{ asset($event->image ?? 'images/default-event.jpg') }}" 
                            alt="{{ $event->title }}"
                            class="w-full h-48 object-cover"
                        >
                        <div class="absolute top-4 left-4">
                            <span class="bg-tra-yellow text-tra-black px-3 py-1 rounded-full text-xs font-bold uppercase">
                                {{ $event->category ?? 'Workshop' }}
                            </span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <div class="bg-white rounded-lg p-2 text-center shadow-lg">
                                <div class="text-2xl font-bold text-tra-black">{{ $event->day ?? '15' }}</div>
                                <div class="text-xs text-gray-600 uppercase">{{ $event->month ?? 'Mar' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-tra-black mb-2 line-clamp-2">
                            {{ $event->title }}
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ $event->description }}
                        </p>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $event->time ?? '9:00 AM - 5:00 PM' }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $event->location ?? 'UDSM Main Campus' }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                                <span>{{ $event->attendees ?? '50' }} / {{ $event->capacity ?? '100' }} Attendees</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold {{ $event->is_free ?? true ? 'text-green-600' : 'text-tra-black' }}">
                                {{ $event->is_free ?? true ? 'Free Event' : 'TSH ' . number_format($event->price ?? 0) }}
                            </span>
                            <a href="{{ route('events.show', $event->id ?? 1) }}" class="bg-tra-yellow text-tra-black px-4 py-2 rounded-lg font-semibold text-sm hover:bg-yellow-400 transition-colors duration-300">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Default Event Cards -->
                @php
                    $defaultEvents = [
                        [
                            'title' => 'Road Safety Workshop for Students',
                            'category' => 'workshop',
                            'description' => 'Interactive workshop covering essential road safety rules, defensive driving techniques, and first aid basics.',
                            'date' => '20',
                            'month' => 'Mar',
                            'time' => '9:00 AM - 12:00 PM',
                            'location' => 'University of Dar es Salaam',
                            'attendees' => 45,
                            'capacity' => 60,
                            'is_free' => true
                        ],
                        [
                            'title' => 'Community Traffic Awareness Campaign',
                            'category' => 'campaign',
                            'description' => 'Join us in spreading road safety awareness in local communities through interactive demonstrations.',
                            'date' => '25',
                            'month' => 'Mar',
                            'time' => '8:00 AM - 6:00 PM',
                            'location' => 'Kariakoo Market Area',
                            'attendees' => 120,
                            'capacity' => 200,
                            'is_free' => true
                        ],
                        [
                            'title' => 'Advanced Driver Training Program',
                            'category' => 'training',
                            'description' => 'Professional driver training program focusing on defensive driving and emergency response techniques.',
                            'date' => '28',
                            'month' => 'Mar',
                            'time' => '2:00 PM - 5:00 PM',
                            'location' => 'TRA Training Center',
                            'attendees' => 25,
                            'capacity' => 30,
                            'is_free' => false,
                            'price' => 50000
                        ],
                        [
                            'title' => 'Inter-School Safety Quiz Competition',
                            'category' => 'competition',
                            'description' => 'Test your road safety knowledge and compete for exciting prizes in this inter-school competition.',
                            'date' => '02',
                            'month' => 'Apr',
                            'time' => '10:00 AM - 4:00 PM',
                            'location' => 'Mlimani City Hall',
                            'attendees' => 200,
                            'capacity' => 300,
                            'is_free' => true
                        ],
                        [
                            'title' => 'Road Safety Technology Seminar',
                            'category' => 'seminar',
                            'description' => 'Explore the latest technologies in road safety, including smart traffic systems and vehicle safety features.',
                            'date' => '05',
                            'month' => 'Apr',
                            'time' => '1:00 PM - 5:00 PM',
                            'location' => 'COSTECH Building',
                            'attendees' => 80,
                            'capacity' => 150,
                            'is_free' => false,
                            'price' => 25000
                        ],
                        [
                            'title' => 'First Aid Training for Road Accidents',
                            'category' => 'training',
                            'description' => 'Essential first aid skills training specifically for road accident scenarios. Certificate provided.',
                            'date' => '10',
                            'month' => 'Apr',
                            'time' => '9:00 AM - 1:00 PM',
                            'location' => 'Muhimbili Hospital',
                            'attendees' => 30,
                            'capacity' => 40,
                            'is_free' => false,
                            'price' => 35000
                        ]
                    ];
                @endphp
                
                @foreach($defaultEvents as $event)
                    <div class="event-card bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden" data-category="{{ $event['category'] }}">
                        <div class="relative">
                            <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300"></div>
                            <div class="absolute top-4 left-4">
                                <span class="bg-tra-yellow text-tra-black px-3 py-1 rounded-full text-xs font-bold uppercase">
                                    {{ ucfirst($event['category']) }}
                                </span>
                            </div>
                            <div class="absolute top-4 right-4">
                                <div class="bg-white rounded-lg p-2 text-center shadow-lg">
                                    <div class="text-2xl font-bold text-tra-black">{{ $event['date'] }}</div>
                                    <div class="text-xs text-gray-600 uppercase">{{ $event['month'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-tra-black mb-2 line-clamp-2">
                                {{ $event['title'] }}
                            </h3>
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ $event['description'] }}
                            </p>
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $event['time'] }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $event['location'] }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                    <span>{{ $event['attendees'] }} / {{ $event['capacity'] }} Attendees</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold {{ $event['is_free'] ? 'text-green-600' : 'text-tra-black' }}">
                                    {{ $event['is_free'] ? 'Free Event' : 'TSH ' . number_format($event['price']) }}
                                </span>
                                <a href="#" class="bg-tra-yellow text-tra-black px-4 py-2 rounded-lg font-semibold text-sm hover:bg-yellow-400 transition-colors duration-300">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>

        <!-- Events List View (Hidden by default) -->
        <div id="events-list" class="hidden space-y-4 animate-fade-in-up">
            @forelse($events ?? [] as $event)
                <div class="event-list-item bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 p-6" data-category="{{ $event->category ?? 'workshop' }}">
                    <div class="flex flex-col lg:flex-row gap-6">
                        <div class="lg:w-48 flex-shrink-0">
                            <img 
                                src="{{ asset($event->image ?? 'images/default-event.jpg') }}" 
                                alt="{{ $event->title }}"
                                class="w-full h-32 lg:h-full object-cover rounded-lg"
                            >
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-start justify-between gap-4 mb-3">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="bg-tra-yellow text-tra-black px-3 py-1 rounded-full text-xs font-bold uppercase">
                                            {{ $event->category ?? 'Workshop' }}
                                        </span>
                                        <span class="text-sm font-semibold {{ $event->is_free ?? true ? 'text-green-600' : 'text-tra-black' }}">
                                            {{ $event->is_free ?? true ? 'Free Event' : 'TSH ' . number_format($event->price ?? 0) }}
                                        </span>
                                    </div>
                                    <h3 class="text-xl font-bold text-tra-black mb-2">
                                        {{ $event->title }}
                                    </h3>
                                </div>
                                <div class="bg-gray-100 rounded-lg p-3 text-center">
                                    <div class="text-2xl font-bold text-tra-black">{{ $event->day ?? '15' }}</div>
                                    <div class="text-sm text-gray-600 uppercase">{{ $event->month ?? 'Mar' }}</div>
                                </div>
                            </div>
                            <p class="text-gray-600 mb-4">
                                {{ $event->description }}
                            </p>
                            <div class="flex flex-wrap gap-6 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $event->time ?? '9:00 AM - 5:00 PM' }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $event->location ?? 'UDSM Main Campus' }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                    <span>{{ $event->attendees ?? '50' }} / {{ $event->capacity ?? '100' }} Attendees</span>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('events.show', $event->id ?? 1) }}" class="bg-tra-yellow text-tra-black px-5 py-2 rounded-lg font-semibold hover:bg-yellow-400 transition-colors duration-300">
                                    View Details
                                </a>
                                <button class="border border-gray-300 text-gray-700 px-5 py-2 rounded-lg font-semibold hover:border-tra-yellow hover:text-tra-yellow transition-colors duration-300">
                                    Share Event
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Default List Items using same data -->
                @foreach($defaultEvents as $event)
                    <div class="event-card bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden" data-category="{{ $event['category'] }}">
                        <div class="relative">
                            <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300"></div>
                            <div class="absolute top-4 left-4">
                                <span class="bg-tra-yellow text-tra-black px-3 py-1 rounded-full text-xs font-bold uppercase">
                                    {{ ucfirst($event['category']) }}
                                </span>
                            </div>
                            <div class="absolute top-4 right-4">
                                <div class="bg-white rounded-lg p-2 text-center shadow-lg">
                                    <div class="text-2xl font-bold text-tra-black">{{ $event['date'] }}</div>
                                    <div class="text-xs text-gray-600 uppercase">{{ $event['month'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-tra-black mb-2 line-clamp-2">
                                {{ $event['title'] }}
                            </h3>
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ $event['description'] }}
                            </p>
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $event['time'] }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $event['location'] }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                    <span>{{ $event['attendees'] }} / {{ $event['capacity'] }} Attendees</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold {{ $event['is_free'] ? 'text-green-600' : 'text-tra-black' }}">
                                    {{ $event['is_free'] ? 'Free Event' : 'TSH ' . number_format($event['price']) }}
                                </span>
                                <a href="#" class="bg-tra-yellow text-tra-black px-4 py-2 rounded-lg font-semibold text-sm hover:bg-yellow-400 transition-colors duration-300">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

            @endforelse
        </div>
        </div>
    </section>
    
                
                




        <!-- Footer Component -->
        <footer class="black-gradient text-tra-yellow py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Company Info -->
                <div class="sm:col-span-2 lg:col-span-1 animate-fade-in-up">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-tra-yellow rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-tra-black" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L3 7v10c0 5.55 3.84 9.739 9 9.739S21 22.55 21 17V7l-9-5zm0 2.236L19 8.236v8.764c0 4.45-3.05 7.764-7 7.764s-7-3.314-7-7.764V8.236l7-4zm0 2.764c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xl sm:text-2xl font-bold">TRA CLUBS</div>
                            <div class="text-xs sm:text-sm text-tra-yellow/70">Management System</div>
                        </div>
                    </div>
                    <p class="text-tra-yellow/80 mb-4 sm:mb-6 leading-relaxed text-sm sm:text-base">
                        Connecting traffic safety clubs across Tanzania for a safer tomorrow through education, innovation, and community engagement.
                    </p>
                    <div class="flex space-x-3 sm:space-x-4">
                        <a href="#" class="w-8 h-8 sm:w-10 sm:h-10 bg-tra-yellow/20 rounded-lg flex items-center justify-center text-tra-yellow hover:bg-tra-yellow hover:text-tra-black transition-all duration-300">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 sm:w-10 sm:h-10 bg-tra-yellow/20 rounded-lg flex items-center justify-center text-tra-yellow hover:bg-tra-yellow hover:text-tra-black transition-all duration-300">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 sm:w-10 sm:h-10 bg-tra-yellow/20 rounded-lg flex items-center justify-center text-tra-yellow hover:bg-tra-yellow hover:text-tra-black transition-all duration-300">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.347-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.748-1.378 0 0-.599 2.282-.744 2.840-.282 1.084-1.064 2.456-1.549 3.235C9.584 23.815 10.77 24.001 12.017 24.001c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 sm:w-10 sm:h-10 bg-tra-yellow/20 rounded-lg flex items-center justify-center text-tra-yellow hover:bg-tra-yellow hover:text-tra-black transition-all duration-300">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="animate-fade-in-up" style="animation-delay: 0.1s;">
                    <h3 class="text-lg font-bold mb-4 sm:mb-6 text-tra-yellow">Quick Links</h3>
                    <ul class="space-y-2 sm:space-y-3">
                        <li>
                            <a href="#institutions" class="text-tra-yellow/80 hover:text-tra-yellow transition-colors duration-300 flex items-center space-x-2 text-sm sm:text-base">
                                <span class="text-xs">→</span>
                                <span>Partner Institutions</span>
                            </a>
                        </li>
                        <li>
                            <a href="#events" class="text-tra-yellow/80 hover:text-tra-yellow transition-colors duration-300 flex items-center space-x-2 text-sm sm:text-base">
                                <span class="text-xs">→</span>
                                <span>Upcoming Events</span>
                            </a>
                        </li>
                        <li>
                            <a href="#funding" class="text-tra-yellow/80 hover:text-tra-yellow transition-colors duration-300 flex items-center space-x-2 text-sm sm:text-base">
                                <span class="text-xs">→</span>
                                <span>Funding Opportunities</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-tra-yellow/80 hover:text-tra-yellow transition-colors duration-300 flex items-center space-x-2 text-sm sm:text-base">
                                <span class="text-xs">→</span>
                                <span>Safety Resources</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-tra-yellow/80 hover:text-tra-yellow transition-colors duration-300 flex items-center space-x-2 text-sm sm:text-base">
                                <span class="text-xs">→</span>
                                <span>Training Materials</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Resources -->
                <div class="animate-fade-in-up" style="animation-delay: 0.2s;">
                    <h3 class="text-lg font-bold mb-4 sm:mb-6 text-tra-yellow">Resources</h3>
                    <ul class="space-y-2 sm:space-y-3">
                        <li>
                            <a href="#" class="text-tra-yellow/80 hover:text-tra-yellow transition-colors duration-300 flex items-center space-x-2 text-sm sm:text-base">
                                <span>📖</span>
                                <span>Safety Guidelines</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-tra-yellow/80 hover:text-tra-yellow transition-colors duration-300 flex items-center space-x-2 text-sm sm:text-base">
                                <span>🎓</span>
                                <span>Training Programs</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-tra-yellow/80 hover:text-tra-yellow transition-colors duration-300 flex items-center space-x-2 text-sm sm:text-base">
                                <span>📊</span>
                                <span>Research Reports</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-tra-yellow/80 hover:text-tra-yellow transition-colors duration-300 flex items-center space-x-2 text-sm sm:text-base">
                                <span>💡</span>
                                <span>Best Practices</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-tra-yellow/80 hover:text-tra-yellow transition-colors duration-300 flex items-center space-x-2 text-sm sm:text-base">
                                <span>📱</span>
                                <span>Mobile App</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="animate-fade-in-up" style="animation-delay: 0.3s;">
                    <h3 class="text-lg font-bold mb-4 sm:mb-6 text-tra-yellow">Contact</h3>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-tra-yellow/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-tra-yellow" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                            <span class="text-tra-yellow/80 text-sm sm:text-base">info@traclubs.tz</span>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-tra-yellow/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-tra-yellow" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                            </div>
                            <span class="text-tra-yellow/80 text-sm sm:text-base">+255 123 456 789</span>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-tra-yellow/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-tra-yellow" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-tra-yellow/80 text-sm sm:text-base">Dar es Salaam, Tanzania</span>
                        </div>
                    </div>
                    
                    <!-- Newsletter Signup -->
                    <div class="mt-6 p-3 sm:p-4 bg-tra-yellow/10 rounded-xl">
                        <h4 class="font-bold text-tra-yellow mb-2 text-sm sm:text-base">Newsletter</h4>
                        <p class="text-tra-yellow/80 text-xs sm:text-sm mb-3">Stay updated with latest news</p>
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <input 
                                type="email" 
                                placeholder="Your email" 
                                class="flex-1 bg-tra-black/50 border border-tra-yellow/30 text-tra-yellow placeholder-tra-yellow/50 px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-tra-yellow focus:ring-2 focus:ring-tra-yellow/20"
                            >
                            <button class="bg-tra-yellow text-tra-black px-4 py-2 rounded-lg text-sm font-bold hover:bg-yellow-400 transition-colors duration-300 whitespace-nowrap">
                                Subscribe
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="border-t border-tra-yellow/20 mt-8 sm:mt-12 pt-6 sm:pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <p class="text-tra-yellow/70 text-sm sm:text-base text-center md:text-left">
                        &copy; 2025 TRA Clubs Management System. All rights reserved.
                    </p>
                    <div class="flex flex-wrap justify-center md:justify-end space-x-4 sm:space-x-6 text-xs sm:text-sm">
                        <a href="#" class="text-tra-yellow/70 hover:text-tra-yellow transition-colors duration-300">Privacy Policy</a>
                        <a href="#" class="text-tra-yellow/70 hover:text-tra-yellow transition-colors duration-300">Terms of Service</a>
                        <a href="#" class="text-tra-yellow/70 hover:text-tra-yellow transition-colors duration-300">Cookie Policy</a>
                        <a href="#" class="text-tra-yellow/70 hover:text-tra-yellow transition-colors duration-300">Support</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>







    <!-- JavaScript for Dynamic Slideshow and Animations -->
    <script>
        // Slideshow functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide-image');
        const indicators = document.querySelectorAll('.slide-indicator');
        const totalSlides = slides.length;
        
        // Auto-advance slideshow
        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            indicators[currentSlide].classList.remove('active');
            
            currentSlide = (currentSlide + 1) % totalSlides;
            
            slides[currentSlide].classList.add('active');
            indicators[currentSlide].classList.add('active');
        }
        
        // Set interval for auto-advance (5 seconds)
        let slideInterval = setInterval(nextSlide, 5000);
        
        // Manual slide navigation
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                clearInterval(slideInterval);
                
                slides[currentSlide].classList.remove('active');
                indicators[currentSlide].classList.remove('active');
                
                currentSlide = index;
                
                slides[currentSlide].classList.add('active');
                indicators[currentSlide].classList.add('active');
                
                // Restart auto-advance
                slideInterval = setInterval(nextSlide, 5000);
            });
        });
        
        // Counter animation
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2000; // 2 seconds
            const increment = target / (duration / 16); // 60fps
            let current = 0;
            
            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    element.textContent = Math.floor(current).toLocaleString();
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target.toLocaleString();
                }
            };
            
            updateCounter();
        }
        
        // Intersection Observer for counter animation
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && entry.target.classList.contains('counter')) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        // Observe all counters
        document.querySelectorAll('.counter').forEach(counter => {
            observer.observe(counter);
        });
        
        // Smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Parallax effect on scroll
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.slide-image.active img');
            
            parallaxElements.forEach(element => {
                const speed = 0.5;
                element.style.transform = `translateY(${scrolled * speed}px) scale(1.1)`;
            });
        });
        
        // Add navbar scroll effect (if integrated with navbar)
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar-hero');
            if (navbar) {
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
            }
        });
    </script>

</body>
</html>