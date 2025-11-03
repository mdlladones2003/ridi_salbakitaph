<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 space-y-6 mt-20">
        <h1 class="text-3xl font-bold text-primary">
            {{ isset($disaster) ? 'Edit Disaster Update' : 'Create Disaster Update' }}
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

        <form action="{{ isset($disaster) ? route('admin.disasters.update', $disaster) : route('admin.disasters.store') }}"
            method="POST" class="space-y-6 bg-base-100 p-6 rounded-lg shadow">
            @csrf
            @if(isset($disaster))
                @method('PUT')
            @endif

            <!-- Disaster Type -->
            <div>
                <label for="type" class="label">
                    <span class="label-text font-semibold">Disaster Type</span>
                </label>
                <select id="type" name="type" class="select select-bordered w-full @error('type') select-error @enderror" required>
                    <option value="" disabled {{ old('type', $disaster->type ?? '') === '' ? 'selected' : '' }}>Select type</option>
                    @foreach(['flood', 'fire', 'earthquake', 'typhoon', 'landslide'] as $type)
                        <option value="{{ $type }}"
                            {{ old('type', $disaster->type ?? '') === $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="label">
                    <span class="label-text font-semibold">Content</span>
                </label>
                <textarea id="content" name="content" rows="5" required
                    class="textarea textarea-bordered w-full @error('content') textarea-error @enderror"
                    placeholder="Enter disaster update details...">
                    {{ old('content', $disaster->content ?? '') }}
                </textarea>
                @error('content')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Affected Area -->
            <div>
                <label for="affected_area" class="label">
                    <span class="label-text font-semibold">Affected Area</span>
                </label>
                <input type="text" id="affected_area" name="affected_area" required
                    value="{{ old('affected_area', $disaster->affected_area ?? '') }}"
                    class="input input-bordered w-full @error('affected_area') input-error @enderror"
                    placeholder="Specify the affected area">
                @error('affected_area')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="btn btn-primary w-full">
                {{ isset($disaster) ? 'Update Disaster Update' : 'Create Disaster Update' }}
                </button>
            </div>

            <!-- Back Link -->
            <div class="text-center pt-2">
                <a href="{{ route('admin.disasters.index') }}" class="btn btn-ghost btn-sm w-full">Back to List</a>
            </div>
        </form>
    </div>
</x-app-layout>
