<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 space-y-6 mt-20">
        <h1 class="text-3xl font-bold text-primary">{{ $evacuationCenter->name }}</h1>
        <p class="text-base-content/70">{{ $evacuationCenter->address }}, {{ $evacuationCenter->barangay->name ?? 'N/A' }}</p>

        @if(session('success'))
            <div class="alert alert-success shadow">{{ session('success') }}</div>
        @endif

        <div class="bg-base-100 rounded-lg shadow p-6 space-y-4">
            <div>
                <h2 class="text-xl font-semibold text-secondary mb-1">Coordinates</h2>
                <p class="text-base-content">Latitude: {{ $evacuationCenter->latitude }}</p>
                <p class="text-base-content">Longitude: {{ $evacuationCenter->longitude }}</p>
            </div>

            <div class="flex gap-4 text-center mt-6">
                <div class="bg-info text-info-content px-4 py-3 rounded-lg flex-1">
                    <div class="text-lg font-bold">{{ $evacuationCenter->capacity }}</div>
                    <div>Capacity</div>
                </div>
                <div class="bg-primary text-primary-content px-4 py-3 rounded-lg flex-1">
                    <div class="text-lg font-bold">{{ $evacuationCenter->current_occupancy }}</div>
                    <div>Current Occupancy</div>
                </div>
                <div class="bg-success text-success-content px-4 py-3 rounded-lg flex-1">
                    <div class="text-lg font-bold">{{ number_format($occupancyPercentage, 1) }}%</div>
                    <div>Occupancy Rate</div>
                </div>
                <div class="bg-warning text-warning-content px-4 py-3 rounded-lg flex-1">
                    <div class="text-lg font-bold">{{ $availableSpace }}</div>
                    <div>Available Space</div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-2">Facilities</h2>
                @if(empty($evacuationCenter->facilities))
                    <p class="text-base-content/60 italic">No facilities listed.</p>
                @else
                    <ul class="list-disc list-inside">
                        @foreach($evacuationCenter->facilities as $facility)
                            <li class="capitalize">{{ $facility }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="pt-4">
                <a href="{{ route('admin.evacuation-centers.edit', $evacuationCenter) }}" class="btn btn-primary mr-4">Edit Center</a>
                <a href="{{ route('admin.evacuation-centers.index') }}" class="btn btn-outline">Back to List</a>
            </div>
        </div>
    </div>
</x-app-layout>
