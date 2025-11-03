<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-primary">
                {{ $evacuationCenter->name }}
            </h1>
            <span class="badge {{ $evacuationCenter->is_active ? 'badge-success' : 'badge-error' }}">
                {{ $evacuationCenter->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <p class="text-base-content/70">{{ $evacuationCenter->address }}</p>

        @if(session('success'))
            <div class="alert alert-success shadow">{{ session('success') }}</div>
        @endif

        {{-- Info Section --}}
        <div class="bg-base-100 rounded-lg shadow p-6 space-y-6">

            {{-- Map --}}
            <div>
                <h2 class="text-xl font-semibold text-secondary mb-2">Location Map</h2>
                <div id="map" class="rounded-lg border border-base-300" style="height: 350px;"></div>
            </div>

            {{-- Coordinates --}}
            <div>
                <h2 class="text-xl font-semibold mb-2">Coordinates</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-base-content/70">Latitude</p>
                        <p class="font-semibold">{{ $evacuationCenter->latitude }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-base-content/70">Longitude</p>
                        <p class="font-semibold">{{ $evacuationCenter->longitude }}</p>
                    </div>
                </div>
            </div>

            {{-- Stats Section --}}
            <div>
                <h2 class="text-xl font-semibold mb-3">Center Statistics</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                    <div class="bg-info text-info-content px-4 py-3 rounded-lg">
                        <div class="text-lg font-bold">{{ $evacuationCenter->capacity }}</div>
                        <div>Capacity</div>
                    </div>
                    <div class="bg-primary text-primary-content px-4 py-3 rounded-lg">
                        <div class="text-lg font-bold">{{ $evacuationCenter->current_occupancy }}</div>
                        <div>Current</div>
                    </div>
                    <div class="bg-success text-success-content px-4 py-3 rounded-lg">
                        <div class="text-lg font-bold">{{ number_format($occupancyPercentage, 1) }}%</div>
                        <div>Occupancy</div>
                    </div>
                    <div class="bg-warning text-warning-content px-4 py-3 rounded-lg">
                        <div class="text-lg font-bold">{{ $availableSpace }}</div>
                        <div>Available</div>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="mt-4">
                    <progress class="progress progress-primary w-full"
                        value="{{ $occupancyPercentage }}" max="100"></progress>
                </div>
            </div>

            {{-- Facilities --}}
            <div>
                <h2 class="text-xl font-semibold mb-3">Facilities</h2>
                @if(empty($evacuationCenter->facilities))
                    <p class="text-base-content/60 italic">No facilities listed.</p>
                @else
                    <div class="flex flex-wrap gap-2">
                        @foreach($evacuationCenter->facilities as $facility)
                            <span class="badge badge-outline capitalize">{{ $facility }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Contact --}}
            <div>
                <h2 class="text-xl font-semibold mb-2">Contact Information</h2>
                <p class="text-base-content">
                    {{ $evacuationCenter->contact_number ?: 'No contact number available.' }}
                </p>
            </div>

            {{-- Actions --}}
            <div class="pt-4 flex flex-wrap gap-3">
                <a href="{{ route('admin.evacuation-centers.edit', $evacuationCenter) }}"
                    class="btn btn-primary flex-1 sm:flex-none">
                    Edit Center
                </a>
                <a href="{{ route('admin.evacuation-centers.index') }}"
                    class="btn btn-outline flex-1 sm:flex-none">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    {{-- Leaflet Map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lat = {{ $evacuationCenter->latitude }};
            const lng = {{ $evacuationCenter->longitude }};

            const map = L.map('map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const marker = L.marker([lat, lng]).addTo(map)
                .bindPopup(`<strong>{{ $evacuationCenter->name }}</strong><br>{{ $evacuationCenter->address }}`)
                .openPopup();
        });
    </script>
</x-app-layout>
