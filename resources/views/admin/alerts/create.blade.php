<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 space-y-6">
        <h1 class="text-3xl font-bold text-primary mb-6">Create New Alert</h1>

        @if(session('success'))
            <div class="alert alert-success">
            {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.alerts.store') }}" method="POST" class="space-y-6 bg-base-100 p-6 rounded-lg shadow">
        @csrf

        <!-- Disaster Selection -->
        <div>
            <label for="disaster_id" class="label">
                <span class="label-text font-semibold">Select Disaster</span>
            </label>
            <select id="disaster_id" name="disaster_id" class="select select-bordered w-full @error('disaster_id') select-error @enderror" required>
                <option value="" disabled selected>Choose a disaster update</option>
                @foreach($disasters as $disaster)
                    <option value="{{ $disaster->disaster_id }}" @selected(old('disaster_id') == $disaster->disaster_id)>{{ ucfirst($disaster->type ?? 'Untitled') }} - {{ $disaster->updated_at->format('M d, Y') }}</option>
                @endforeach
                </select>
            @error('disaster_id')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Alert Message -->
        <div>
            <label for="message" class="label">
            <span class="label-text font-semibold">Alert Message</span>
            </label>
            <textarea id="message" name="message" rows="4" required class="textarea textarea-bordered w-full @error('message') textarea-error @enderror" placeholder="Enter the alert message...">{{ old('message') }}</textarea>
            @error('message')
            <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Severity -->
        <div>
            <label class="label font-semibold mb-2">Severity</label>
            <div class="flex gap-4">
            @foreach(['info', 'warning', 'critical'] as $level)
                <label class="label cursor-pointer flex items-center gap-2">
                <input type="radio" name="severity" value="{{ $level }}" class="radio" @checked(old('severity') === $level) required/>
                <span class="capitalize">{{ $level }}</span>
                </label>
            @endforeach
            </div>
            @error('severity')
            <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Expiration Date -->
        <div>
            <label for="expires_at" class="label">
            <span class="label-text font-semibold">Expires At (optional)</span>
            </label>
            <input type="datetime-local" name="expires_at" id="expires_at" class="input input-bordered w-full @error('expires_at') input-error @enderror" value="{{ old('expires_at') }}">
            <p class="text-xs text-base-content/60 mt-1">Leave empty if alert does not expire</p>
            @error('expires_at')
            <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" class="btn btn-primary w-full">Broadcast Alert</button>
        </div>
        </form>
    </div>
</x-app-layout>
