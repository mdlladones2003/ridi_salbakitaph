<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Disaster Report
        </h1>

        @if ($errors->any())
            <div class="alert alert-error mb-4">
                <ul class="list-disc list-inside text-sm text-white">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold mb-1">Barangay</label>
                    <select id="barangaySelect" name="barangay_id" class="select select-bordered w-full" required>
                        <option value="">Select Barangay</option>
                        @foreach ($barangays as $barangay)
                            <option value="{{ $barangay->barangay_id }}"
                                data-lat="{{ $barangay->latitude }}"
                                data-lon="{{ $barangay->longitude }}"
                                {{ old('barangay_id') == $barangay->barangay_id ? 'selected' : '' }}>
                                {{ $barangay->name }} ({{ $barangay->municipality }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Disaster Type</label>
                    <select name="type" class="select select-bordered w-full" required>
                        <option value="">Select Type</option>
                        @foreach (['flood', 'fire', 'earthquake', 'typhoon', 'landslide'] as $type)
                            <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Severity Level</label>
                    <select name="severity" class="select select-bordered w-full" required>
                        <option value="">Select Severity</option>
                        @foreach (['low', 'moderate', 'high', 'critical'] as $level)
                            <option value="{{ $level }}" {{ old('severity') == $level ? 'selected' : '' }}>
                                {{ ucfirst($level) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Incident Description</label>
                <textarea name="content" rows="4" class="textarea textarea-bordered w-full"
                          placeholder="Describe the incident..." required>{{ old('content') }}</textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold mb-1">Upload Media (optional)</label>
                    <input type="file" name="media[]" class="file-input file-input-bordered w-full" multiple accept="image/*">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Estimated Affected People</label>
                    <input type="number" name="affected_count" min="0" value="{{ old('affected_count') }}"
                           class="input input-bordered w-full" placeholder="e.g. 25">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Barangay Location</label>
                <div id="map" class="w-full h-64 rounded-lg border"></div>
            </div>

            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

            <div class="flex justify-end pt-4">
                <button type="submit" class="btn btn-primary px-6">Submit Report</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const map = L.map("map").setView([13.525, 123.300], 9);
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                let marker;
                const barangaySelect = document.getElementById("barangaySelect");
                const latInput = document.getElementById("latitude");
                const lonInput = document.getElementById("longitude");

                barangaySelect.addEventListener("change", function () {
                    const selected = this.options[this.selectedIndex];
                    const lat = parseFloat(selected.dataset.lat);
                    const lon = parseFloat(selected.dataset.lon);

                    if (!isNaN(lat) && !isNaN(lon)) {
                        latInput.value = lat;
                        lonInput.value = lon;

                        if (marker) map.removeLayer(marker);
                        marker = L.marker([lat, lon]).addTo(map);
                        map.setView([lat, lon], 14);
                    }
                });

                if (barangaySelect.value) {
                    barangaySelect.dispatchEvent(new Event("change"));
                }
            });
        </script>
    @endpush
</x-app-layout>
