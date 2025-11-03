<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 space-y-6 mt-20">
        <h1 class="text-3xl font-bold text-primary">
            {{ isset($barangay) ? 'Edit Barangay' : 'Create Barangay' }}
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

        <form action="{{ isset($barangay) ? route('admin.barangays.update', $barangay) : route('admin.barangays.store') }}"
            method="POST" class="space-y-6 bg-base-100 p-6 rounded-lg shadow">
            @csrf
            @if(isset($barangay))
                @method('PUT')
            @endif

            <div>
                <label for="name" class="label"><span class="label-text font-semibold">Barangay Name</span></label>
                <input type="text" id="name" name="name" required
                    value="{{ old('name', $barangay->name ?? '') }}"
                    class="input input-bordered w-full @error('name') input-error @enderror" />
                @error('name')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="municipality" class="label"><span class="label-text font-semibold">Municipality</span></label>
                <input type="text" id="municipality" name="municipality" required
                    value="{{ old('municipality', $barangay->municipality ?? '') }}"
                    class="input input-bordered w-full @error('municipality') input-error @enderror" />
                @error('municipality')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="province" class="label"><span class="label-text font-semibold">Province</span></label>
                <input type="text" id="province" name="province" required
                    value="{{ old('province', $barangay->province ?? '') }}"
                    class="input input-bordered w-full @error('province') input-error @enderror" />
                @error('province')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="latitude" class="label"><span class="label-text font-semibold">Latitude</span></label>
                    <input type="number" id="latitude" name="latitude" step="any" min="-90" max="90" required
                        value="{{ old('latitude', $barangay->latitude ?? '') }}"
                        class="input input-bordered w-full @error('latitude') input-error @enderror" />
                    @error('latitude')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="longitude" class="label"><span class="label-text font-semibold">Longitude</span></label>
                    <input type="number" id="longitude" name="longitude" step="any" min="-180" max="180" required
                        value="{{ old('longitude', $barangay->longitude ?? '') }}"
                        class="input input-bordered w-full @error('longitude') input-error @enderror" />
                    @error('longitude')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="risk_level" class="label"><span class="label-text font-semibold">Risk Level</span></label>
                <select id="risk_level" name="risk_level" required class="select select-bordered w-full @error('risk_level') select-error @enderror">
                    <option value="" disabled {{ old('risk_level', $barangay->risk_level ?? '') === '' ? 'selected' : '' }}>Select risk level</option>
                    @foreach(['low', 'medium', 'high'] as $level)
                        <option value="{{ $level }}" {{ old('risk_level', $barangay->risk_level ?? '') === $level ? 'selected' : '' }}>{{ ucfirst($level) }}</option>
                    @endforeach
                </select>
                @error('risk_level')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <button type="submit" class="btn btn-primary w-full">
                    {{ isset($barangay) ? 'Update Barangay' : 'Create Barangay' }}
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('admin.barangays.index') }}" class="btn btn-ghost btn-sm w-full">Back to List</a>
            </div>
        </form>
    </div>
</x-app-layout>
