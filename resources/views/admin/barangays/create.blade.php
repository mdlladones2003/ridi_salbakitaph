<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 space-y-6">
        <h1 class="text-3xl font-bold text-primary">
            {{ isset($barangay) ? 'Edit Barangay' : 'Create Barangay' }}
        </h1>

        {{-- Session Error --}}
        @if(session('error'))
            <div class="alert alert-error shadow">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-error shadow">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            id="barangayForm"
            action="{{ isset($barangay) ? route('admin.barangays.update', $barangay) : route('admin.barangays.store') }}"
            method="POST"
            class="space-y-6 bg-base-100 p-6 rounded-lg shadow"
        >
            @csrf
            @if(isset($barangay))
                @method('PUT')
            @endif

            {{-- Barangay Name --}}
            <div>
                <label for="name" class="label"><span class="label-text font-semibold">Barangay Name</span></label>
                <input type="text" id="name" name="name" required
                    value="{{ old('name', $barangay->name ?? '') }}"
                    class="input input-bordered w-full @error('name') input-error @enderror" />
                @error('name')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Municipality --}}
            <div>
                <label for="municipality" class="label"><span class="label-text font-semibold">Municipality</span></label>
                <input type="text" id="municipality" name="municipality" required
                    value="{{ old('municipality', $barangay->municipality ?? '') }}"
                    class="input input-bordered w-full @error('municipality') input-error @enderror" />
                @error('municipality')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Province --}}
            <div>
                <label for="province" class="label"><span class="label-text font-semibold">Province</span></label>
                <input type="text" id="province" name="province" required
                    value="{{ old('province', $barangay->province ?? 'Camarines Sur') }}"
                    class="input input-bordered w-full @error('province') input-error @enderror" />
                @error('province')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Info --}}
            <div class="bg-base-200 text-sm text-gray-700 p-3 rounded-lg">
                <p>
                    Coordinates are automatically fetched from
                    <a href="https://www.geoapify.com" target="_blank" class="text-primary underline">Geoapify</a>
                    when you fill in the Barangay and Municipality fields.
                </p>
                <p class="mt-1">You can manually adjust them if needed.</p>
            </div>

            {{-- Coordinates --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="latitude" class="label"><span class="label-text font-semibold">Latitude</span></label>
                    <input type="number" id="latitude" name="latitude" step="any" min="-90" max="90"
                        value="{{ old('latitude', $barangay->latitude ?? '') }}"
                        class="input input-bordered w-full @error('latitude') input-error @enderror"
                        placeholder="Auto-filled from Geoapify" />
                    @error('latitude')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="longitude" class="label"><span class="label-text font-semibold">Longitude</span></label>
                    <input type="number" id="longitude" name="longitude" step="any" min="-180" max="180"
                        value="{{ old('longitude', $barangay->longitude ?? '') }}"
                        class="input input-bordered w-full @error('longitude') input-error @enderror"
                        placeholder="Auto-filled from Geoapify" />
                    @error('longitude')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Risk Level --}}
            <div>
                <label for="risk_level" class="label"><span class="label-text font-semibold">Risk Level</span></label>
                <select id="risk_level" name="risk_level" required
                    class="select select-bordered w-full @error('risk_level') select-error @enderror">
                    <option value="" disabled {{ old('risk_level', $barangay->risk_level ?? '') === '' ? 'selected' : '' }}>Select risk level</option>
                    @foreach(['low', 'medium', 'high'] as $level)
                        <option value="{{ $level }}" {{ old('risk_level', $barangay->risk_level ?? '') === $level ? 'selected' : '' }}>
                            {{ ucfirst($level) }}
                        </option>
                    @endforeach
                </select>
                @error('risk_level')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Submit --}}
            <div>
                <button type="submit" class="btn btn-primary w-full">
                    {{ isset($barangay) ? 'Update Barangay' : 'Create Barangay' }}
                </button>
            </div>

            {{-- Back --}}
            <div class="text-center">
                <a href="{{ route('admin.barangays.index') }}" class="btn btn-ghost btn-sm w-full">Back to List</a>
            </div>
        </form>
    </div>

    {{-- AJAX Script for auto-fetching coordinates --}}
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

                latInput.placeholder = 'Fetching latitude...';
                lngInput.placeholder = 'Fetching longitude...';

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
                        latInput.placeholder = '';
                        lngInput.placeholder = '';
                    } else {
                        latInput.placeholder = 'Coordinates not found';
                        lngInput.placeholder = 'Coordinates not found';
                    }
                } catch (error) {
                    latInput.placeholder = 'Error fetching';
                    lngInput.placeholder = 'Error fetching';
                    console.error('Error fetching coordinates:', error);
                }
            };

            [nameInput, municipalityInput, provinceInput].forEach(input => {
                input.addEventListener('blur', fetchCoordinates);
            });
        });
    </script>
</x-app-layout>
