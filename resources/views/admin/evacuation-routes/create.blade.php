<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 space-y-6 mt-20">
        <h1 class="text-3xl font-bold text-primary">
            {{ isset($evacuationRoute) ? 'Edit Evacuation Route' : 'Create Evacuation Route' }}
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

        <form action="{{ isset($evacuationRoute) ? route('admin.evacuation-routes.update', $evacuationRoute) : route('admin.evacuation-routes.store') }}"
            method="POST" class="space-y-6 bg-base-100 p-6 rounded-lg shadow">
            @csrf
            @if(isset($evacuationRoute))
                @method('PUT')
            @endif

            <div>
                <label for="barangay_id" class="label"><span class="label-text font-semibold">Barangay</span></label>
                <select id="barangay_id" name="barangay_id" required class="select select-bordered w-full @error('barangay_id') select-error @enderror">
                    <option value="" disabled {{ old('barangay_id', $evacuationRoute->barangay_id ?? '') == '' ? 'selected' : '' }}>Select Barangay</option>
                    @foreach($barangays as $barangay)
                        <option value="{{ $barangay->barangay_id }}" {{ old('barangay_id', $evacuationRoute->barangay_id ?? '') == $barangay->barangay_id ? 'selected' : '' }}>{{ $barangay->name }}</option>
                    @endforeach
                </select>
                @error('barangay_id')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="route_name" class="label"><span class="label-text font-semibold">Route Name</span></label>
                <input type="text" id="route_name" name="route_name" required
                value="{{ old('route_name', $evacuationRoute->route_name ?? '') }}"
                class="input input-bordered w-full @error('route_name') input-error @enderror" />
                @error('route_name')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="start_point" class="label"><span class="label-text font-semibold">Start Point</span></label>
                <input type="text" id="start_point" name="start_point" required
                    value="{{ old('start_point', $evacuationRoute->start_point ?? '') }}"
                    class="input input-bordered w-full @error('start_point') input-error @enderror" />
                @error('start_point')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="end_point" class="label"><span class="label-text font-semibold">End Point</span></label>
                <input type="text" id="end_point" name="end_point" required
                    value="{{ old('end_point', $evacuationRoute->end_point ?? '') }}"
                    class="input input-bordered w-full @error('end_point') input-error @enderror" />
                @error('end_point')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="latitude" class="label"><span class="label-text font-semibold">Latitude</span></label>
                    <input type="number" id="latitude" name="latitude" step="any" min="-90" max="90" required
                        value="{{ old('latitude', $evacuationRoute->latitude ?? '') }}"
                        class="input input-bordered w-full @error('latitude') input-error @enderror" />
                    @error('latitude')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="longitude" class="label"><span class="label-text font-semibold">Longitude</span></label>
                    <input type="number" id="longitude" name="longitude" step="any" min="-180" max="180" required
                        value="{{ old('longitude', $evacuationRoute->longitude ?? '') }}"
                        class="input input-bordered w-full @error('longitude') input-error @enderror" />
                    @error('longitude')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-primary"
                    @if(old('is_active', $evacuationRoute->is_active ?? false)) checked @endif />
                    <span>Active</span>
                </label>
            </div>

            <div>
                <button type="submit" class="btn btn-primary w-full">
                    {{ isset($evacuationRoute) ? 'Update Evacuation Route' : 'Create Evacuation Route' }}
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('admin.evacuation-routes.index') }}" class="btn btn-ghost btn-sm w-full">Back to List</a>
            </div>
        </form>
    </div>
</x-app-layout>
