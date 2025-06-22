<div>
<div class="certificate-gallery">
    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Certificate Gallery</h1>
            <p class="text-gray-600 mt-2">
                @if(auth()->user()->role === 'student')
                    Your personal certificate collection
                @elseif(in_array(auth()->user()->role, ['leader', 'supervisor']))
                    Institution certificate gallery
                @else
                    System-wide certificate gallery
                @endif
            </p>
        </div>
        
        <div class="text-sm text-gray-600">
            <i class="fas fa-certificate mr-2"></i>{{ $certificates->total() }} certificates found
        </div>
    </div>

    {{-- Gallery Controls --}}
    <div class="gallery-controls bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-wrap justify-between items-center gap-4">
            {{-- View Mode Toggle --}}
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600 font-medium">View:</span>
                <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                    <button wire:click="$set('viewMode', 'grid')" 
                            class="px-4 py-2 text-sm font-medium transition duration-200 {{ $viewMode === 'grid' ? 'bg-yellow-500 text-black' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-th mr-1"></i>Grid
                    </button>
                    <button wire:click="$set('viewMode', 'list')" 
                            class="px-4 py-2 text-sm font-medium transition duration-200 {{ $viewMode === 'list' ? 'bg-yellow-500 text-black' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-list mr-1"></i>List
                    </button>
                    <button wire:click="$set('viewMode', 'carousel')" 
                            class="px-4 py-2 text-sm font-medium transition duration-200 {{ $viewMode === 'carousel' ? 'bg-yellow-500 text-black' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-images mr-1"></i>Carousel
                    </button>
                </div>
            </div>
            
            {{-- Filters --}}
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-600 font-medium">Type:</label>
                    <select wire:model="filterType" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">All Types</option>
                        <option value="completion">Completion</option>
                        <option value="participation">Participation</option>
                        <option value="achievement">Achievement</option>
                        <option value="recognition">Recognition</option>
                    </select>
                </div>
                
                <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-600 font-medium">Status:</label>
                    <select wire:model="filterStatus" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="expired">Expired</option>
                        <option value="revoked">Revoked</option>
                    </select>
                </div>
                
                <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-600 font-medium">Sort:</label>
                    <select wire:model="sortBy" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="issue_date">Issue Date</option>
                        <option value="title">Title</option>
                        <option value="type">Type</option>
                        <option value="created_at">Created</option>
                    </select>
                    
                    <button wire:click="sortBy('{{ $sortBy }}')" 
                            class="text-sm border border-gray-300 rounded-lg px-3 py-2 hover:bg-gray-50 transition duration-200">
                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Certificate Display --}}
    @if($certificates->count() > 0)
        @if($viewMode === 'grid')
            {{-- Grid View --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
                @foreach($certificates as $certificate)
                <div class="certificate-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1"
                     wire:click="viewCertificate({{ $certificate->id }})">
                    {{-- Certificate Preview --}}
                    <div class="certificate-preview h-40 relative overflow-hidden
                        @if($certificate->type === 'completion') bg-gradient-to-br from-blue-400 to-blue-500
                        @elseif($certificate->type === 'participation') bg-gradient-to-br from-green-400 to-green-500
                        @elseif($certificate->type === 'achievement') bg-gradient-to-br from-purple-400 to-purple-500
                        @elseif($certificate->type === 'recognition') bg-gradient-to-br from-indigo-400 to-indigo-500
                        @else bg-gradient-to-br from-yellow-400 to-yellow-500 @endif">
                        
                        {{-- Background pattern --}}
                        <div class="absolute inset-0 opacity-20">
                            <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="grid-{{ $certificate->id }}" width="10" height="10" patternUnits="userSpaceOnUse">
                                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5"/>
                                    </pattern>
                                </defs>
                                <rect width="60" height="60" fill="url(#grid-{{ $certificate->id }})" />
                            </svg>
                        </div>
                        
                        <div class="relative h-full flex items-center justify-center text-white">
                            <div class="text-center">
                                <i class="fas fa-certificate text-4xl mb-2"></i>
                                <p class="text-sm font-semibold uppercase tracking-wide">{{ $certificate->type }}</p>
                            </div>
                        </div>
                        
                        {{-- Status indicator --}}
                        <div class="absolute top-3 right-3">
                            @if($certificate->status === 'active' && !$certificate->isExpired())
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                            @elseif($certificate->isExpired())
                                <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-ban text-white text-sm"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Certificate Info --}}
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 text-sm mb-2 line-clamp-2 leading-tight">{{ $certificate->title }}</h3>
                        <p class="text-xs text-gray-600 mb-2 font-medium">{{ $certificate->user->name }}</p>
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>{{ $certificate->issue_date->format('M d, Y') }}</span>
                            <span class="font-mono">{{ substr($certificate->certificate_code, -4) }}</span>
                        </div>
                        
                        {{-- Quick actions --}}
                        <div class="mt-3 flex space-x-2">
                            <a href="{{ route('certificates.show', $certificate->id) }}" 
                               class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-black text-center py-1 px-2 rounded text-xs font-medium transition duration-200"
                               onclick="event.stopPropagation()">
                                View
                            </a>
                            @if($certificate->file_path)
                            <button class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-2 rounded text-xs transition duration-200"
                                    onclick="event.stopPropagation()">
                                <i class="fas fa-download"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        
        @elseif($viewMode === 'list')
            {{-- List View --}}
            <div class="space-y-4 mb-6">
                @foreach($certificates as $certificate)
                <div class="certificate-list-item bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 cursor-pointer"
                     wire:click="viewCertificate({{ $certificate->id }})">
                    <div class="flex items-center p-6">
                        {{-- Certificate Icon --}}
                        <div class="certificate-icon w-16 h-16 rounded-lg flex items-center justify-center text-white mr-6 flex-shrink-0
                            @if($certificate->type === 'completion') bg-gradient-to-br from-blue-400 to-blue-500
                            @elseif($certificate->type === 'participation') bg-gradient-to-br from-green-400 to-green-500
                            @elseif($certificate->type === 'achievement') bg-gradient-to-br from-purple-400 to-purple-500
                            @elseif($certificate->type === 'recognition') bg-gradient-to-br from-indigo-400 to-indigo-500
                            @else bg-gradient-to-br from-yellow-400 to-yellow-500 @endif">
                            <i class="fas fa-certificate text-2xl"></i>
                        </div>
                        
                        {{-- Certificate Details --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 text-lg mb-1 truncate">{{ $certificate->title }}</h3>
                            <p class="text-sm text-gray-600 mb-2">Recipient: <span class="font-medium">{{ $certificate->user->name }}</span></p>
                            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100">
                                    <i class="fas fa-tag mr-1"></i>{{ ucfirst($certificate->type) }}
                                </span>
                                <span class="inline-flex items-center">
                                    <i class="fas fa-calendar mr-1"></i>{{ $certificate->issue_date->format('M d, Y') }}
                                </span>
                                <span class="inline-flex items-center font-mono">
                                    <i class="fas fa-barcode mr-1"></i>{{ $certificate->certificate_code }}
                                </span>
                                @if($certificate->institution)
                                <span class="inline-flex items-center">
                                    <i class="fas fa-university mr-1"></i>{{ $certificate->institution->name }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Certificate Status --}}
                        <div class="certificate-status ml-6">
                            @if($certificate->status === 'active' && !$certificate->isExpired())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Valid
                                </span>
                            @elseif($certificate->isExpired())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-clock mr-1"></i>Expired
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-ban mr-1"></i>Revoked
                                </span>
                            @endif
                            
                            {{-- Quick actions --}}
                            <div class="mt-2 flex space-x-2">
                                <a href="{{ route('certificates.show', $certificate->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-800 text-sm"
                                   onclick="event.stopPropagation()">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                @if($certificate->file_path)
                                <button class="text-blue-600 hover:text-blue-800 text-sm"
                                        onclick="event.stopPropagation()">
                                    <i class="fas fa-download mr-1"></i>Download
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        
        @else
            {{-- Carousel View --}}
            <div class="certificate-carousel mb-6" x-data="{ 
                currentSlide: 0, 
                totalSlides: {{ $certificates->count() }},
                nextSlide() {
                    this.currentSlide = this.currentSlide < this.totalSlides - 1 ? this.currentSlide + 1 : 0;
                },
                prevSlide() {
                    this.currentSlide = this.currentSlide > 0 ? this.currentSlide - 1 : this.totalSlides - 1;
                },
                goToSlide(index) {
                    this.currentSlide = index;
                }
            }">
                <div class="relative">
                    <div class="overflow-hidden rounded-lg bg-gray-100">
                        <div class="flex transition-transform duration-500 ease-in-out" 
                             :style="`transform: translateX(-${currentSlide * 100}%)`">
                            @foreach($certificates as $index => $certificate)
                            <div class="w-full flex-shrink-0">
                                <div class="bg-white p-12 text-center min-h-96 flex items-center justify-center">
                                    <div class="max-w-md mx-auto">
                                        {{-- Certificate Preview Large --}}
                                        <div class="certificate-preview-large h-64 rounded-lg flex items-center justify-center text-white mb-6 relative overflow-hidden
                                            @if($certificate->type === 'completion') bg-gradient-to-br from-blue-400 to-blue-500
                                            @elseif($certificate->type === 'participation') bg-gradient-to-br from-green-400 to-green-500
                                            @elseif($certificate->type === 'achievement') bg-gradient-to-br from-purple-400 to-purple-500
                                            @elseif($certificate->type === 'recognition') bg-gradient-to-br from-indigo-400 to-indigo-500
                                            @else bg-gradient-to-br from-yellow-400 to-yellow-500 @endif">
                                            
                                            {{-- Background pattern --}}
                                            <div class="absolute inset-0 opacity-20">
                                                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                                    <defs>
                                                        <pattern id="carousel-pattern-{{ $index }}" width="10" height="10" patternUnits="userSpaceOnUse">
                                                            <circle cx="5" cy="5" r="1" fill="currentColor"/>
                                                        </pattern>
                                                    </defs>
                                                    <rect width="100" height="100" fill="url(#carousel-pattern-{{ $index }})" />
                                                </svg>
                                            </div>
                                            
                                            <div class="relative text-center">
                                                <i class="fas fa-certificate text-6xl mb-4"></i>
                                                <h3 class="text-xl font-bold mb-2">{{ $certificate->title }}</h3>
                                                <p class="text-sm opacity-90">{{ $certificate->user->name }}</p>
                                                <p class="text-xs mt-2 opacity-75">{{ ucfirst($certificate->type) }} • {{ $certificate->issue_date->format('M Y') }}</p>
                                            </div>
                                        </div>
                                        
                                        {{-- Certificate Details --}}
                                        <div class="space-y-3">
                                            <h4 class="text-lg font-semibold text-gray-800">{{ $certificate->title }}</h4>
                                            <p class="text-gray-600">Awarded to <span class="font-medium">{{ $certificate->user->name }}</span></p>
                                            <p class="text-sm text-gray-500">{{ $certificate->certificate_code }}</p>
                                            
                                            {{-- Status badge --}}
                                            @if($certificate->status === 'active' && !$certificate->isExpired())
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>Valid Certificate
                                                </span>
                                            @elseif($certificate->isExpired())
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-clock mr-1"></i>Expired Certificate
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-ban mr-1"></i>Revoked Certificate
                                                </span>
                                            @endif
                                        </div>
                                        
                                        {{-- Action buttons --}}
                                        <div class="mt-6 flex space-x-3 justify-center">
                                            <button wire:click="viewCertificate({{ $certificate->id }})" 
                                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition duration-200">
                                                <i class="fas fa-search mr-2"></i>Preview
                                            </button>
                                            <a href="{{ route('certificates.show', $certificate->id) }}" 
                                               class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg font-semibold text-sm transition duration-200">
                                                <i class="fas fa-eye mr-2"></i>View Full
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- Carousel Controls --}}
                    @if($certificates->count() > 1)
                    <button @click="prevSlide()" 
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full p-3 shadow-lg transition duration-200">
                        <i class="fas fa-chevron-left text-gray-600"></i>
                    </button>
                    
                    <button @click="nextSlide()" 
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full p-3 shadow-lg transition duration-200">
                        <i class="fas fa-chevron-right text-gray-600"></i>
                    </button>
                    
                    {{-- Carousel Indicators --}}
                    <div class="flex justify-center space-x-2 mt-6">
                        @foreach($certificates as $index => $cert)
                        <button @click="goToSlide({{ $index }})" 
                                class="w-3 h-3 rounded-full transition-all duration-200"
                                :class="currentSlide === {{ $index }} ? 'bg-yellow-500 scale-125' : 'bg-gray-300 hover:bg-gray-400'">
                        </button>
                        @endforeach
                    </div>
                    
                    {{-- Slide counter --}}
                    <div class="text-center mt-4">
                        <span class="text-sm text-gray-600">
                            <span x-text="currentSlide + 1"></span> of {{ $certificates->count() }} certificates
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        @endif

    @else
        {{-- Empty State --}}
        <div class="empty-state bg-white rounded-lg shadow-md p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-certificate text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">No certificates found</h3>
                <p class="text-gray-600 mb-6">
                    @if($filterType || $filterStatus)
                        No certificates match your current filters. Try adjusting your search criteria.
                    @elseif(auth()->user()->role === 'student')
                        You haven't received any certificates yet. Participate in events and complete courses to earn certificates.
                    @else
                        No certificates have been issued yet. Start by issuing certificates to deserving participants.
                    @endif
                </p>
                
                <div class="flex flex-wrap justify-center gap-3">
                    @if($filterType || $filterStatus)
                    <button wire:click="$set('filterType', '')" 
                            wire:click="$set('filterStatus', 'active')"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                        <i class="fas fa-times mr-2"></i>Clear Filters
                    </button>
                    @endif
                    
                    @if(in_array(auth()->user()->role, ['leader', 'supervisor', 'tra_officer']))
                    <a href="{{ route('certificates.create') }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg text-sm font-semibold transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Issue Certificate
                    </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Pagination --}}
    @if($certificates->hasPages())
    <div class="pagination-wrapper bg-white rounded-lg shadow-md p-6">
        {{ $certificates->links() }}
    </div>
    @endif

    {{-- Certificate Quick Preview Modal --}}
    @if($selectedCertificate)
    <div class="fixed inset-0 z-50 overflow-y-auto" 
         x-data="{ show: false }" 
         x-show="show" 
         x-on:open-certificate-modal.window="show = true"
         x-on:close-certificate-modal.window="show = false"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background overlay --}}
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 @click="$wire.closeCertificateModal()"></div>
            
            {{-- Modal content --}}
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                {{-- Modal header --}}
                <div class="bg-white px-6 pt-6 pb-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-900">Certificate Preview</h3>
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-600">{{ $selectedCertificate->certificate_code }}</span>
                            <button @click="$wire.closeCertificateModal()" 
                                    class="text-gray-400 hover:text-gray-600 transition duration-200">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                {{-- Modal body --}}
                <div class="bg-gray-50 px-6 py-6">
                    <div class="certificate-modal-content">
                        @if($selectedCertificate)
                            @livewire('certificate.certificate-view', [
                                'id' => $selectedCertificate->id, 
                                'viewMode' => 'preview', 
                                'showActions' => false
                            ], key('modal-cert-' . $selectedCertificate->id))
                        @endif
                    </div>
                </div>
                
                {{-- Modal footer --}}
                <div class="bg-white px-6 py-4 border-t border-gray-200 sm:flex sm:flex-row-reverse">
                    <a href="{{ route('certificates.show', $selectedCertificate->id) }}" 
                       class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-black hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-200">
                        <i class="fas fa-external-link-alt mr-2"></i>View Full Certificate
                    </a>
                    
                    @if($selectedCertificate->file_path)
                    <button class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition duration-200">
                        <i class="fas fa-download mr-2"></i>Download PDF
                    </button>
                    @endif
                    
                    <button @click="$wire.closeCertificateModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm transition duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Custom styles for certificate gallery --}}
