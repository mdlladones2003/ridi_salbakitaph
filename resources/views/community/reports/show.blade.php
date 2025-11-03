<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 py-8 mt-20">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('community.map') }}" class="btn btn-ghost btn-sm gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Map
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Report Header Card -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    @php
                                        $typeColors = [
                                            'flood' => 'badge-info',
                                            'fire' => 'badge-error',
                                            'earthquake' => 'badge-warning',
                                            'typhoon' => 'badge-primary',
                                            'landslide' => 'badge-warning',
                                        ];
                                        $typeBadge = $typeColors[$report->type] ?? 'badge-primary';

                                        $severityColors = [
                                            'critical' => 'badge-error',
                                            'high' => 'badge-warning',
                                            'medium' => 'badge-info',
                                            'low' => 'badge-success'
                                        ];
                                        $severityBadge = $severityColors[$report->severity] ?? 'badge-ghost';
                                    @endphp
                                    <span class="badge {{ $typeBadge }} badge-lg">{{ ucfirst($report->type) }}</span>
                                    <span class="badge {{ $severityBadge }} badge-lg">{{ ucfirst($report->severity) }}</span>

                                    @if($report->status === 'verified')
                                        <span class="badge badge-success gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Verified ({{ $report->verification_count }})
                                        </span>
                                    @elseif($report->status === 'pending')
                                        <span class="badge badge-warning">Pending Review</span>
                                    @elseif($report->status === 'investigating')
                                        <span class="badge badge-info">Under Investigation</span>
                                    @elseif($report->status === 'resolved')
                                        <span class="badge badge-success">Resolved</span>
                                    @endif
                                </div>

                                <h1 class="text-3xl font-bold mb-2">{{ ucfirst($report->type) }} Report</h1>

                                <div class="flex flex-wrap items-center gap-4 text-sm opacity-70">
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>{{ $report->user->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $report->reported_at->format('M d, Y g:i A') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>{{ $report->barangay->name ?? 'Unknown Location' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="divider my-2"></div>

                        <!-- Content -->
                        <div class="prose max-w-none">
                            <h3 class="text-lg font-semibold mb-2">Report Details</h3>
                            <p class="whitespace-pre-wrap text-base-content/80">{{ $report->content }}</p>
                        </div>

                        @if($report->affected_count > 0)
                            <div class="alert alert-warning mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span><strong>{{ $report->affected_count }}</strong> people affected</span>
                            </div>
                        @endif

                        @if($report->media && count($report->media) > 0)
                            <div class="mt-6">
                                <h3 class="text-lg font-semibold mb-3">Media</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($report->media as $mediaItem)
                                        <a href="{{ Storage::url($mediaItem) }}" target="_blank" class="group">
                                            <div class="aspect-square rounded-lg overflow-hidden border-2 border-base-300 hover:border-primary transition-colors">
                                                <img src="{{ Storage::url($mediaItem) }}" alt="Report media" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($report->resolved_at)
                            <div class="alert alert-success mt-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Resolved on <strong>{{ $report->resolved_at->format('M d, Y g:i A') }}</strong></span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Map Card -->
                @if($report->latitude && $report->longitude)
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <h3 class="card-title text-lg mb-3">Location</h3>
                            <div id="report-map" class="w-full h-64 rounded-lg"></div>
                        </div>
                    </div>
                @endif

                <!-- Verifications -->
                @if($report->verifications && $report->verifications->count() > 0)
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <h3 class="card-title text-lg mb-3">
                                Community Verifications
                                <span class="badge badge-primary">{{ $report->verifications->count() }}</span>
                            </h3>
                            <div class="space-y-3">
                                @foreach($report->verifications as $verification)
                                    <div class="flex items-start gap-3 p-3 bg-base-200 rounded-lg">
                                        <div class="avatar placeholder">
                                            <div class="bg-primary text-primary-content rounded-full w-10">
                                                <span>{{ substr($verification->user->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-semibold">{{ $verification->user->name }}</div>
                                            <div class="text-xs opacity-60">{{ $verification->created_at->diffForHumans() }}</div>
                                            @if($verification->comment)
                                                <p class="text-sm mt-1">{{ $verification->comment }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Report Details Card -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title text-lg mb-4">Report Information</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-xs opacity-60 mb-1">Report ID</div>
                                <div class="font-mono text-sm">{{ $report->report_id }}</div>
                            </div>
                            <div class="divider my-2"></div>
                            <div>
                                <div class="text-xs opacity-60 mb-1">Status</div>
                                <div class="font-semibold">{{ ucfirst($report->status) }}</div>
                            </div>
                            <div class="divider my-2"></div>
                            <div>
                                <div class="text-xs opacity-60 mb-1">Type</div>
                                <div class="font-semibold">{{ ucfirst($report->type) }}</div>
                            </div>
                            <div class="divider my-2"></div>
                            <div>
                                <div class="text-xs opacity-60 mb-1">Severity</div>
                                <div class="font-semibold">{{ ucfirst($report->severity) }}</div>
                            </div>
                            <div class="divider my-2"></div>
                            <div>
                                <div class="text-xs opacity-60 mb-1">Location</div>
                                <div class="font-semibold">{{ $report->barangay->name ?? 'N/A' }}</div>
                                @if($report->latitude && $report->longitude)
                                    <div class="text-xs opacity-60 mt-1">
                                        {{ number_format($report->latitude, 6) }}, {{ number_format($report->longitude, 6) }}
                                    </div>
                                @endif
                            </div>
                            <div class="divider my-2"></div>
                            <div>
                                <div class="text-xs opacity-60 mb-1">Reported</div>
                                <div class="text-sm">{{ $report->reported_at->diffForHumans() }}</div>
                                <div class="text-xs opacity-60 mt-1">{{ $report->reported_at->format('M d, Y g:i A') }}</div>
                            </div>
                            @if($report->affected_count > 0)
                                <div class="divider my-2"></div>
                                <div>
                                    <div class="text-xs opacity-60 mb-1">Affected People</div>
                                    <div class="font-semibold text-warning">{{ $report->affected_count }}</div>
                                </div>
                            @endif
                            @if($report->verification_count > 0)
                                <div class="divider my-2"></div>
                                <div>
                                    <div class="text-xs opacity-60 mb-1">Verifications</div>
                                    <div class="font-semibold text-success">{{ $report->verification_count }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reporter Info Card -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title text-lg mb-4">Reported By</h3>
                        <div class="flex items-center gap-3">
                            <div class="avatar placeholder">
                                <div class="bg-primary text-primary-content rounded-full w-12">
                                    <span class="text-xl">{{ substr($report->user->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="font-semibold">{{ $report->user->name }}</div>
                                <div class="text-xs opacity-60">{{ $report->user->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title text-lg mb-4">Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('community.map') }}" class="btn btn-outline btn-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                                View on Map
                            </a>
                            <button onclick="window.print()" class="btn btn-ghost btn-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($report->latitude && $report->longitude)
        @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const reportMap = L.map('report-map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(reportMap);

                // Add marker
                L.marker([{{ $report->latitude }}, {{ $report->longitude }}], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: '<div class="w-8 h-8 bg-primary rounded-full border-4 border-white shadow-lg"></div>',
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    })
                }).addTo(reportMap)
                .bindPopup(`
                    <div class="p-2">
                        <div class="badge badge-primary badge-sm mb-2">{{ ucfirst($report->type) }}</div>
                        <h3 class="font-bold text-sm">{{ ucfirst($report->type) }} Report</h3>
                        <p class="text-xs opacity-60 mt-1">{{ $report->barangay->name ?? 'Unknown' }}</p>
                    </div>
                `).openPopup();
            });
        </script>

        <style>
            .custom-marker {
                background: transparent;
                border: none;
            }

            .leaflet-popup-content-wrapper {
                border-radius: 0.5rem;
            }

            @media print {
                .btn, nav, header, footer {
                    display: none !important;
                }
            }
        </style>
        @endpush
    @endif
</x-app-layout>
