<x-app-layout>
    <div class="px-8 py-6 max-w-7xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-base-content flex items-center gap-2">
                <x-lucide-activity class="w-7 h-7 text-primary" />
                {{ isset($disaster) ? 'Edit Disaster Update' : 'Create Disaster Update' }}
            </h1>
            <a href="{{ route('admin.disasters.index') }}" class="btn btn-outline btn-sm">
                <x-lucide-arrow-left class="w-4 h-4" /> Back
            </a>
        </div>

        <div class="bg-white border border-base-300 shadow-sm rounded-md p-8 space-y-10">
            <form
                action="{{ isset($disaster) ? route('admin.disasters.update', $disaster) : route('admin.disasters.store') }}"
                method="POST"
                class="space-y-10">
                @csrf
                @if(isset($disaster))
                    @method('PUT')
                @endif

                <section>
                    <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2 border-b border-base-200 pb-2">
                        <x-lucide-alert-triangle class="w-5 h-5 text-blue-600" />
                        Disaster Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="flex flex-col">
                            <label for="type" class="font-semibold text-sm text-base-content/80 mb-1">Disaster Type</label>
                            <select id="type" name="type"
                                class="select select-bordered w-full @error('type') select-error @enderror"
                                required>
                                <option value="" disabled {{ old('type', $disaster->type ?? '') === '' ? 'selected' : '' }}>Select Type</option>
                                @foreach(['flood', 'fire', 'earthquake', 'typhoon', 'landslide'] as $type)
                                    <option value="{{ $type }}" {{ old('type', $disaster->type ?? '') === $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col">
                            <label for="affected_area" class="font-semibold text-sm text-base-content/80 mb-1">Affected Area</label>
                            <input
                                type="text"
                                id="affected_area"
                                name="affected_area"
                                value="{{ old('affected_area', $disaster->affected_area ?? '') }}"
                                placeholder="Enter affected area (e.g., Barangay Binalay)"
                                class="input input-bordered w-full @error('affected_area') input-error @enderror"
                                required>
                            @error('affected_area')
                                <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col">
                            <label for="status" class="font-semibold text-sm text-base-content/80 mb-1">Status</label>
                            <select id="status" name="status" class="select select-bordered w-full">
                                <option value="active" @selected(old('status', $disaster->status ?? '') === 'active')>Active</option>
                                <option value="archived" @selected(old('status', $disaster->status ?? '') === 'archived')>Archived</option>
                            </select>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2 border-b border-base-200 pb-2">
                        <x-lucide-file-text class="w-5 h-5 text-blue-600" />
                        Disaster Details
                    </h2>

                    <div>
                        <label for="content" class="font-semibold text-sm text-base-content/80 mb-1">Update Description</label>
                        <textarea
                            id="content"
                            name="content"
                            rows="7"
                            placeholder="Describe the disaster situation, response actions, and ongoing developments..."
                            class="textarea textarea-bordered w-full @error('content') textarea-error @enderror"
                            required
                        >{{ old('content', $disaster->content ?? '') }}</textarea>
                        @error('content')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <div class="flex justify-end gap-3 pt-6 border-t border-base-200">
                    <a href="{{ route('admin.disasters.index') }}" class="btn btn-ghost btn-md">Cancel</a>
                    <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white btn-md px-6">
                        <x-lucide-save class="w-4 h-4 mr-2" />
                        {{ isset($disaster) ? 'Update Disaster' : 'Create Disaster' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
