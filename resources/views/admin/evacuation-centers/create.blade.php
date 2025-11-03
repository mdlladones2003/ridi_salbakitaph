<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 space-y-6">
        <h1 class="text-3xl font-bold text-primary">
            {{ isset($evacuationCenter) ? 'Edit Evacuation Center' : 'Create Evacuation Center' }}
        </h1>

        @if($errors->any())
            <div class="alert alert-error shadow">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($evacuationCenter) ? route('admin.evacuation-centers.update', $evacuationCenter) : route('admin.evacuation-centers.store') }}"
            method="POST" class="space-y-6 bg-base-100 p-6 rounded-lg shadow">
            @csrf
            @if(isset($evacuationCenter))
                @method('PUT')
            @endif

            {{-- Barangay --}}
            <div>
                <label for="barangay_id" class="label"><span class="label-text font-semibold">Barangay</span></label>
                <select id="barangay_id" name="barangay_id" required
                    class="select select-bordered w-full @error('barangay_id') select-error @enderror">
                    <option value="" disabled {{ old('barangay_id', $evacuationCenter->barangay_id ?? '') == '' ? 'selected' : '' }}>
                        Select Barangay
                    </option>
                    @foreach($barangays as $barangay)
                        <option value="{{ $barangay->barangay_id }}"
                            {{ old('barangay_id', $evacuationCenter->barangay_id ?? '') == $barangay->barangay_id ? 'selected' : '' }}>
                            {{ $barangay->name }}
                        </option>
                    @endforeach
                </select>
                @error('barangay_id')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Name --}}
            <div>
                <label for="name" class="label"><span class="label-text font-semibold">Center Name</span></label>
                <input type="text" id="name" name="name" required
                    value="{{ old('name', $evacuationCenter->name ?? '') }}"
                    class="input input-bordered w-full @error('name') input-error @enderror" />
                @error('name')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Address (auto-filled) --}}
            <div>
                <label for="address" class="label"><span class="label-text font-semibold">Address</span></label>
                <textarea id="address" name="address" rows="2" required
                    class="textarea textarea-bordered w-full @error('address') textarea-error @enderror"
                    placeholder="Will auto-fill when barangay is selected">{{ old('address', $evacuationCenter->address ?? '') }}</textarea>
                @error('address')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Map --}}
            <div>
                <label class="label"><span class="label-text font-semibold">Map Preview</span></label>
                <div id="map" class="rounded-lg border border-base-300" style="height: 300px;"></div>
            </div>

            {{-- Coordinates --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="latitude" class="label"><span class="label-text font-semibold">Latitude</span></label>
                    <input type="number" id="latitude" name="latitude" step="any" min="-90" max="90" required
                        value="{{ old('latitude', $evacuationCenter->latitude ?? '') }}"
                        class="input input-bordered w-full @error('latitude') input-error @enderror" />
                    @error('latitude')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="longitude" class="label"><span class="label-text font-semibold">Longitude</span></label>
                    <input type="number" id="longitude" name="longitude" step="any" min="-180" max="180" required
                        value="{{ old('longitude', $evacuationCenter->longitude ?? '') }}"
                        class="input input-bordered w-full @error('longitude') input-error @enderror" />
                    @error('longitude')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Capacity + Occupancy --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="capacity" class="label"><span class="label-text font-semibold">Capacity</span></label>
                    <input type="number" id="capacity" name="capacity" min="1" required
                        value="{{ old('capacity', $evacuationCenter->capacity ?? '') }}"
                        class="input input-bordered w-full @error('capacity') input-error @enderror" />
                    @error('capacity')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="current_occupancy" class="label"><span class="label-text font-semibold">Current Occupancy</span></label>
                    <input type="number" id="current_occupancy" name="current_occupancy" min="0" required
                        value="{{ old('current_occupancy', $evacuationCenter->current_occupancy ?? '') }}"
                        class="input input-bordered w-full @error('current_occupancy') input-error @enderror" />
                    @error('current_occupancy')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Facilities --}}
            <div>
                <label class="label font-semibold mb-2">Facilities</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @php
                        $selectedFacilities = old('facilities', $evacuationCenter->facilities ?? []);
                    @endphp
                    @foreach(['medical', 'food', 'water', 'power', 'blankets', 'clothing', 'hygiene'] as $facility)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="facilities[]" value="{{ $facility }}"
                                class="checkbox checkbox-primary"
                                @if(in_array($facility, $selectedFacilities)) checked @endif />
                            <span class="capitalize">{{ $facility }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Contact --}}
            <div>
                <label for="contact_number" class="label"><span class="label-text font-semibold">Contact Number</span></label>
                <input type="text" id="contact_number" name="contact_number"
                    value="{{ old('contact_number', $evacuationCenter->contact_number ?? '') }}"
                    class="input input-bordered w-full @error('contact_number') input-error @enderror" />
            </div>

            {{-- Active toggle --}}
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-primary"
                        @if(old('is_active', $evacuationCenter->is_active ?? false)) checked @endif />
                    <span>Active</span>
                </label>
            </div>

            {{-- Buttons --}}
            <div>
                <button type="submit" class="btn btn-primary w-full">
                    {{ isset($evacuationCenter) ? 'Update Center' : 'Create Center' }}
                </button>
            </div>
            <div class="text-center">
                <a href="{{ route('admin.evacuation-centers.index') }}" class="btn btn-ghost btn-sm w-full">Back to List</a>
            </div>
        </form>
    </div>

    {{-- Leaflet JS + Script --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const barangaySelect = document.getElementById('barangay_id');
            const addressInput = document.getElementById('address');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');

            // Initialize Leaflet map
            const map = L.map('map').setView([13.4105, 123.4173], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            let marker;

            function updateMarker(lat, lng) {
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
                map.setView([lat, lng], 14);
            }

            // Barangay select listener
            barangaySelect.addEventListener('change', async function () {
    const barangayId = this.value;
    if (!barangayId) return;

    try {
        const response = await fetch(`/admin/barangays/${barangayId}/info`);
        const data = await response.json();

        // Use complete address from API
        addressInput.value = data.address || `${data.name}, ${data.municipality}, ${data.province}, Philippines`;

        // Auto-fill coordinates
        if (data.latitude && data.longitude) {
            latitudeInput.value = data.latitude;
            longitudeInput.value = data.longitude;
            updateMarker(data.latitude, data.longitude);
        }
    } catch (error) {
        console.error('Failed to fetch barangay info:', error);
    }
});


            // Manual coordinate updates
            [latitudeInput, longitudeInput].forEach(input => {
                input.addEventListener('input', () => {
                    const lat = parseFloat(latitudeInput.value);
                    const lng = parseFloat(longitudeInput.value);
                    if (!isNaN(lat) && !isNaN(lng)) updateMarker(lat, lng);
                });
            });

            // If editing existing center, show its coordinates
            const initLat = parseFloat(latitudeInput.value);
            const initLng = parseFloat(longitudeInput.value);
            if (!isNaN(initLat) && !isNaN(initLng)) updateMarker(initLat, initLng);
        });
    </script>
</x-app-layout>
