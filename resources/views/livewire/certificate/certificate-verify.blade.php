<div>
<div class="container mx-auto px-6 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Certificate Verification</h1>
            <p class="text-gray-600">Verify the authenticity of a certificate by entering its code or verification hash</p>
        </div>

        {{-- Verification Form --}}
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <form wire:submit.prevent="verifyCertificate">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Certificate Code or Verification Hash *
                    </label>
                    <input type="text" wire:model="verificationCode" 
                           placeholder="Enter certificate code (e.g., ABC-2024-COM-0001) or verification hash"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-lg">
                    @error('verificationCode') 
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>
                
                <div class="flex space-x-4">
                    <button type="submit" 
                            class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-3 rounded-lg font-semibold transition duration-200">
                        <i class="fas fa-shield-alt mr-2"></i>Verify Certificate
                    </button>
                    <button type="button" wire:click="reset" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </button>
                </div>
            </form>
        </div>

        {{-- Verification Results --}}
        @if($verificationResult)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            {{-- Result Header --}}
            <div class="px-8 py-6 border-b border-gray-200
                @if($verificationResult === 'valid') bg-green-50 border-green-200
                @elseif($verificationResult === 'expired') bg-yellow-50 border-yellow-200
                @elseif($verificationResult === 'revoked') bg-red-50 border-red-200
                @else bg-gray-50 border-gray-200 @endif">
                
                <div class="flex items-center">
                    @if($verificationResult === 'valid')
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-check text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-green-800">Certificate Valid</h3>
                            <p class="text-green-600">This certificate is authentic and currently valid.</p>
                        </div>
                    @elseif($verificationResult === 'expired')
                        <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-yellow-800">Certificate Expired</h3>
                            <p class="text-yellow-600">This certificate was valid but has expired.</p>
                        </div>
                    @elseif($verificationResult === 'revoked')
                        <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-ban text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-red-800">Certificate Revoked</h3>
                            <p class="text-red-600">This certificate has been revoked and is no longer valid.</p>
                        </div>
                    @else
                        <div class="w-12 h-12 bg-gray-500 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-times text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Certificate Not Found</h3>
                            <p class="text-gray-600">No certificate found with the provided code or hash.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Certificate Details --}}
            @if($certificate)
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-3">Certificate Information</h4>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-600">Title:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $certificate->title }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Code:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $certificate->certificate_code }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Type:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2
                                    @if($certificate->type === 'completion') bg-blue-100 text-blue-800
                                    @elseif($certificate->type === 'participation') bg-green-100 text-green-800
                                    @elseif($certificate->type === 'achievement') bg-purple-100 text-purple-800
                                    @else bg-indigo-100 text-indigo-800 @endif">
                                    {{ ucfirst($certificate->type) }}
                                </span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Issue Date:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $certificate->issue_date->format('M d, Y') }}</span>
                            </div>
                            @if($certificate->expiry_date)
                            <div>
                                <span class="text-sm font-medium text-gray-600">Expiry Date:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $certificate->expiry_date->format('M d, Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-3">Recipient & Institution</h4>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-600">Recipient:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $certificate->user->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Institution:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $certificate->institution->name }}</span>
                            </div>
                            @if($certificate->event)
                            <div>
                                <span class="text-sm font-medium text-gray-600">Event:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $certificate->event->title }}</span>
                            </div>
                            @endif
                            @if($certificate->certificate_data && isset($certificate->certificate_data['course_name']))
                            <div>
                                <span class="text-sm font-medium text-gray-600">Course:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $certificate->certificate_data['course_name'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($certificate->description)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="font-semibold text-gray-800 mb-2">Description</h4>
                    <p class="text-sm text-gray-700">{{ $certificate->description }}</p>
                </div>
                @endif
                
                @if($verificationResult === 'revoked' && $certificate->revocation_reason)
                <div class="mt-6 pt-6 border-t border-red-200">
                    <h4 class="font-semibold text-red-800 mb-2">Revocation Information</h4>
                    <p class="text-sm text-red-700">{{ $certificate->revocation_reason }}</p>
                    @if($certificate->revoked_at)
                    <p class="text-xs text-red-600 mt-1">Revoked on {{ $certificate->revoked_at->format('M d, Y') }}</p>
                    @endif
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif

        {{-- Verification Tips --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
            <h3 class="font-semibold text-blue-800 mb-3">
                <i class="fas fa-info-circle mr-2"></i>Verification Tips
            </h3>
            <ul class="text-sm text-blue-700 space-y-2">
                <li>• Certificate codes are typically in the format: ABC-2024-COM-0001</li>
                <li>• Verification hashes are long alphanumeric strings</li>
                <li>• Both codes and hashes are case-sensitive</li>
                <li>• Valid certificates will show complete details including recipient and institution</li>
                <li>• If you suspect a certificate is fraudulent, contact the issuing institution</li>
            </ul>
        </div>
    </div>
</div>

</div>
