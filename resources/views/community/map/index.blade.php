<x-app-layout>
    <div class="h-screen flex flex-col">
        <div class="bg-base-100 border-b border-base-300 px-4 py-3">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Disaster Map</h1>
                </div>
            </div>
        </div>

        <div class="flex-1 flex overflow-hidden">
            <div class="w-80 bg-base-100 border-r border-base-300 overflow-y-auto">
                <div class="p-4 border-b border-base-300">
                    <h3 class="font-semibold mb-3 flex items-center gap-2">Map Legend</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-blue-500"></div><span>Verified</span></div>
                        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-green-500"></div><span>Resolved</span></div>
                    </div>
                </div>

                <div class="p-4 space-y-3">
                    @php
                        $visibleReports = $reports->whereIn('status', ['verified', 'resolved']);
                    @endphp
                    <h3 class="font-semibold text-sm mb-3">Incident Reports ({{ $visibleReports->count() }})</h3>

                    @forelse($visibleReports as $report)
                        <div class="card bg-base-200 shadow-sm cursor-pointer hover:shadow-md transition"
                             onclick="focusMarker({{ $report->latitude }}, {{ $report->longitude }})">
                            <div class="card-body p-3">
                                <h4 class="font-semibold text-sm capitalize">{{ ucfirst($report->type) }}</h4>
                                <p class="text-xs opacity-70 line-clamp-2">{{ $report->content }}</p>
                                <div class="flex justify-between mt-2 text-xs opacity-60">
                                    <span>📍 {{ $report->barangay->name }}, {{ $report->barangay->municipality }}</span>
                                    <span>{{ $report->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="mt-1">
                                    <span class="badge badge-outline badge-sm capitalize">{{ $report->status }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-sm opacity-60">
                            <div class="text-4xl mb-2">📋</div>
                            No verified or resolved reports available
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Map --}}
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

            document.getElementById('map-loading')?.classList.add('hidden');

            const markers = [];

            @foreach($reports->whereIn('status', ['verified', 'resolved']) as $report)
                @if(!empty($report->latitude) && !empty($report->longitude))
                    {
                        let color = '{{ $report->status === "verified" ? "#3b82f6" : "#22c55e" }}'; // blue or green

                        const marker = L.circleMarker([{{ $report->latitude }}, {{ $report->longitude }}], {
                            radius: 8,
                            fillColor: color,
                            color: '#ffffff',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.9
                        }).addTo(map);

                        marker.bindPopup(`
                            <div class="p-2 text-sm">
                                <div class="font-semibold mb-1 capitalize">{{ ucfirst($report->type) }}</div>
                                <p class="opacity-70 mb-1">{{ Str::limit(addslashes($report->content ?? ''), 100) }}</p>
                                <p class="text-xs opacity-60 mb-2">📍 {{ $report->barangay->name }}, {{ $report->barangay->municipality }}</p>
                                <a href="{{ route('community.reports.show', $report) }}" class="btn btn-xs btn-primary">View Report</a>
                            </div>
                        `);

                        markers.push(marker);
                    }
                @endif
            @endforeach

            if (markers.length > 0) {
                const group = L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.2));
            }

            window.focusMarker = function(lat, lng) {
                map.setView([lat, lng], 15);
            };
        });
    </script>

    <style>
        html, body, #map { height: 100%; }
        .leaflet-popup-content-wrapper { border-radius: 0.5rem; }
    </style>
    @endpush
</x-app-layout>
