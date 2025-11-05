<x-app-layout>
    <div class="max-w-7xl mx-auto p-8 space-y-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-base-300 pb-4">
            <div class="flex items-center text-base-content gap-3 flex-wrap">
                <x-lucide-home class="w-6 h-6" />
                <h1 class="text-3xl font-bold">
                    {{ isset($evacuationCenter) ? 'Edit Evacuation Center' : 'Create Evacuation Center' }}
                </h1>
                <label class="flex items-center gap-2 cursor-pointer ml-3">
                    <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-primary"
                        form="evacuationForm"
                        @if(old('is_active', $evacuationCenter->is_active ?? false)) checked @endif />
                    <span class="text-sm">Active</span>
                </label>
            </div>

            <div class="flex items-center gap-3 mt-3 sm:mt-0">
                @if(isset($evacuationCenter) && Route::currentRouteName() !== 'admin.evacuation-centers.edit')
                    <a href="{{ route('admin.evacuation-centers.edit', $evacuationCenter) }}" class="btn btn-outline btn-sm">
                        <x-lucide-pencil class="w-4 h-4" /> Edit
                    </a>
                @endif
                <a href="{{ route('admin.evacuation-centers.index') }}" class="btn btn-outline btn-sm">
                    <x-lucide-arrow-left class="w-4 h-4 mr-1" /> Back to List
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-error shadow">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="evacuationForm"
            action="{{ isset($evacuationCenter) ? route('admin.evacuation-centers.update', $evacuationCenter) : route('admin.evacuation-centers.store') }}"
            method="POST" class="space-y-8 bg-base-100 p-10 rounded-md shadow-md border border-base-300">
            @csrf
            @if(isset($evacuationCenter))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="barangay_id" class="label font-semibold">Barangay</label>
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

                <div>
                    <label for="name" class="label font-semibold">Center Name</label>
                    <input type="text" id="name" name="name" required
                        value="{{ old('name', $evacuationCenter->name ?? '') }}"
                        class="input input-bordered w-full @error('name') input-error @enderror" />
                    @error('name')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="address" class="label font-semibold">Address</label>
                    <textarea id="address" name="address" rows="3" required
                        class="textarea textarea-bordered w-full @error('address') textarea-error @enderror"
                        placeholder="Will auto-fill when barangay is selected">{{ old('address', $evacuationCenter->address ?? '') }}</textarea>
                    @error('address')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="contact_number" class="label font-semibold">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number"
                        value="{{ old('contact_number', $evacuationCenter->contact_number ?? '') }}"
                        class="input input-bordered w-full @error('contact_number') input-error @enderror" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="capacity" class="label font-semibold">Capacity</label>
                    <input type="number" id="capacity" name="capacity" min="1" required
                        value="{{ old('capacity', $evacuationCenter->capacity ?? '') }}"
                        class="input input-bordered w-full @error('capacity') input-error @enderror" />
                </div>
                <div>
                    <label for="current_occupancy" class="label font-semibold">Current Occupancy</label>
                    <input type="number" id="current_occupancy" name="current_occupancy" min="0" required
                        value="{{ old('current_occupancy', $evacuationCenter->current_occupancy ?? '') }}"
                        class="input input-bordered w-full @error('current_occupancy') input-error @enderror" />
                </div>
            </div>

            <div>
                <label class="label font-semibold mb-2">Facilities</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @php $selectedFacilities = old('facilities', $evacuationCenter->facilities ?? []); @endphp
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

            <div>
                <label class="label font-semibold">Map Overview</label>
                <div id="map" class="rounded-lg border border-base-300 h-[450px] w-full"></div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-base-200">
                    <a href="{{ route('admin.evacuation-centers.index') }}" class="btn btn-ghost btn-md">Cancel</a>
                    <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white btn-md px-6">
                        <x-lucide-save class="w-4 h-4 mr-2" />
                        {{ isset($evacuationCenter) ? 'Update Center' : 'Create Center' }}
                    </button>
                </div>
            </form>
        </form>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const barangaySelect = document.getElementById('barangay_id');
            const addressInput = document.getElementById('address');
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

            barangaySelect.addEventListener('change', async function () {
                const barangayId = this.value;
                if (!barangayId) return;

                try {
                    const response = await fetch(`/admin/barangays/${barangayId}/info`);
                    const data = await response.json();
                    addressInput.value = data.address || `${data.name}, ${data.municipality}, ${data.province}, Philippines`;

                    if (data.latitude && data.longitude) {
                        updateMarker(data.latitude, data.longitude);
                    }
                } catch (error) {
                    console.error('Failed to fetch barangay info:', error);
                }
            });

            @if(isset($evacuationCenter) && $evacuationCenter->latitude && $evacuationCenter->longitude)
                updateMarker({{ $evacuationCenter->latitude }}, {{ $evacuationCenter->longitude }});
            @endif
        });
    </script>
</x-app-layout>
