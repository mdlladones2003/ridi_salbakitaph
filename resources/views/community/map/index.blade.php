<x-app-layout>
    <div class="h-screen flex flex-col">
        <!-- Header -->
        <div class="bg-base-100 border-b border-base-300 px-4 py-3">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Disaster Map</h1>
                    <p class="text-xs opacity-60 mt-0.5">Real-time incident reports visualization (Geoapify)</p>
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

        <!-- Main Layout -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Sidebar -->
            <div class="w-80 bg-base-100 border-r border-base-300 overflow-y-auto">
                <div class="p-4 border-b border-base-300">
                    <h3 class="font-semibold mb-3 flex items-center gap-2">
                        Map Legend
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-blue-500"></div><span>Verified</span></div>
                        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-yellow-500"></div><span>Pending</span></div>
                        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-green-500"></div><span>Resolved</span></div>
                    </div>
                </div>

                <div class="p-4 space-y-3">
                    <h3 class="font-semibold text-sm mb-3">Incident Reports ({{ $reports->count() }})</h3>
                    @forelse($reports as $report)
                        <div class="card bg-base-200 shadow-sm cursor-pointer hover:shadow-md transition"
                             onclick="focusMarker({{ $report->latitude }}, {{ $report->longitude }})">
                            <div class="card-body p-3">
                                <h4 class="font-semibold text-sm line-clamp-2 capitalize">{{ $report->type }}</h4>
                                <p class="text-xs opacity-70 line-clamp-2">{{ $report->content }}</p>
                                <div class="flex justify-between mt-2 text-xs opacity-60">
                                    <span>📍 {{ $report->barangay->name }}, {{ $report->barangay->municipality }}</span>
                                    <span>{{ $report->created_at->diffForHumans() }}</span>
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
                <div id="map-loading" class="absolute inset-0 bg-base-100 flex items-center justify-center">
                    <span class="loading loading-spinner loading-lg text-primary"></span>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const map = L.map('map').setView([13.4125, 123.4131], 10);

            L.tileLayer('https://maps.geoapify.com/v1/tile/osm-carto/{z}/{x}/{y}.png?apiKey={{ config("services.geoapify.key") }}', {
                attribution: '© Geoapify, OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            const loader = document.getElementById('map-loading');
            if (loader) loader.classList.add('hidden');

            const markers = [];

            @foreach($reports as $report)
                @if(!empty($report->latitude) && !empty($report->longitude))
                    {
                        // Color code by status
                        const color = {
                            'pending': 'yellow-500',
                            'verified': 'blue-500',
                            'resolved': 'green-500'
                        }['{{ $report->status }}'] || 'gray-400';

                        let marker = L.marker([{{ $report->latitude }}, {{ $report->longitude }}], {
                            icon: L.divIcon({
                                className: 'custom-marker',
                                html: `<div class="w-5 h-5 bg-${color} rounded-full border-2 border-white shadow-md"></div>`,
                                iconSize: [20, 20],
                                iconAnchor: [10, 10],
                            }),
                        }).addTo(map);

                        marker.bindPopup(`
                            <div class="p-2">
                                <div class="badge badge-primary badge-sm mb-2">{{ ucfirst($report->type) }}</div>
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
                map.fitBounds(group.getBounds().pad(0.2));
            }

            // Focus helper
            window.focusMarker = function(lat, lng) {
                map.setView([lat, lng], 15);
            };
        });
    </script>

    <style>
        html, body, #map { height: 100%; }
        .custom-marker { background: transparent; border: none; }
        .leaflet-popup-content-wrapper { border-radius: 0.5rem; }
    </style>
    @endpush
</x-app-layout>