<style>
.certificate-gallery {
    font-family: 'Inter', sans-serif;
}

.certificate-card {
    transition: all 0.3s ease;
}

.certificate-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.certificate-list-item:hover {
    transform: translateX(4px);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom scrollbar for carousel */
.certificate-carousel {
    scrollbar-width: thin;
    scrollbar-color: #EAB308 #f1f1f1;
}

.certificate-carousel::-webkit-scrollbar {
    height: 6px;
}

.certificate-carousel::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.certificate-carousel::-webkit-scrollbar-thumb {
    background: #EAB308;
    border-radius: 3px;
}

.certificate-carousel::-webkit-scrollbar-thumb:hover {
    background: #D97706;
}

/* Animation for modal */
[x-cloak] {
    display: none !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .certificate-preview {
        height: 120px;
    }
    
    .certificate-preview-large {
        height: 200px;
    }
    
    .gallery-controls .flex-wrap > div {
        width: 100%;
        justify-content: center;
    }
    
    .gallery-controls select {
        width: 100%;
        max-width: 200px;
    }
}

/* Print styles */
@media print {
    .gallery-controls,
    .pagination-wrapper,
    .certificate-actions {
        display: none !important;
    }
}
</style>

{{-- JavaScript for enhanced interactions --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-advance carousel (optional)
    const carousels = document.querySelectorAll('[x-data*="currentSlide"]');
    carousels.forEach(carousel => {
        // Auto-advance every 5 seconds (uncomment if desired)
        // setInterval(() => {
        //     carousel.__x.$data.nextSlide();
        // }, 5000);
    });
    
    // Keyboard navigation for carousel
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            document.querySelector('[x-data*="currentSlide"]')?.__x?.$data.prevSlide();
        } else if (e.key === 'ArrowRight') {
            document.querySelector('[x-data*="currentSlide"]')?.__x?.$data.nextSlide();
        }
    });
    
    // Lazy loading for certificate images (if implemented)
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('loaded');
            }
        });
    });
    
    document.querySelectorAll('.certificate-card, .certificate-list-item').forEach(card => {
        observer.observe(card);
    });
});
</script>


</div>
