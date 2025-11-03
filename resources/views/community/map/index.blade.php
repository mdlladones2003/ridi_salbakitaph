<x-app-layout>
    <div class="h-screen flex flex-col mt-20">
        <div class="bg-base-100 border-b border-base-300 px-4 py-3">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Disaster Map</h1>
                    <p class="text-xs opacity-60 mt-0.5">Real-time incident reports visualization</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="stats shadow-sm">
                        <div class="stat py-2 px-4">
                            <div class="stat-title text-xs">Total Reports</div>
                            <div class="stat-value text-xl text-primary">{{ $reports->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1 flex overflow-hidden">
            <!-- Sidebar -->
            <div class="w-80 bg-base-100 border-r border-base-300 overflow-y-auto">
                <div class="p-4 border-b border-base-300">
                    <h3 class="font-semibold mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        Map Legend
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 rounded-full bg-primary"></div>
                            <span>Incident Reports</span>
                        </div>
                    </div>
                </div>

                <!-- Reports List -->
                <div class="p-4 space-y-3">
                    <h3 class="font-semibold text-sm mb-3">Incident Reports ({{ $reports->count() }})</h3>
                    @forelse($reports as $report)
                        <div class="card bg-base-200 shadow-sm cursor-pointer hover:shadow-md transition-shadow"
                             onclick="focusMarker({{ $report->latitude }}, {{ $report->longitude }}, {{ $report->report_id }})">
                            <div class="card-body p-3">
                                <div class="flex items-start justify-between gap-2">
                                    @if($report->status === 'verified')
                                        <div class="badge badge-success badge-xs">Verified</div>
                                    @endif
                                </div>
                                <h4 class="font-semibold text-sm line-clamp-2">{{ $report->type }}</h4>
                                <p class="text-xs opacity-70 line-clamp-2">{{ $report->content }}</p>
                                <div class="flex items-center justify-between mt-2">
                                    <div class="text-xs opacity-60">
                                        📍 {{ $report->barangay->name }}, {{ $report->barangay->municipality }}
                                    </div>
                                    <div class="text-xs opacity-60">
                                        {{ $report->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-sm opacity-60">
                            <div class="text-4xl mb-2">📋</div>
                            No reports available
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Map -->
            <div class="flex-1 relative">
                <div id="map" class="w-full h-full"></div>

                <!-- Loading -->
                <div id="map-loading" class="absolute inset-0 bg-base-100 flex items-center justify-center">
                    <span class="loading loading-spinner loading-lg text-primary"></span>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mapContainer = document.getElementById('map');
            if (!mapContainer) {
                console.error('Map container not found');
                return;
            }

            const defaultCenter = [13.4125, 123.4131];
            const map = L.map('map').setView(defaultCenter, 10);

            // Add base tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Hide loader
            const loader = document.getElementById('map-loading');
            if (loader) loader.classList.add('hidden');

            const markers = [];

            @foreach($reports as $report)
                @if(!empty($report->latitude) && !empty($report->longitude))
                {
                    let marker = L.marker([{{ $report->latitude }}, {{ $report->longitude }}], {
                        icon: L.divIcon({
                            className: 'custom-marker',
                            html: '<div class="w-6 h-6 bg-primary rounded-full border-2 border-white shadow-lg"></div>',
                            iconSize: [24, 24],
                            iconAnchor: [12, 12],
                        }),
                    }).addTo(map);

                    marker.bindPopup(`
                        <div class="p-2">
                            <div class="badge badge-primary badge-sm mb-2">{{ $report->type }}</div>
                            <h3 class="font-bold text-sm mb-1">{{ addslashes($report->type) }}</h3>
                            <p class="text-xs opacity-70 mb-1">{{ Str::limit(addslashes($report->content ?? ''), 100) }}</p>
                            <p class="text-xs opacity-60 mb-2">📍 {{ $report->barangay->name }}, {{ $report->barangay->municipality }}</p>
                            <a href="{{ route('community.reports.show', $report) }}" class="btn btn-xs btn-primary">View Report</a>
                        </div>
                    `);

                    markers.push(marker);
                }
                @endif
            @endforeach

            if (markers.length > 0) {
                const group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }

            // Functions available globally
            window.focusMarker = function(lat, lng, id) {
                map.setView([lat, lng], 15);
            };

            window.resetMapView = function() {
                if (markers.length > 0) {
                    const group = new L.featureGroup(markers);
                    map.fitBounds(group.getBounds().pad(0.1));
                } else {
                    map.setView(defaultCenter, 10);
                }
            };
        });
    </script>

    <style>
        html,
        body,
        #map {
            height: 100%;
        }

        .custom-marker {
            background: transparent;
            border: none;
        }

        .leaflet-container {
            z-index: 0 !important;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 0.5rem;
        }
    </style>
    @endpush
</x-app-layout>
