<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Tax Clubs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        .gradient-bg {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #000000 100%);
        }
        
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .glow-effect {
            box-shadow: 0 0 20px rgba(255, 193, 7, 0.3);
        }
        
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
            border-color: #fbbf24;
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .step-indicator {
            transition: all 0.3s ease;
        }
        
        .step-indicator.active {
            background-color: #fbbf24;
            color: #000;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left Side - Image and Content -->
        <!-- <div class="hidden lg:flex lg:w-1/2 gradient-bg relative overflow-hidden"> -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden" style="background-image: url({{ asset('/image/freepik_assistant_1750630100358.png') }}); background-size: cover; background-position: center;">

            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            
            <!-- Floating Elements -->
            <div class="absolute top-20 left-20 w-32 h-32 bg-yellow-400 rounded-full opacity-10 floating-animation"></div>
            <div class="absolute bottom-32 right-16 w-24 h-24 bg-yellow-400 rounded-full opacity-20 floating-animation" style="animation-delay: -2s;"></div>
            <div class="absolute top-1/2 left-1/3 w-16 h-16 bg-yellow-400 rounded-full opacity-15 floating-animation" style="animation-delay: -4s;"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center items-center w-full px-12 text-center">
            <div class="mb-8">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-6 mx-auto glow-effect overflow-hidden">
                        <img src="{{ asset('/logo/traIcon-removebg-preview.png') }}" alt="Tax Clubs Logo" class="w-16 h-16 object-contain" />
                    </div>
                </div>
                
                <h1 class="text-4xl font-bold text-white mb-4">
                    Join
                    <span class="text-yellow-400">Tax Clubs</span>
                </h1>
                
                <p class="text-gray-300 text-lg mb-8 max-w-md leading-relaxed">
                    Start your journey with us today. Get access to professional tax services, 
                    expert advice, and comprehensive financial planning tools.
                </p>
                
                <div class="space-y-4 text-gray-400">
                    <div class="flex items-center justify-center">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full mr-3"></div>
                        <span class="text-sm">Free Account Setup</span>
                    </div>
                    <div class="flex items-center justify-center">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full mr-3"></div>
                        <span class="text-sm">Expert Tax Consultation</span>
                    </div>
                    <div class="flex items-center justify-center">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full mr-3"></div>
                        <span class="text-sm">24/7 Support Access</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12">
            <div class="w-full max-w-full">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/>
                            <path d="M8 12h8v2H8zm0 3h8v2H8zm0-6h5v2H8z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Tax Clubs</h2>
                </div>

                <div class="bg-white p-8 rounded-2xl  border border-gray-100">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h2>
                        <p class="text-gray-600">Join thousands of satisfied tax clients</p>
                    </div>


                    <x-validation-errors class="mb-4" />


                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf
                        <!-- Name Fields Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    First Name
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <input 
                                        type="text" 
                                        id="first_name" 
                                        name="first_name" 
                                        required
                                        class="input-focus block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition duration-200"
                                        placeholder="John"
                                    />
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Last Name
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <input 
                                        type="text" 
                                        id="last_name" 
                                        name="last_name" 
                                        required
                                        class="input-focus block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition duration-200"
                                        placeholder="Doe"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    required
                                    class="input-focus block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition duration-200"
                                    placeholder="john.doe@example.com"
                                />
                            </div>
                        </div>

                        <!-- Phone Field -->
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                       
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    required
                                    class="input-focus block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition duration-200"
                                    placeholder="+1 (555) 123-4567"
                                />
                            </div>
                        </div>




                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                               Institutions
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <select 
                                
                                    name="institution_id" 
                                    required
                                    class="input-focus block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition duration-200"
                                   
                                >

                                <option>  select institution </option>

                                @foreach (DB::table('institutions')->get() as $institutions)
                                
                                <option                                     class="input-focus block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition duration-200"
                                value="{{ $institutions->id }}">  {{ $institutions->name }}</option>
                                @endforeach



                                </select>
                            </div>
                        </div>




                          
                        </div>

                        <!-- Password Fields Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <input 
                                        type="password" 
                                        id="password" 
                                        name="password" 
                                        required
                                        class="input-focus block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition duration-200"
                                        placeholder="Create password"
                                    />
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <input 
                                        type="password" 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        required
                                        class="input-focus block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition duration-200"
                                        placeholder="Confirm password"
                                    />
                                </div>
                            </div>
                        </div>

                       

                        <!-- Terms and Conditions -->
                        <div class="flex items-start">
                            <div class="flex items-center h-6">
                                <input 
                                    id="terms" 
                                    name="terms" 
                                    type="checkbox" 
                                    required
                                    class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded"
                                />
                            </div>
                            <div class="ml-3">
                                <label for="terms" class="text-sm text-gray-700">
                                    I agree to the 
                                    <a href="#" class="text-yellow-600 hover:text-yellow-500 font-medium">Terms of Service</a> 
                                    and 
                                    <a href="#" class="text-yellow-600 hover:text-yellow-500 font-medium">Privacy Policy</a>
                                </label>
                            </div>
                        </div>

                        <!-- Newsletter Subscription -->
                        <div class="flex items-start">
                            <div class="flex items-center h-6">
                                <input 
                                    id="newsletter" 
                                    name="newsletter" 
                                    type="checkbox" 
                                    class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded"
                                />
                            </div>
                            <div class="ml-3">
                                <label for="newsletter" class="text-sm text-gray-700">
                                    Subscribe to our newsletter for tax tips and updates
                                </label>
                            </div>
                        </div>

                        <!-- Register Button -->
                        <button 
                            type="submit" 
                            class="btn-hover w-full bg-black text-white py-3 px-4 rounded-lg font-semibold text-lg transition duration-300 ease-in-out transform hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2"
                        >
                            Create Account
                        </button>

                        <!-- Divider -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">or</span>
                            </div>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="text-gray-600">
                                Already have an account? 
                                <a href="login" class="font-semibold text-yellow-600 hover:text-yellow-500 transition duration-200">
                                    Sign In
                                </a>
                            </p>
                        </div>
                    </form>

                    <!-- Footer -->
                    <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                        <p class="text-xs text-gray-500 mb-2">
                            Your data is protected with enterprise-grade security
                        </p>
                        <div class="flex items-center justify-center space-x-4">
                            <div class="flex items-center text-xs text-gray-400">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                256-bit SSL
                            </div>
                            <div class="flex items-center text-xs text-gray-400">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Data Protected
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>