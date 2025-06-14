<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRA Clubs Management System</title>
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
                        'fade-in-up': 'fadeInUp 0.8s ease-out',
                        'fade-in-left': 'fadeInLeft 0.8s ease-out',
                        'fade-in-right': 'fadeInRight 0.8s ease-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .yellow-gradient {
            background: linear-gradient(135deg, #F9E510 0%, #fff3a0 50%, #F9E510 100%);
        }
        
        .black-gradient {
            background: linear-gradient(135deg, #000000 0%, #2c3e50 50%, #000000 100%);
        }
        
        .mobile-menu-hidden {
            transform: translateY(-100%);
            opacity: 0;
        }
        
        .mobile-menu-visible {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body class="bg-white font-sans">
    
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



  <!-- Main Header/Hero Section -->
  <header id="home" class="pt-28 sm:pt-32 h-[70vh] sm:h-[80vh] relative overflow-hidden">
        <!-- Black Gradient Overlay (Left Side) -->
        <div class="absolute inset-0 bg-gradient-to-r from-tra-black via-tra-black/85 via-55% to-transparent lg:via-45% z-10"></div>
        
        <!-- Animated Sliding Background Images - Events/Activities/Announcements -->
        <div class="absolute inset-0 overflow-hidden">
            <!-- Event Cards with Sliding Animation -->
            <div class="absolute top-12 right-8 w-28 h-20 sm:w-36 sm:h-24 bg-tra-yellow rounded-xl flex flex-col items-center justify-center shadow-2xl p-2 slide-left animate-float" style="animation-delay: 0.5s;">
                <div class="text-lg sm:text-2xl mb-1">📅</div>
                <div class="text-xs font-bold text-tra-black text-center leading-tight">Safety Workshop<br><span class="text-xs opacity-75">June 25</span></div>
            </div>
            
            <div class="absolute top-1/4 right-1/5 w-24 h-18 sm:w-32 sm:h-20 bg-white rounded-xl flex flex-col items-center justify-center shadow-xl p-2 slide-right animate-float" style="animation-delay: 1.2s;">
                <div class="text-lg sm:text-xl mb-1">🏆</div>
                <div class="text-xs font-bold text-tra-black text-center leading-tight">Competition<br><span class="text-xs opacity-75">July 2</span></div>
            </div>
            
            <div class="absolute top-1/2 right-1/3 w-32 h-22 sm:w-40 sm:h-26 bg-tra-yellow rounded-xl flex flex-col items-center justify-center shadow-2xl p-2 slide-up animate-float" style="animation-delay: 1.8s;">
                <div class="text-lg sm:text-2xl mb-1">📢</div>
                <div class="text-xs font-bold text-tra-black text-center leading-tight">New Funding<br><span class="text-xs opacity-75">Available Now</span></div>
            </div>
            
            <div class="absolute bottom-1/4 right-1/6 w-26 h-20 sm:w-34 sm:h-22 bg-white rounded-xl flex flex-col items-center justify-center shadow-xl p-2 slide-down animate-float" style="animation-delay: 2.4s;">
                <div class="text-lg sm:text-xl mb-1">🎯</div>
                <div class="text-xs font-bold text-tra-black text-center leading-tight">Outreach<br><span class="text-xs opacity-75">Kibaha</span></div>
            </div>
            
            <div class="absolute bottom-12 right-12 w-24 h-18 sm:w-30 sm:h-20 bg-tra-yellow rounded-xl flex flex-col items-center justify-center shadow-2xl p-2 slide-left animate-float" style="animation-delay: 3s;">
                <div class="text-lg sm:text-xl mb-1">🚗</div>
                <div class="text-xs font-bold text-tra-black text-center leading-tight">Driver Training<br><span class="text-xs opacity-75">UDSM</span></div>
            </div>
            
            <!-- Additional Sliding Activity Cards -->
            <div class="absolute top-20 right-1/2 w-20 h-16 sm:w-24 sm:h-18 bg-green-400 rounded-xl flex flex-col items-center justify-center shadow-xl p-2 slide-right animate-float" style="animation-delay: 3.6s;">
                <div class="text-sm sm:text-lg mb-1">✅</div>
                <div class="text-xs font-bold text-white text-center leading-tight">Registration<br><span class="text-xs opacity-90">Open</span></div>
            </div>
            
            <div class="absolute bottom-20 right-1/4 w-22 h-16 sm:w-28 sm:h-18 bg-blue-500 rounded-xl flex flex-col items-center justify-center shadow-xl p-2 slide-up animate-float" style="animation-delay: 4.2s;">
                <div class="text-sm sm:text-lg mb-1 text-white">📋</div>
                <div class="text-xs font-bold text-white text-center leading-tight">Apply Now<br><span class="text-xs opacity-90">Deadline Soon</span></div>
            </div>
            
            <!-- Moving Notification Indicators -->
            <div class="absolute top-16 right-1/4 w-3 h-3 sm:w-4 sm:h-4 bg-red-500 rounded-full animate-ping slide-down" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/3 right-1/6 w-2 h-2 sm:w-3 sm:h-3 bg-green-500 rounded-full animate-ping slide-left" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-1/3 right-1/4 w-3 h-3 sm:w-4 sm:h-4 bg-blue-500 rounded-full animate-ping slide-right" style="animation-delay: 3s;"></div>
            <div class="absolute top-1/2 right-1/8 w-2 h-2 sm:w-3 sm:h-3 bg-yellow-400 rounded-full animate-ping slide-up" style="animation-delay: 4s;"></div>
            
            <div class="absolute top-1/2 right-8 flex items-center space-x-1 slide-left animate-float" style="animation-delay: 2.5s;">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-xs text-white/80 font-medium">Live Event</span>
            </div>
            
            <div class="absolute bottom-1/2 right-2 flex items-center space-x-1 slide-right animate-float" style="animation-delay: 3.5s;">
                <div class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></div>
                <span class="text-xs text-white/80 font-medium">Urgent</span>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="relative z-20 h-full flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                
                <!-- Left Content -->
                <div class="text-left">
                    <div class="mb-3 sm:mb-4 animate-fade-in-left">
                        <span class="bg-tra-yellow text-tra-black px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-bold uppercase tracking-wider">
                            Traffic Safety Excellence
                        </span>
                    </div>
                    
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-white mb-3 sm:mb-4 animate-fade-in-left" style="animation-delay: 0.2s;">
                        TRA CLUBS
                        <span class="block text-tra-yellow">Management</span>
                        <span class="block text-white text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl font-light">System</span>
                    </h1>
                    
                    <p class="text-sm sm:text-base md:text-lg text-gray-300 mb-4 sm:mb-6 max-w-lg animate-fade-in-left leading-relaxed" style="animation-delay: 0.4s;">
                        Empowering Traffic Safety Education Through Connected Learning Communities Across Schools and Universities in Tanzania
                    </p>
                    
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 lg:space-x-6 animate-fade-in-left" style="animation-delay: 0.6s;">
                        <a href="#institutions" class="bg-tra-yellow text-tra-black px-5 sm:px-6 py-2.5 sm:py-3 rounded-full font-bold text-sm sm:text-base hover:bg-yellow-400 hover:scale-105 transition-all duration-300 shadow-xl hover:shadow-2xl text-center">
                            Explore Institutions
                        </a>
                        <a href="#events" class="border-2 border-tra-yellow text-tra-yellow px-5 sm:px-6 py-2.5 sm:py-3 rounded-full font-bold text-sm sm:text-base hover:bg-tra-yellow hover:text-tra-black transition-all duration-300 text-center">
                            View Events
                        </a>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="flex flex-wrap gap-4 sm:gap-6 mt-6 sm:mt-8 animate-fade-in-left" style="animation-delay: 0.8s;">
                        <div class="text-center">
                            <div class="text-lg sm:text-xl md:text-2xl font-bold text-tra-yellow">125+</div>
                            <div class="text-xs sm:text-sm text-gray-400 uppercase tracking-wider">Active Clubs</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg sm:text-xl md:text-2xl font-bold text-tra-yellow">15K+</div>
                            <div class="text-xs sm:text-sm text-gray-400 uppercase tracking-wider">Members</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg sm:text-xl md:text-2xl font-bold text-tra-yellow">89+</div>
                            <div class="text-xs sm:text-sm text-gray-400 uppercase tracking-wider">Institutions</div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Content (Enhanced Activity Dashboard) - Hidden on Mobile -->
                <div class="relative lg:block hidden animate-fade-in-right">
                    <div class="relative">
                        <!-- Main Activity Dashboard -->
                        <div class="w-full h-64 xl:h-80 bg-white/10 backdrop-blur-sm rounded-3xl p-4 xl:p-6 shadow-2xl border border-white/20">
                            <div class="h-full bg-gradient-to-br from-tra-yellow/20 to-white/20 rounded-2xl p-3 flex flex-col">
                                <div class="text-center mb-4">
                                    <div class="text-3xl xl:text-4xl mb-2">📊</div>
                                    <h3 class="text-base xl:text-lg font-bold text-white mb-1">Live Dashboard</h3>
                                    <p class="text-gray-300 text-xs">Real-time Activity Monitor</p>
                                </div>
                                
                                <!-- Activity Feed -->
                                <div class="space-y-2 flex-1">
                                    <div class="bg-white/20 rounded-lg p-2 flex items-center space-x-2 slide-right" style="animation-delay: 1s;">
                                        <div class="w-6 h-6 bg-tra-yellow rounded-full flex items-center justify-center text-xs">📅</div>
                                        <div class="flex-1">
                                            <div class="text-white text-xs font-medium">New Workshop</div>
                                            <div class="text-gray-300 text-xs">UDSM - 45 registered</div>
                                        </div>
                                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                    </div>
                                    
                                    <div class="bg-white/20 rounded-lg p-2 flex items-center space-x-2 slide-left" style="animation-delay: 1.5s;">
                                        <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-xs">🏆</div>
                                        <div class="flex-1">
                                            <div class="text-white text-xs font-medium">Competition Alert</div>
                                            <div class="text-gray-300 text-xs">12 teams participating</div>
                                        </div>
                                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                                    </div>
                                    
                                    <div class="bg-white/20 rounded-lg p-2 flex items-center space-x-2 slide-up" style="animation-delay: 2s;">
                                        <div class="w-6 h-6 bg-tra-yellow rounded-full flex items-center justify-center text-xs">💰</div>
                                        <div class="flex-1">
                                            <div class="text-white text-xs font-medium">Funding Released</div>
                                        </div>
                                        <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Floating Activity Cards with Sliding -->
                        <div class="absolute -top-3 -right-3 w-16 h-12 xl:w-20 xl:h-16 bg-tra-yellow rounded-2xl p-2 shadow-xl animate-float slide-down flex flex-col items-center justify-center" style="animation-delay: 2.5s;">
                            <div class="text-sm xl:text-base mb-1">🎯</div>
                            <div class="text-xs font-bold text-tra-black text-center">Active</div>
                        </div>
                        
                        <div class="absolute -bottom-3 -left-3 w-16 h-12 xl:w-20 xl:h-16 bg-white rounded-2xl p-2 shadow-xl animate-float slide-up flex flex-col items-center justify-center" style="animation-delay: 3s;">
                            <div class="text-sm xl:text-base mb-1">⚡</div>
                            <div class="text-xs font-bold text-tra-black text-center">Fast</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Enhanced Scroll Indicator -->
        <div class="absolute bottom-2 sm:bottom-4 left-1/2 transform -translate-x-1/2 animate-bounce z-20">
            <div class="bg-tra-yellow rounded-full p-2 shadow-lg hover:shadow-xl transition-shadow duration-300 cursor-pointer">
                <svg class="w-4 h-4 text-tra-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
        </div>
    </header>
                                    


    <!-- Main Content Placeholder -->
    <main class="min-h-screen bg-gray-50 flex items-center justify-center">
        <div class="text-center px-4">
            

        @yield('content')

            <p class="text-gray-500 mt-4">This is the main content area. Replace this with your actual content.</p>
        </div>
    </main>

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

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle functionality
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            const menuIcon = document.getElementById('menuIcon');
            const closeIcon = document.getElementById('closeIcon');
            
            if (mobileMenu.classList.contains('mobile-menu-hidden')) {
                // Show menu
                mobileMenu.classList.remove('mobile-menu-hidden');
                mobileMenu.classList.add('mobile-menu-visible');
                menuIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
                
                // Prevent body scroll when menu is open
                document.body.style.overflow = 'hidden';
            } else {
                // Hide menu
                mobileMenu.classList.remove('mobile-menu-visible');
                mobileMenu.classList.add('mobile-menu-hidden');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                
                // Restore body scroll
                document.body.style.overflow = 'auto';
            }
        }

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    // Close mobile menu if open
                    const mobileMenu = document.getElementById('mobileMenu');
                    if (mobileMenu && !mobileMenu.classList.contains('mobile-menu-hidden')) {
                        toggleMobileMenu();
                    }
                    
                    // Smooth scroll to target
                    const navHeight = 80; // Account for fixed navbar
                    const targetPosition = target.offsetTop - navHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            const mobileMenu = document.getElementById('mobileMenu');
            const menuButton = e.target.closest('button[onclick="toggleMobileMenu()"]');
            
            if (!menuButton && !mobileMenu.contains(e.target) && !mobileMenu.classList.contains('mobile-menu-hidden')) {
                toggleMobileMenu();
            }
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0) translateX(0)';
                }
            });
        }, observerOptions);

        // Observe all animated elements
        document.querySelectorAll('.animate-fade-in-up, .animate-fade-in-left, .animate-fade-in-right').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            observer.observe(el);
        });

        // Navbar scroll effect
        let lastScroll = 0;
        const navbar = document.querySelector('nav');

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll <= 0) {
                navbar.classList.remove('shadow-2xl');
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
                navbar.classList.add('shadow-2xl');
            }
            
            lastScroll = currentScroll;
        });

        // Add loading animation
        window.addEventListener('load', () => {
            document.body.classList.add('loaded');
        });

        // Handle window resize for mobile menu
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) { // lg breakpoint
                const mobileMenu = document.getElementById('mobileMenu');
                if (!mobileMenu.classList.contains('mobile-menu-hidden')) {
                    toggleMobileMenu();
                }
            }
        });
    </script>


</body>
</html>