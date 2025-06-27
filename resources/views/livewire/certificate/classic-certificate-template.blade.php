<div>


<div class="classic-certificate-container">

    {{-- Flash Messages --}}
    @if (session()->has('message'))
    <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg mb-6">
        <i class="fas fa-check-circle mr-2"></i>{{ session('message') }}
    </div>
    @endif

    @if (session()->has('error'))
    <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg mb-6">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
    @endif

    {{-- Template Controls (only show in customize mode) --}}
    @if($viewMode === 'customize')
    <div class="template-controls bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-palette text-yellow-500 mr-2"></i>Customize Classic Template
            </h3>
            <div class="flex space-x-2">
                <button wire:click="resetToDefaults" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                    <i class="fas fa-undo mr-1"></i>Reset
                </button>
                <button wire:click="saveCustomization" 
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                    <i class="fas fa-save mr-1"></i>Save
                </button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Border Style --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Border Style</label>
                <select wire:model.live="borderStyle"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    @foreach($customizationOptions['borderStyles'] as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            {{-- Seal Style --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Seal Style</label>
                <select wire:model.live="sealStyle"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    @foreach($customizationOptions['sealStyles'] as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            {{-- Color Scheme --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Color Scheme</label>
                <select wire:model.live="colorScheme"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    @foreach($customizationOptions['colorSchemes'] as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            {{-- Font Size --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Font Size</label>
                <select wire:model.live="fontSize"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    @foreach($customizationOptions['fontSizes'] as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        {{-- Toggle Options --}}
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h4 class="text-sm font-medium text-gray-700 mb-3">Display Options</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <label class="flex items-center">
                    <input type="checkbox" wire:model.live="showPattern"
                           class="form-checkbox h-4 w-4 text-yellow-500">
                    <span class="ml-2 text-sm text-gray-700">Background Pattern</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" wire:model.live="showCorners"
                           class="form-checkbox h-4 w-4 text-yellow-500">
                    <span class="ml-2 text-sm text-gray-700">Decorative Corners</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" wire:model.live="showSeal"
                           class="form-checkbox h-4 w-4 text-yellow-500">
                    <span class="ml-2 text-sm text-gray-700">Official Seal</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" wire:model.live="showVerificationCode"
                           class="form-checkbox h-4 w-4 text-yellow-500">
                    <span class="ml-2 text-sm text-gray-700">Verification Code</span>
                </label>
            </div>
        </div>
        
        {{-- Quick Presets --}}
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h4 class="text-sm font-medium text-gray-700 mb-3">Quick Presets</h4>
            <div class="flex flex-wrap gap-2">
                <button wire:click="applyPreset('elegant')" 
                        class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded text-sm hover:bg-yellow-200 transition duration-200">
                    Elegant
                </button>
                <button wire:click="applyPreset('vintage')" 
                        class="px-3 py-1 bg-amber-100 text-amber-800 rounded text-sm hover:bg-amber-200 transition duration-200">
                    Vintage
                </button>
                <button wire:click="applyPreset('modern')" 
                        class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded text-sm hover:bg-emerald-200 transition duration-200">
                    Modern
                </button>
                <button wire:click="applyPreset('minimal')" 
                        class="px-3 py-1 bg-gray-100 text-gray-800 rounded text-sm hover:bg-gray-200 transition duration-200">
                    Minimal
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Action Buttons (not in print mode) --}}
    @if($viewMode !== 'print')
    <div class="certificate-actions bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div class="flex flex-wrap gap-2">
                @if($viewMode !== 'customize')
                <button wire:click="toggleCustomization" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                    <i class="fas fa-palette mr-2"></i>Customize
                </button>
                @endif
                
                <button wire:click="printCertificate" 
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
                
                <button wire:click="exportAsPdf" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="exportAsPdf">
                        <i class="fas fa-file-pdf mr-2"></i>Export PDF
                    </span>
                    <span wire:loading wire:target="exportAsPdf">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Generating...
                    </span>
                </button>
                
                <button wire:click="shareCertificate" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                    <i class="fas fa-share mr-2"></i>Share
                </button>
            </div>
            
            @if($viewMode === 'customize')
            <button wire:click="toggleCustomization" 
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                <i class="fas fa-times mr-2"></i>Close Customizer
            </button>
            @endif
        </div>
    </div>
    @endif

    {{-- Certificate Template --}}
    <div class="certificate-classic bg-gradient-to-br from-{{ $colorClasses['background'] }} to-{{ $colorClasses['light'] }} {{ $borderClasses }} max-w-4xl mx-auto relative print:max-w-none print:mx-0 shadow-2xl rounded-lg"
         id="classic-certificate-{{ $certificateId }}">
        
        {{-- Decorative corners --}}
        @if($showCorners)
        <div class="absolute top-4 left-4 w-16 h-16 border-l-4 border-t-4 border-{{ $colorClasses['primary'] }}"></div>
        <div class="absolute top-4 right-4 w-16 h-16 border-r-4 border-t-4 border-{{ $colorClasses['primary'] }}"></div>
        <div class="absolute bottom-4 left-4 w-16 h-16 border-l-4 border-b-4 border-{{ $colorClasses['primary'] }}"></div>
        <div class="absolute bottom-4 right-4 w-16 h-16 border-r-4 border-b-4 border-{{ $colorClasses['primary'] }}"></div>
        @endif
        
        {{-- Background pattern --}}
        @if($showPattern)
        <div class="absolute inset-0 opacity-5 rounded-lg overflow-hidden">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="classicPattern-{{ $certificateId }}" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="10" cy="10" r="1" fill="currentColor"/>
                        <circle cx="5" cy="5" r="0.5" fill="currentColor"/>
                        <circle cx="15" cy="15" r="0.5" fill="currentColor"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#classicPattern-{{ $certificateId }})" />
            </svg>
        </div>
        @endif
        
        <div class="relative p-8 md:p-12">
            {{-- Classic Header --}}
            <div class="text-center mb-8 md:mb-12">
                <div class="decorative-border border-4 border-double border-{{ $colorClasses['primary'] }} p-4 md:p-6 mb-6 bg-white bg-opacity-50 rounded-lg">
                    <h1 class="{{ $fontSizeClasses['title'] }} font-serif text-{{ $colorClasses['secondary'] }} mb-4">Certificate</h1>
                    <div class="flex justify-center items-center space-x-4">
                        <div class="w-6 md:w-8 h-0.5 bg-{{ $colorClasses['primary'] }}"></div>
                        <div class="w-3 md:w-4 h-3 md:h-4 bg-{{ $colorClasses['primary'] }} rounded-full flex items-center justify-center">
                            <div class="w-1.5 md:w-2 h-1.5 md:h-2 bg-white rounded-full"></div>
                        </div>
                        <div class="w-6 md:w-8 h-0.5 bg-{{ $colorClasses['primary'] }}"></div>
                    </div>
                </div>
                <h2 class="text-lg md:{{ $fontSizeClasses['content'] }} font-serif text-{{ $colorClasses['secondary'] }} uppercase tracking-widest">
                    of {{ ucfirst($certificate->type ?? 'Achievement') }}
                </h2>
            </div>

            {{-- Classic Content --}}
            <div class="text-center space-y-6 md:space-y-8">
                <div class="ornamental-frame border-2 border-{{ $colorClasses['primary'] }} bg-white bg-opacity-70 p-6 md:p-8 rounded-lg shadow-inner">
                    <h3 class="{{ $fontSizeClasses['content'] }} font-serif text-{{ $colorClasses['secondary'] }} mb-6">{{ $certificate->title ?? 'Certificate Title' }}</h3>
                    
                    <div class="space-y-4 md:space-y-6">
                        <p class="text-lg md:{{ $fontSizeClasses['details'] }} font-serif text-{{ $colorClasses['secondary'] }}">This is to certify that</p>
                        
                        <div class="recipient-showcase">
                            <div class="bg-white border-2 border-{{ $colorClasses['primary'] }} rounded-lg p-4 md:p-6 shadow-inner relative">
                                {{-- Decorative flourishes --}}
                                <div class="absolute top-2 left-2 w-3 md:w-4 h-3 md:h-4 border-l-2 border-t-2 border-{{ $colorClasses['light'] }}"></div>
                                <div class="absolute top-2 right-2 w-3 md:w-4 h-3 md:h-4 border-r-2 border-t-2 border-{{ $colorClasses['light'] }}"></div>
                                <div class="absolute bottom-2 left-2 w-3 md:w-4 h-3 md:h-4 border-l-2 border-b-2 border-{{ $colorClasses['light'] }}"></div>
                                <div class="absolute bottom-2 right-2 w-3 md:w-4 h-3 md:h-4 border-r-2 border-b-2 border-{{ $colorClasses['light'] }}"></div>
                                
                                <h4 class="{{ $fontSizeClasses['name'] }} font-serif text-{{ $colorClasses['secondary'] }} font-bold">{{ $certificate->user->name ?? 'Recipient Name' }}</h4>
                            </div>
                        </div>
                        
                        @if($certificate->description)
                        <p class="text-base md:{{ $fontSizeClasses['details'] }} font-serif text-{{ $colorClasses['secondary'] }} leading-relaxed italic">{{ $certificate->description }}</p>
                        @endif
                    </div>
                </div>
                
                {{-- Achievement Details --}}
                @if($templateData['course_name'])
                <div class="achievement-details bg-white bg-opacity-80 border-2 border-{{ $colorClasses['light'] }} rounded-lg p-4 md:p-6 shadow-lg">
                    <div class="mb-4">
                        <p class="font-serif text-{{ $colorClasses['secondary'] }} mb-2">in recognition of successful completion of</p>
                        <h5 class="text-xl md:{{ $fontSizeClasses['content'] }} font-serif font-bold text-{{ $colorClasses['secondary'] }} border-b-2 border-{{ $colorClasses['light'] }} inline-block pb-1">{{ $templateData['course_name'] }}</h5>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mt-6">
                        @if($templateData['duration'])
                        <div class="text-center">
                            <div class="bg-{{ $colorClasses['light'] }} rounded-full w-12 md:w-16 h-12 md:h-16 flex items-center justify-center mx-auto mb-2 border-2 border-{{ $colorClasses['primary'] }}">
                                <i class="fas fa-clock text-{{ $colorClasses['primary'] }} text-lg md:text-xl"></i>
                            </div>
                            <p class="text-xs md:text-sm font-serif text-{{ $colorClasses['primary'] }} font-semibold">Duration</p>
                            <p class="font-serif font-bold text-{{ $colorClasses['secondary'] }} text-sm md:text-base">{{ $templateData['duration'] }}</p>
                        </div>
                        @endif
                        
                        @if($templateData['grade'])
                        <div class="text-center">
                            <div class="bg-{{ $colorClasses['light'] }} rounded-full w-12 md:w-16 h-12 md:h-16 flex items-center justify-center mx-auto mb-2 border-2 border-{{ $colorClasses['primary'] }}">
                                <i class="fas fa-star text-{{ $colorClasses['primary'] }} text-lg md:text-xl"></i>
                            </div>
                            <p class="text-xs md:text-sm font-serif text-{{ $colorClasses['primary'] }} font-semibold">Grade Achieved</p>
                            <p class="font-serif font-bold text-{{ $colorClasses['secondary'] }} text-sm md:text-base">{{ $templateData['grade'] }}</p>
                        </div>
                        @endif
                        
                        @if($templateData['instructor'])
                        <div class="text-center">
                            <div class="bg-{{ $colorClasses['light'] }} rounded-full w-12 md:w-16 h-12 md:h-16 flex items-center justify-center mx-auto mb-2 border-2 border-{{ $colorClasses['primary'] }}">
                                <i class="fas fa-user-tie text-{{ $colorClasses['primary'] }} text-lg md:text-xl"></i>
                            </div>
                            <p class="text-xs md:text-sm font-serif text-{{ $colorClasses['primary'] }} font-semibold">Instructor</p>
                            <p class="font-serif font-bold text-{{ $colorClasses['secondary'] }} text-sm md:text-base">{{ $templateData['instructor'] }}</p>
                        </div>
                        @endif
                    </div>
                    
                    @if($templateData['achievement_details'])
                    <div class="mt-4 md:mt-6 pt-4 border-t border-{{ $colorClasses['light'] }}">
                        <p class="text-xs md:text-sm font-serif text-{{ $colorClasses['secondary'] }} italic">{{ $templateData['achievement_details'] }}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            
            {{-- Classic Footer --}}
            <div class="classic-footer mt-12 md:mt-16 flex flex-col md:flex-row justify-between items-center md:items-end space-y-6 md:space-y-0">
                {{-- Authority Signature --}}
                <div class="authority text-center md:text-left">
                    <div class="bg-white bg-opacity-80 border-2 border-{{ $colorClasses['light'] }} rounded-lg p-3 md:p-4 shadow-md">
                        <p class="text-xs md:text-sm font-serif text-{{ $colorClasses['primary'] }} mb-1">Certified by</p>
                        <div class="border-b border-{{ $colorClasses['light'] }} pb-1 mb-2">
                            <p class="font-serif font-bold text-{{ $colorClasses['secondary'] }} text-sm md:text-lg">{{ $certificate->issuer->name ?? 'Institution Authority' }}</p>
                        </div>
                        <p class="text-xs md:text-sm font-serif text-{{ $colorClasses['secondary'] }}">{{ $certificate->institution->name ?? 'Institution Name' }}</p>
                    </div>
                </div>
                
                {{-- Ceremonial Seal --}}
                @if($showSeal)
                <div class="ceremonial-seal text-center">
                    <div class="relative">
                        @if($sealStyle === 'ceremonial')
                        <div class="w-24 md:w-32 h-24 md:h-32 bg-gradient-to-br from-{{ $colorClasses['light'] }} via-{{ $colorClasses['primary'] }} to-{{ $colorClasses['secondary'] }} rounded-full flex items-center justify-center border-4 border-{{ $colorClasses['secondary'] }} shadow-xl relative">
                            {{-- Inner seal design --}}
                            <div class="w-16 md:w-24 h-16 md:h-24 border-2 border-{{ $colorClasses['secondary'] }} rounded-full flex items-center justify-center bg-{{ $colorClasses['light'] }}">
                                <div class="text-center text-{{ $colorClasses['secondary'] }}">
                                    <i class="fas fa-award text-lg md:text-2xl mb-1"></i>
                                    <div class="text-xs font-serif font-bold leading-tight">
                                        <div>AUTHENTIC</div>
                                        <div>SEAL</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($sealStyle === 'official')
                        <div class="w-24 md:w-32 h-24 md:h-32 bg-{{ $colorClasses['primary'] }} rounded-full flex items-center justify-center border-4 border-{{ $colorClasses['secondary'] }} shadow-xl">
                            <div class="text-center text-white">
                                <i class="fas fa-university text-lg md:text-2xl mb-1"></i>
                                <div class="text-xs font-serif font-bold">OFFICIAL</div>
                            </div>
                        </div>
                        @else
                        <div class="w-20 md:w-24 h-20 md:h-24 bg-{{ $colorClasses['primary'] }} rounded-full flex items-center justify-center border-2 border-{{ $colorClasses['secondary'] }} shadow-lg">
                            <i class="fas fa-check text-white text-xl md:text-2xl"></i>
                        </div>
                        @endif
                        
                        <div class="absolute -bottom-2 md:-bottom-3 left-1/2 transform -translate-x-1/2">
                            <div class="bg-{{ $colorClasses['secondary'] }} text-white text-xs px-2 md:px-3 py-1 rounded-full font-serif font-bold shadow-lg">
                                OFFICIAL
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                {{-- Date Information --}}
                <div class="date-issued text-center md:text-right">
                    <div class="bg-white bg-opacity-80 border-2 border-{{ $colorClasses['light'] }} rounded-lg p-3 md:p-4 shadow-md">
                        <p class="text-xs md:text-sm font-serif text-{{ $colorClasses['primary'] }} mb-1">Date of Issue</p>
                        <div class="border-b border-{{ $colorClasses['light'] }} pb-1 mb-2">
                            <p class="font-serif font-bold text-{{ $colorClasses['secondary'] }} text-sm md:text-lg">
                                {{ $certificate->issue_date ? $certificate->issue_date->format('F d, Y') : date('F d, Y') }}
                            </p>
                        </div>
                        @if($certificate->expiry_date)
                        <p class="text-xs font-serif text-{{ $colorClasses['primary'] }}">Valid until {{ $certificate->expiry_date->format('M Y') }}</p>
                        @else
                        <p class="text-xs font-serif text-{{ $colorClasses['primary'] }}">No expiration date</p>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Certificate Authentication --}}
            @if($showVerificationCode && $certificate->certificate_code)
            <div class="certificate-authentication mt-6 md:mt-8 text-center">
                <div class="inline-block bg-white bg-opacity-90 border-2 border-{{ $colorClasses['primary'] }} rounded-lg px-6 md:px-8 py-2 md:py-3 shadow-lg">
                    <p class="text-xs md:text-sm font-serif text-{{ $colorClasses['secondary'] }} mb-1">Certificate Authentication Code</p>
                    <p class="font-mono text-{{ $colorClasses['secondary'] }} font-bold text-sm md:text-lg tracking-wider">{{ $certificate->certificate_code }}</p>
                    <p class="text-xs font-serif text-{{ $colorClasses['primary'] }} mt-1">Verify at {{ config('app.url') }}/verify</p>
                </div>
            </div>
            @endif
            
            {{-- Special Notes --}}
            @if(isset($certificate->special_notes) && $certificate->special_notes)
            <div class="special-notes mt-4 md:mt-6 text-center">
                <div class="inline-block bg-{{ $colorClasses['light'] }} border border-{{ $colorClasses['primary'] }} rounded-lg px-4 md:px-6 py-2">
                    <p class="text-xs md:text-sm font-serif text-{{ $colorClasses['secondary'] }} italic">{{ $certificate->special_notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Loading Overlay --}}
@if($showLoadingEffect)
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:loading.class="block" wire:loading.class.remove="hidden">
    <div class="bg-white rounded-lg p-8 text-center">
        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-yellow-500 mx-auto mb-4"></div>
        <p class="text-gray-700 font-medium">Generating Classic Certificate PDF...</p>
    </div>
</div>
@endif

{{-- Styles --}}
<style>
.classic-certificate-container {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Print styles */
@media print {
    .template-controls,
    .certificate-actions {
        display: none !important;
    }
    
    .classic-certificate-container {
        width: 100%;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .certificate-classic {
        max-width: none !important;
        margin: 0 !important;
        width: 210mm;
        height: 297mm;
        page-break-inside: avoid;
        box-shadow: none !important;
    }
    
    body {
        margin: 0;
        padding: 0;
        background: white;
    }
}

@page {
    size: A4;
    margin: 0;
}

/* Custom checkbox styling */
input[type="checkbox"]:checked {
    background-color: #eab308;
    border-color: #eab308;
}

/* Loading animation */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.classic-certificate-container {
    animation: fadeIn 0.5s ease-in-out;
}
</style>

@script
<script>
// Print functionality
$wire.on('print-classic-certificate', () => {
    window.print();
});

// Share functionality
$wire.on('share-certificate', (event) => {
    const data = event[0];
    if (navigator.share) {
        navigator.share({
            title: data.title,
            text: data.text,
            url: data.url
        }).catch(console.error);
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(data.url).then(() => {
            showNotification('Verification link copied to clipboard!', 'success');
        }).catch(() => {
            showNotification('Failed to copy link', 'error');
        });
    }
});

// PDF ready notification
$wire.on('pdf-ready', (event) => {
    const data = event[0];
    showNotification(data.message, 'success');
});

// PDF error notification
$wire.on('pdf-error', (event) => {
    const data = event[0];
    showNotification(data.message, 'error');
});

// Template updated notification
$wire.on('classic-template-updated', (event) => {
    console.log('Template updated:', event[0]);
});

function showNotification(message, type) {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle mr-2"></i>${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}
</script>
@endscript

</div>
