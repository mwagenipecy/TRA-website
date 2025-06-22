<div>
<div class="certificate-viewer {{ $viewMode === 'print' ? 'print-mode' : '' }}">
    {{-- Certificate Actions (Hidden in print mode) --}}
    @if($showActions && $viewMode !== 'print')
    <div class="certificate-actions bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div class="flex flex-wrap gap-2">
                <button wire:click="printCertificate" 
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
                <button wire:click="downloadCertificate" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                    <i class="fas fa-download mr-2"></i>Download PDF
                </button>
                <button wire:click="shareCertificate" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                    <i class="fas fa-share mr-2"></i>Share
                </button>
                <button wire:click="toggleQrCode" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                    <i class="fas fa-qrcode mr-2"></i>{{ $showQrCode ? 'Hide' : 'Show' }} QR
                </button>
            </div>
            
            {{-- Template Selector --}}
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Template:</span>
                <select wire:model="templateStyle" class="text-sm border border-gray-300 rounded px-2 py-1">
                    <option value="modern">Modern</option>
                    <option value="formal">Formal</option>
                    <option value="classic">Classic</option>
                    <option value="minimal">Minimal</option>
                </select>
            </div>
        </div>
    </div>
    @endif

    {{-- Certificate Status Banner --}}
    @if($status['status'] !== 'valid')
    <div class="status-banner bg-{{ $status['color'] }}-100 border border-{{ $status['color'] }}-300 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-{{ $status['icon'] }} text-{{ $status['color'] }}-600 text-xl mr-3"></i>
            <div>
                <h3 class="font-semibold text-{{ $status['color'] }}-800">{{ ucfirst($status['status']) }} Certificate</h3>
                <p class="text-{{ $status['color'] }}-700">{{ $status['message'] }}</p>
                @if($certificate->revocation_reason)
                <p class="text-sm text-{{ $status['color'] }}-600 mt-1">Reason: {{ $certificate->revocation_reason }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Certificate Display --}}
    <div class="certificate-container">
        @if($templateStyle === 'modern')
            @include('livewire.certificate.templates.modern', ['certificate' => $certificate])
        @elseif($templateStyle === 'formal')
            @include('livewire.certificate.templates.formal', ['certificate' => $certificate])
        @elseif($templateStyle === 'classic')
            @include('livewire.certificate.templates.classic', ['certificate' => $certificate])
        @else
            @include('livewire.certificate.templates.minimal', ['certificate' => $certificate])
        @endif
    </div>

    {{-- Certificate Details (Hidden in print mode) --}}
    @if($viewMode !== 'print')
    <div class="certificate-details bg-white rounded-lg shadow-md p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
            <i class="fas fa-info-circle text-yellow-500 mr-2"></i>Certificate Details
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="space-y-3">
                <h4 class="font-medium text-gray-700">Basic Information</h4>
                <div class="space-y-2 text-sm">
                    <div><span class="font-medium">Code:</span> {{ $certificate->certificate_code }}</div>
                    <div><span class="font-medium">Type:</span> {{ ucfirst($certificate->type) }}</div>
                    <div><span class="font-medium">Status:</span> 
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-800">
                            {{ ucfirst($status['status']) }}
                        </span>
                    </div>
                    <div><span class="font-medium">Issue Date:</span> {{ $certificate->issue_date->format('F d, Y') }}</div>
                    @if($certificate->expiry_date)
                    <div><span class="font-medium">Expiry Date:</span> {{ $certificate->expiry_date->format('F d, Y') }}</div>
                    @endif
                </div>
            </div>
            
            <div class="space-y-3">
                <h4 class="font-medium text-gray-700">Recipient & Institution</h4>
                <div class="space-y-2 text-sm">
                    <div><span class="font-medium">Recipient:</span> {{ $certificate->user->name }}</div>
                    <div><span class="font-medium">Institution:</span> {{ $certificate->institution->name }}</div>
                    <div><span class="font-medium">Issued By:</span> {{ $certificate->issuer->name }}</div>
                    @if($certificate->event)
                    <div><span class="font-medium">Related Event:</span> {{ $certificate->event->title }}</div>
                    @endif
                </div>
            </div>
            
            <div class="space-y-3">
                <h4 class="font-medium text-gray-700">Verification</h4>
                <div class="space-y-2 text-sm">
                    <div><span class="font-medium">Verification Hash:</span> 
                        <span class="font-mono text-xs">{{ substr($certificate->verification_hash, 0, 16) }}...</span>
                    </div>
                    <div><span class="font-medium">Verification URL:</span> 
                        <a href="{{ $verificationUrl }}" target="_blank" class="text-blue-600 hover:text-blue-800 break-all">
                            {{ $verificationUrl }}
                        </a>
                    </div>
                    @if($showQrCode)
                    <div class="mt-3">
                        <div class="qr-code bg-white p-4 rounded border inline-block">
                            {!! QrCode::size(120)->generate($verificationUrl) !!}
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Scan to verify certificate</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        @if($certificate->special_notes)
        <div class="mt-6 pt-4 border-t border-gray-200">
            <h4 class="font-medium text-gray-700 mb-2">Special Notes</h4>
            <p class="text-sm text-gray-600">{{ $certificate->special_notes }}</p>
        </div>
        @endif
    </div>
    @endif
</div>

{{-- Print Styles --}}
<style>
@media print {
    .print-mode .certificate-actions,
    .print-mode .certificate-details {
        display: none !important;
    }
    
    .print-mode .certificate-container {
        width: 100%;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .print-mode body {
        margin: 0;
        padding: 0;
    }
}
</style>

</div>
