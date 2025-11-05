<x-app-layout>
    <div class="px-8 py-6 max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-bold text-base-content flex items-center gap-2">
                        <x-lucide-building class="w-6 h-6" />
                        {{ $evacuationCenter->name }}
                    </h1>
                    <span class="badge {{ $evacuationCenter->is_active ? 'badge-success' : 'badge-error' }} px-4 py-2 text-sm">
                        {{ $evacuationCenter->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <p class="text-base-content/70 text-sm flex items-center gap-2 mt-1">
                    {{ $evacuationCenter->address }}
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.evacuation-centers.edit', $evacuationCenter) }}" class="btn bg-blue-600 hover:bg-blue-700 text-white btn-sm">
                    <x-lucide-pencil class="w-4 h-4" /> Edit
                </a>
                <a href="{{ route('admin.evacuation-centers.index') }}" class="btn btn-outline btn-sm">
                    <x-lucide-arrow-left class="w-4 h-4 mr-1" /> Back to List
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow flex items-center gap-2">
                <x-lucide-check-circle class="w-5 h-5" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Main Card -->
        <div class="bg-white border border-base-300 shadow-sm rounded-md p-8 space-y-10">
            <!-- Location Section -->
            <section>
                <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2 border-b border-base-200 pb-2">
                    <x-lucide-map class="w-5 h-5 text-blue-600" /> Location
                </h2>
                <div id="map" class="rounded-lg border border-base-300" style="height: 350px;"></div>
            </section>

            <!-- Statistics Section -->
            <section>
                <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2 border-b border-base-200 pb-2">
                    <x-lucide-bar-chart-3 class="w-5 h-5 text-blue-600" /> Center Statistics
                </h2>

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
            </section>

            <!-- Facilities Section -->
            <section>
                <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2 border-b border-base-200 pb-2">
                    <x-lucide-wrench class="w-5 h-5 text-blue-600" /> Facilities
                </h2>

                @if(empty($evacuationCenter->facilities))
                    <p class="text-base-content/60 italic">No facilities listed.</p>
                @else
                    <div class="flex flex-wrap gap-2">
                        @foreach($evacuationCenter->facilities as $facility)
                            <span class="badge badge-outline capitalize px-3 py-2">{{ $facility }}</span>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Contact Section -->
            <section>
                <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2 border-b border-base-200 pb-2">
                    <x-lucide-phone class="w-5 h-5 text-blue-600" /> Contact Information
                </h2>
                <p class="text-base-content">
                    {{ $evacuationCenter->contact_number ?: 'No contact number available.' }}
                </p>
            </section>
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

            L.marker([lat, lng])
                .addTo(map)
                .bindPopup(`<strong>{{ $evacuationCenter->name }}</strong><br>{{ $evacuationCenter->address }}`)
                .openPopup();
        });
    </script>
</x-app-layout>
