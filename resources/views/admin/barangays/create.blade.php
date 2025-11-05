<x-app-layout>
    <div class="px-8 py-6 max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-base-content flex items-center gap-2">
                <x-lucide-map-pin class="w-6 h-6" />
                {{ isset($barangay) ? 'Edit Barangay' : 'Create Barangay' }}
            </h1>
            <a href="{{ route('admin.barangays.index') }}" class="btn btn-outline btn-sm">
                <x-lucide-arrow-left class="w-4 h-4" /> Back
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-base-300 shadow-sm rounded-md p-8 space-y-10">
            <form
                action="{{ isset($barangay) ? route('admin.barangays.update', $barangay) : route('admin.barangays.store') }}"
                method="POST"
                class="space-y-10"
            >
                @csrf
                @if(isset($barangay))
                    @method('PUT')
                @endif

                <!-- Section 1: Barangay Information -->
                <section>
                    <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2 border-b border-base-200 pb-2">
                        <x-lucide-building class="w-5 h-5 text-blue-600" />
                        Barangay Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="flex flex-col">
                            <label for="name" class="font-semibold text-sm text-base-content/80 mb-1">Barangay Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $barangay->name ?? '') }}"
                                placeholder="Enter barangay name"
                                class="input input-bordered w-full @error('name') input-error @enderror"
                                required
                            />
                            @error('name') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex flex-col">
                            <label for="municipality" class="font-semibold text-sm text-base-content/80 mb-1">Municipality</label>
                            <input
                                type="text"
                                id="municipality"
                                name="municipality"
                                value="{{ old('municipality', $barangay->municipality ?? '') }}"
                                placeholder="Enter municipality"
                                class="input input-bordered w-full @error('municipality') input-error @enderror"
                                required
                            />
                            @error('municipality') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex flex-col">
                            <label for="province" class="font-semibold text-sm text-base-content/80 mb-1">Province</label>
                            <input
                                type="text"
                                id="province"
                                name="province"
                                value="{{ old('province', $barangay->province ?? 'Camarines Sur') }}"
                                placeholder="Enter province"
                                class="input input-bordered w-full @error('province') input-error @enderror"
                                required
                            />
                            @error('province') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <!-- Section 2: Coordinates -->
                <section>
                    <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2 border-b border-base-200 pb-2">
                        <x-lucide-compass class="w-5 h-5 text-blue-600" />
                        Coordinates
                    </h2>

                    <div class="bg-base-200 text-sm text-gray-700 p-3 rounded-lg mb-4">
                        <x-lucide-info class="inline w-4 h-4 mr-2 text-primary" />
                        Coordinates are fetched automatically from
                        <a href="https://www.geoapify.com" target="_blank" class="text-blue-600 underline">Geoapify</a>
                        when you fill in the Barangay and Municipality fields.
                        <br>
                        You can manually adjust them if needed.
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col">
                            <label for="latitude" class="font-semibold text-sm text-base-content/80 mb-1">Latitude</label>
                            <input
                                type="number"
                                id="latitude"
                                name="latitude"
                                step="any"
                                min="-90"
                                max="90"
                                value="{{ old('latitude', $barangay->latitude ?? '') }}"
                                placeholder="Auto-filled from Geoapify"
                                class="input input-bordered w-full @error('latitude') input-error @enderror"
                            />
                            @error('latitude') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex flex-col">
                            <label for="longitude" class="font-semibold text-sm text-base-content/80 mb-1">Longitude</label>
                            <input
                                type="number"
                                id="longitude"
                                name="longitude"
                                step="any"
                                min="-180"
                                max="180"
                                value="{{ old('longitude', $barangay->longitude ?? '') }}"
                                placeholder="Auto-filled from Geoapify"
                                class="input input-bordered w-full @error('longitude') input-error @enderror"
                            />
                            @error('longitude') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <!-- Section 3: Risk Level -->
                <section>
                    <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2 border-b border-base-200 pb-2">
                        <x-lucide-thermometer class="w-5 h-5 text-blue-600" />
                        Risk Level
                    </h2>

                    <div class="flex flex-col md:w-1/2">
                        <label for="risk_level" class="font-semibold text-sm text-base-content/80 mb-1">Select Risk Level</label>
                        <select id="risk_level" name="risk_level" required
                            class="select select-bordered w-full @error('risk_level') select-error @enderror">
                            <option value="" disabled {{ old('risk_level', $barangay->risk_level ?? '') === '' ? 'selected' : '' }}>
                                Choose risk level
                            </option>
                            @foreach(['low', 'medium', 'high'] as $level)
                                <option value="{{ $level }}" {{ old('risk_level', $barangay->risk_level ?? '') === $level ? 'selected' : '' }}>
                                    {{ ucfirst($level) }}
                                </option>
                            @endforeach
                        </select>
                        @error('risk_level') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </section>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-6 border-t border-base-200">
                    <a href="{{ route('admin.barangays.index') }}" class="btn btn-ghost btn-md">Cancel</a>
                    <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white btn-md px-6">
                        <x-lucide-save class="w-4 h-4 mr-2" />
                        {{ isset($barangay) ? 'Update Barangay' : 'Create Barangay' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Auto-Fetch Coordinates Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const nameInput = document.getElementById('name');
            const municipalityInput = document.getElementById('municipality');
            const provinceInput = document.getElementById('province');
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            const fetchCoordinates = async () => {
                const barangay = nameInput.value.trim();
                const municipality = municipalityInput.value.trim();
                const province = provinceInput.value.trim();

                if (!barangay || !municipality) return;

                latInput.placeholder = 'Fetching...';
                lngInput.placeholder = 'Fetching...';

                try {
                    const response = await fetch("{{ route('admin.barangays.fetch-coordinates') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ barangay, municipality, province })
                    });

                    const data = await response.json();
                    if (data.latitude && data.longitude) {
                        latInput.value = data.latitude;
                        lngInput.value = data.longitude;
                    } else {
                        latInput.placeholder = 'Not found';
                        lngInput.placeholder = 'Not found';
                    }
                } catch {
                    latInput.placeholder = 'Error';
                    lngInput.placeholder = 'Error';
                }
            };

            [nameInput, municipalityInput, provinceInput].forEach(input => {
                input.addEventListener('blur', fetchCoordinates);
            });
        });
    </script>
</x-app-layout>
