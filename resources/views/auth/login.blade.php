<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tax Clubs</title>
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
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left Side - Image and Content -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden" style="background-image: url({{ asset('/image/african-american-team-comparing-class-notes-doing-research-library.jpg') }}); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
            
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
                    Welcome to
                    <span class="text-yellow-400">Tax Clubs</span>
                </h1>
                
                <p class="text-gray-300 text-lg mb-8 max-w-md leading-relaxed">
                    Your trusted partner in tax management and financial planning. 
                    Streamline your tax processes with our comprehensive platform.
                </p>
                
                <div class="space-y-4 text-gray-400">
                    <div class="flex items-center justify-center">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full mr-3"></div>
                        <span class="text-sm">Secure Tax Document Management</span>
                    </div>
                    <div class="flex items-center justify-center">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full mr-3"></div>
                        <span class="text-sm">Professional Tax Advisory</span>
                    </div>
                    <div class="flex items-center justify-center">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full mr-3"></div>
                        <span class="text-sm">Real-time Tax Updates</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
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
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Sign In</h2>
                        <p class="text-gray-600">Access your Tax Clubs account</p>
                    </div>

                    <form method="POST" action="login" class="space-y-6">
                        @csrf
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
                                    placeholder="Enter your email address"
                                />
                            </div>
                        </div>

                        <!-- Password Field -->
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
                                    placeholder="Enter your password"
                                />
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input 
                                    id="remember" 
                                    name="remember" 
                                    type="checkbox" 
                                    class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded"
                                />
                                <label for="remember" class="ml-2 block text-sm text-gray-700">
                                    Remember me
                                </label>
                            </div>
                            <a href="forgot-password" class="text-sm text-yellow-600 hover:text-yellow-500 font-medium transition duration-200">
                                Forgot password?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button 
                            type="submit" 
                            class="btn-hover w-full bg-black text-white py-3 px-4 rounded-lg font-semibold text-lg transition duration-300 ease-in-out transform hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2"
                        >
                            Sign In
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

                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="text-gray-600">
                                Don't have an account? 
                                <a href="register" class="font-semibold text-yellow-600 hover:text-yellow-500 transition duration-200">
                                    Create Account
                                </a>
                            </p>
                        </div>
                    </form>

                    <!-- Footer -->
                    <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                        <p class="text-xs text-gray-500">
                            Protected by enterprise-grade security
                        </p>
                        <div class="flex items-center justify-center mt-2 space-x-4">
                            <div class="flex items-center text-xs text-gray-400">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                SSL Encrypted
                            </div>
                            <div class="flex items-center text-xs text-gray-400">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                GDPR Compliant
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>