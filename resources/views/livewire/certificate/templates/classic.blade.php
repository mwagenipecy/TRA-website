<div>
<div class="certificate-classic bg-gradient-to-br from-amber-50 to-yellow-100 border-4 border-amber-600 max-w-4xl mx-auto relative">
    {{-- Decorative corners --}}
    <div class="absolute top-0 left-0 w-16 h-16 border-l-4 border-t-4 border-amber-600"></div>
    <div class="absolute top-0 right-0 w-16 h-16 border-r-4 border-t-4 border-amber-600"></div>
    <div class="absolute bottom-0 left-0 w-16 h-16 border-l-4 border-b-4 border-amber-600"></div>
    <div class="absolute bottom-0 right-0 w-16 h-16 border-r-4 border-b-4 border-amber-600"></div>
    
    {{-- Decorative pattern overlay --}}
    <div class="absolute inset-0 opacity-5">
        <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="classicPattern" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1" fill="currentColor"/>
                    <circle cx="5" cy="5" r="0.5" fill="currentColor"/>
                    <circle cx="15" cy="15" r="0.5" fill="currentColor"/>
                </pattern>
            </defs>
            <rect width="100" height="100" fill="url(#classicPattern)" />
        </svg>
    </div>
    
    <div class="relative p-12">
        {{-- Classic Header --}}
        <div class="text-center mb-12">
            <div class="decorative-border border-4 border-double border-amber-600 p-6 mb-6 bg-white bg-opacity-50 rounded-lg">
                <h1 class="text-6xl font-serif text-amber-800 mb-4">Certificate</h1>
                <div class="flex justify-center items-center space-x-4">
                    <div class="w-8 h-0.5 bg-amber-600"></div>
                    <div class="w-4 h-4 bg-amber-600 rounded-full flex items-center justify-center">
                        <div class="w-2 h-2 bg-white rounded-full"></div>
                    </div>
                    <div class="w-8 h-0.5 bg-amber-600"></div>
                </div>
            </div>
            <h2 class="text-2xl font-serif text-amber-700 uppercase tracking-widest">of {{ $certificate->type }}</h2>
        </div>

        {{-- Classic Content --}}
        <div class="text-center space-y-8">
            <div class="ornamental-frame border-2 border-amber-500 bg-white bg-opacity-70 p-8 rounded-lg shadow-inner">
                <h3 class="text-3xl font-serif text-amber-800 mb-6">{{ $certificate->title }}</h3>
                
                <div class="space-y-6">
                    <p class="text-xl font-serif text-amber-700">This is to certify that</p>
                    
                    <div class="recipient-showcase">
                        <div class="bg-white border-2 border-amber-500 rounded-lg p-6 shadow-inner relative">
                            {{-- Decorative flourishes --}}
                            <div class="absolute top-2 left-2 w-4 h-4 border-l-2 border-t-2 border-amber-400"></div>
                            <div class="absolute top-2 right-2 w-4 h-4 border-r-2 border-t-2 border-amber-400"></div>
                            <div class="absolute bottom-2 left-2 w-4 h-4 border-l-2 border-b-2 border-amber-400"></div>
                            <div class="absolute bottom-2 right-2 w-4 h-4 border-r-2 border-b-2 border-amber-400"></div>
                            
                            <h4 class="text-5xl font-serif text-amber-900 font-bold">{{ $certificate->user->name }}</h4>
                        </div>
                    </div>
                    
                    @if($certificate->description)
                    <p class="text-lg font-serif text-amber-700 leading-relaxed italic">{{ $certificate->description }}</p>
                    @endif
                </div>
            </div>
            
            @if($certificate->certificate_data)
            @php $data = $certificate->certificate_data @endphp
            <div class="achievement-details bg-white bg-opacity-80 border-2 border-amber-400 rounded-lg p-6 shadow-lg">
                @if(isset($data['course_name']))
                <div class="mb-4">
                    <p class="font-serif text-amber-700 mb-2">in recognition of successful completion of</p>
                    <h5 class="text-2xl font-serif font-bold text-amber-800 border-b-2 border-amber-300 inline-block pb-1">{{ $data['course_name'] }}</h5>
                </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    @if(isset($data['duration']))
                    <div class="text-center">
                        <div class="bg-amber-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-2 border-2 border-amber-300">
                            <i class="fas fa-clock text-amber-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-serif text-amber-600 font-semibold">Duration</p>
                        <p class="font-serif font-bold text-amber-800">{{ $data['duration'] }}</p>
                    </div>
                    @endif
                    
                    @if(isset($data['grade']))
                    <div class="text-center">
                        <div class="bg-amber-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-2 border-2 border-amber-300">
                            <i class="fas fa-star text-amber-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-serif text-amber-600 font-semibold">Grade Achieved</p>
                        <p class="font-serif font-bold text-amber-800">{{ $data['grade'] }}</p>
                    </div>
                    @endif
                    
                    @if(isset($data['instructor']))
                    <div class="text-center">
                        <div class="bg-amber-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-2 border-2 border-amber-300">
                            <i class="fas fa-user-tie text-amber-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-serif text-amber-600 font-semibold">Instructor</p>
                        <p class="font-serif font-bold text-amber-800">{{ $data['instructor'] }}</p>
                    </div>
                    @endif
                </div>
                
                @if(isset($data['achievement_details']))
                <div class="mt-6 pt-4 border-t border-amber-300">
                    <p class="text-sm font-serif text-amber-700 italic">{{ $data['achievement_details'] }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
        
        {{-- Classic Footer --}}
        <div class="classic-footer mt-16 flex justify-between items-end">
            <div class="authority text-left">
                <div class="bg-white bg-opacity-80 border-2 border-amber-400 rounded-lg p-4 shadow-md">
                    <p class="text-sm font-serif text-amber-600 mb-1">Certified by</p>
                    <div class="border-b border-amber-400 pb-1 mb-2">
                        <p class="font-serif font-bold text-amber-800 text-lg">{{ $certificate->issuer->name }}</p>
                    </div>
                    <p class="text-sm font-serif text-amber-700">{{ $certificate->institution->name }}</p>
                </div>
            </div>
            
            <div class="ceremonial-seal text-center">
                <div class="relative">
                    <div class="w-32 h-32 bg-gradient-to-br from-amber-400 via-amber-500 to-amber-600 rounded-full flex items-center justify-center border-4 border-amber-700 shadow-xl relative">
                        {{-- Inner seal design --}}
                        <div class="w-24 h-24 border-2 border-amber-800 rounded-full flex items-center justify-center bg-amber-300">
                            <div class="text-center text-amber-900">
                                <i class="fas fa-award text-2xl mb-1"></i>
                                <div class="text-xs font-serif font-bold leading-tight">
                                    <div>AUTHENTIC</div>
                                    <div>SEAL</div>
                                </div>
                            </div>
                        </div>
                        {{-- Decorative dots around seal --}}
                        <div class="absolute inset-0">
                            @for($i = 0; $i < 12; $i++)
                            <div class="absolute w-2 h-2 bg-amber-800 rounded-full" 
                                 style="transform: rotate({{ $i * 30 }}deg) translateY(-60px);"></div>
                            @endfor
                        </div>
                    </div>
                    <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2">
                        <div class="bg-amber-700 text-white text-xs px-3 py-1 rounded-full font-serif font-bold shadow-lg">
                            OFFICIAL
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="date-issued text-right">
                <div class="bg-white bg-opacity-80 border-2 border-amber-400 rounded-lg p-4 shadow-md">
                    <p class="text-sm font-serif text-amber-600 mb-1">Date of Issue</p>
                    <div class="border-b border-amber-400 pb-1 mb-2">
                        <p class="font-serif font-bold text-amber-800 text-lg">{{ $certificate->issue_date->format('F d, Y') }}</p>
                    </div>
                    @if($certificate->expiry_date)
                    <p class="text-xs font-serif text-amber-600">Valid until {{ $certificate->expiry_date->format('M Y') }}</p>
                    @else
                    <p class="text-xs font-serif text-amber-600">No expiration date</p>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- Classic Certificate Code --}}
        <div class="certificate-authentication mt-8 text-center">
            <div class="inline-block bg-white bg-opacity-90 border-2 border-amber-500 rounded-lg px-8 py-3 shadow-lg">
                <p class="text-sm font-serif text-amber-700 mb-1">Certificate Authentication Code</p>
                <p class="font-mono text-amber-800 font-bold text-lg tracking-wider">{{ $certificate->certificate_code }}</p>
                <p class="text-xs font-serif text-amber-600 mt-1">Verify at {{ config('app.url') }}/verify</p>
            </div>
        </div>
        
        {{-- Special notes if any --}}
        @if($certificate->special_notes)
        <div class="special-notes mt-6 text-center">
            <div class="inline-block bg-amber-100 border border-amber-400 rounded-lg px-6 py-2">
                <p class="text-sm font-serif text-amber-800 italic">{{ $certificate->special_notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

</div>
