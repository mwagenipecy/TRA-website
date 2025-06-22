<div>
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Certificate Details</h1>
        <div class="flex space-x-4">
            @if($certificate->file_path)
            <button wire:click="downloadCertificate" 
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                <i class="fas fa-download mr-2"></i>Download
            </button>
            @endif
            <button wire:click="generatePDF" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                <i class="fas fa-file-pdf mr-2"></i>Generate PDF
            </button>
            <a href="{{ route('certificates.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Certificate Preview --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                {{-- Certificate Header --}}
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-black p-8 text-center">
                    <div class="mb-4">
                        <i class="fas fa-certificate text-6xl mb-4"></i>
                        <h1 class="text-3xl font-bold">CERTIFICATE</h1>
                        <h2 class="text-xl font-semibold mt-2">{{ strtoupper($certificate->type) }}</h2>
                    </div>
                </div>

                {{-- Certificate Body --}}
                <div class="p-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">{{ $certificate->title }}</h3>
                    
                    <div class="mb-6">
                        <p class="text-lg text-gray-600 mb-2">This is to certify that</p>
                        <h4 class="text-3xl font-bold text-gray-800 border-b-2 border-yellow-500 inline-block pb-2">
                            {{ $certificate->user->name }}
                        </h4>
                    </div>
                    
                    @if($certificate->description)
                    <p class="text-lg text-gray-700 mb-6 max-w-2xl mx-auto">{{ $certificate->description }}</p>
                    @endif
                    
                    @if($certificate->certificate_data && isset($certificate->certificate_data['course_name']))
                    <div class="mb-6">
                        <p class="text-lg text-gray-600">has successfully completed</p>
                        <h5 class="text-xl font-semibold text-gray-800">{{ $certificate->certificate_data['course_name'] }}</h5>
                        
                        @if(isset($certificate->certificate_data['duration']))
                        <p class="text-gray-600 mt-2">Duration: {{ $certificate->certificate_data['duration'] }}</p>
                        @endif
                        
                        @if(isset($certificate->certificate_data['grade']))
                        <p class="text-gray-600">Grade: {{ $certificate->certificate_data['grade'] }}</p>
                        @endif
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-end mt-12">
                        <div class="text-left">
                            <div class="border-t border-gray-400 pt-2 w-48">
                                <p class="text-sm text-gray-600">Issued by</p>
                                <p class="font-semibold">{{ $certificate->issuer->name }}</p>
                                <p class="text-sm text-gray-600">{{ $certificate->institution->name }}</p>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <div class="mb-4">
                                <div class="w-24 h-24 bg-yellow-500 rounded-full flex items-center justify-center mx-auto">
                                    <i class="fas fa-seal text-black text-2xl"></i>
                                </div>
                                <p class="text-xs text-gray-600 mt-2">Official Seal</p>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <div class="border-t border-gray-400 pt-2 w-48">
                                <p class="text-sm text-gray-600">Date Issued</p>
                                <p class="font-semibold">{{ $certificate->issue_date->format('F d, Y') }}</p>
                                @if($certificate->expiry_date)
                                <p class="text-sm text-gray-600 mt-1">Expires: {{ $certificate->expiry_date->format('F d, Y') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-600">Certificate Code: {{ $certificate->certificate_code }}</p>
                        <p class="text-xs text-gray-500 mt-1">Verification Hash: {{ substr($certificate->verification_hash, 0, 16) }}...</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Certificate Information --}}
        <div class="space-y-6">
            {{-- Status & Actions --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Certificate Status</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Status:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($certificate->status === 'active' && !$certificate->isExpired()) bg-green-100 text-green-800
                            @elseif($certificate->isExpired()) bg-gray-100 text-gray-800
                            @elseif($certificate->status === 'revoked') bg-red-100 text-red-800
                            @else bg-blue-100 text-blue-800 @endif">
                            @if($certificate->isExpired() && $certificate->status === 'active')
                                <i class="fas fa-clock mr-1"></i>Expired
                            @elseif($certificate->status === 'active')
                                <i class="fas fa-check-circle mr-1"></i>Active
                            @elseif($certificate->status === 'revoked')
                                <i class="fas fa-ban mr-1"></i>Revoked
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Type:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($certificate->type === 'completion') bg-blue-100 text-blue-800
                            @elseif($certificate->type === 'participation') bg-green-100 text-green-800
                            @elseif($certificate->type === 'achievement') bg-purple-100 text-purple-800
                            @else bg-indigo-100 text-indigo-800 @endif">
                            {{ ucfirst($certificate->type) }}
                        </span>
                    </div>
                    
                    @if($certificate->isValid())
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <i class="fas fa-shield-check text-green-500 mr-2"></i>
                            <span class="text-sm text-green-700 font-medium">Valid Certificate</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Certificate Details --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Details</h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-600">Recipient:</span>
                        <div class="text-sm text-gray-900">{{ $certificate->user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $certificate->user->email }}</div>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-600">Institution:</span>
                        <div class="text-sm text-gray-900">{{ $certificate->institution->name }}</div>
                    </div>
                    
                    @if($certificate->event)
                    <div>
                        <span class="text-sm font-medium text-gray-600">Related Event:</span>
                        <div class="text-sm text-gray-900">{{ $certificate->event->title }}</div>
                    </div>
                    @endif
                    
                    <div>
                        <span class="text-sm font-medium text-gray-600">Issued By:</span>
                        <div class="text-sm text-gray-900">{{ $certificate->issuer->name }}</div>
                        <div class="text-xs text-gray-500">{{ $certificate->created_at->format('M d, Y H:i') }}</div>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-600">Template:</span>
                        <div class="text-sm text-gray-900">{{ ucfirst($certificate->template_used) }}</div>
                    </div>
                </div>
            </div>

            {{-- Additional Information --}}
            @if($certificate->certificate_data)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Information</h3>
                
                <div class="space-y-3">
                    @if(isset($certificate->certificate_data['instructor']))
                    <div>
                        <span class="text-sm font-medium text-gray-600">Instructor:</span>
                        <div class="text-sm text-gray-900">{{ $certificate->certificate_data['instructor'] }}</div>
                    </div>
                    @endif
                    
                    @if(isset($certificate->certificate_data['achievement_details']))
                    <div>
                        <span class="text-sm font-medium text-gray-600">Achievement Details:</span>
                        <div class="text-sm text-gray-900">{{ $certificate->certificate_data['achievement_details'] }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Special Notes --}}
            @if($certificate->special_notes)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Special Notes</h3>
                <p class="text-sm text-gray-700">{{ $certificate->special_notes }}</p>
            </div>
            @endif

            {{-- Revocation Information --}}
            @if($certificate->status === 'revoked')
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-red-800 mb-4">Revocation Information</h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-red-600">Revoked By:</span>
                        <div class="text-sm text-red-900">{{ $certificate->revoker->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-red-600">Revocation Date:</span>
                        <div class="text-sm text-red-900">{{ $certificate->revoked_at->format('M d, Y H:i') }}</div>
                    </div>
                    
                    @if($certificate->revocation_reason)
                    <div>
                        <span class="text-sm font-medium text-red-600">Reason:</span>
                        <div class="text-sm text-red-900">{{ $certificate->revocation_reason }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
