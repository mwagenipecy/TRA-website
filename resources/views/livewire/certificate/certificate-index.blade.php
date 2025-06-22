<div>
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Certificates</h1>
        <div class="flex space-x-4">


        <a href="{{ route('certificates.create') }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-lg font-semibold transition duration-200">
                <i class="fas fa-plus mr-2"></i>Issue Certificate
            </a>


            <a href="{{ route('certificates.verify', 34) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                <i class="fas fa-shield-alt mr-2"></i>Verify Certificate
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div>
                <input type="text" wire:model="search" placeholder="Search certificates..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            </div>
            <div>
                <select wire:model="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="revoked">Revoked</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
            <div>
                <select wire:model="typeFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    <option value="">All Types</option>
                    <option value="completion">Completion</option>
                    <option value="participation">Participation</option>
                    <option value="achievement">Achievement</option>
                    <option value="recognition">Recognition</option>
                </select>
            </div>
            <div>
                <select wire:model="yearFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    <option value="">All Years</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->role !== 'student')
            <div>
                <select wire:model="eventFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    <option value="">All Events</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->title }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            @if(auth()->user()->role === 'tra_officer')
            <div>
                <select wire:model="institutionFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    <option value="">All Institutions</option>
                    @foreach($institutions as $institution)
                        <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>
    </div>

    {{-- Certificate Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @forelse($certificates as $certificate)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
            {{-- Certificate Header --}}
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-black p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg">{{ $certificate->title }}</h3>
                        <p class="text-sm opacity-90">{{ $certificate->certificate_code }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-white
                            @if($certificate->status === 'active') bg-green-500
                            @elseif($certificate->status === 'revoked') bg-red-500
                            @else bg-gray-500 @endif">
                            @if($certificate->isExpired() && $certificate->status === 'active')
                                Expired
                            @else
                                {{ ucfirst($certificate->status) }}
                            @endif
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-white
                            @if($certificate->type === 'completion') bg-blue-500
                            @elseif($certificate->type === 'participation') bg-green-500
                            @elseif($certificate->type === 'achievement') bg-purple-500
                            @else bg-indigo-500 @endif">
                            {{ ucfirst($certificate->type) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Certificate Body --}}
            <div class="p-4">
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-600">Recipient:</span>
                        <div class="text-sm text-gray-900">{{ $certificate->user->name }}</div>
                    </div>
                    
                    @if($certificate->event)
                    <div>
                        <span class="text-sm font-medium text-gray-600">Event:</span>
                        <div class="text-sm text-gray-900">{{ $certificate->event->title }}</div>
                    </div>
                    @endif
                    
                    <div>
                        <span class="text-sm font-medium text-gray-600">Issue Date:</span>
                        <div class="text-sm text-gray-900">{{ $certificate->issue_date->format('M d, Y') }}</div>
                    </div>
                    
                    @if($certificate->expiry_date)
                    <div>
                        <span class="text-sm font-medium text-gray-600">Expires:</span>
                        <div class="text-sm {{ $certificate->isExpired() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $certificate->expiry_date->format('M d, Y') }}
                            @if($certificate->isExpired())
                                <i class="fas fa-exclamation-triangle ml-1"></i>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    @if(auth()->user()->role !== 'student')
                    <div>
                        <span class="text-sm font-medium text-gray-600">Institution:</span>
                        <div class="text-sm text-gray-900">{{ $certificate->institution->name }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Certificate Actions --}}
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="flex space-x-2">
                        <a href="{{ route('certificates.show', $certificate->id) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                        @if($certificate->file_path)
                        <button wire:click="downloadCertificate({{ $certificate->id }})" 
                                class="text-green-600 hover:text-green-800 text-sm">
                            <i class="fas fa-download mr-1"></i>Download
                        </button>
                        @endif
                    </div>
                    
                    @if((auth()->user()->role === 'tra_officer' || $certificate->issued_by === auth()->id()) && $certificate->status === 'active')
                    <button wire:click="revokeCertificate({{ $certificate->id }})" 
                            onclick="confirm('Are you sure you want to revoke this certificate?') || event.stopImmediatePropagation()"
                            class="text-red-600 hover:text-red-800 text-sm">
                        <i class="fas fa-ban mr-1"></i>Revoke
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-certificate text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No certificates found</h3>
            <p class="text-gray-500">
                @if(in_array(auth()->user()->role, ['leader', 'supervisor', 'tra_officer']))
                    Start by issuing your first certificate.
                @else
                    Certificates will appear here once issued to you.
                @endif
            </p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        {{ $certificates->links() }}
    </div>
</div>

</div>
