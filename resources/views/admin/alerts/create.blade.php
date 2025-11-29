<x-app-layout>
    <div class="px-8 py-6 max-w-7xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-base-content flex items-center gap-2">
                <x-lucide-megaphone class="w-6 h-6" />
                Create New Alert
            </h1>
            <a href="{{ route('admin.alerts.index') }}" class="btn btn-outline btn-sm">
                <x-lucide-arrow-left class="w-4 h-4" /> Back
            </a>
        </div>

        <div class="bg-white border border-base-300 shadow-sm rounded-md p-8 space-y-10">
            <form action="{{ route('admin.alerts.store') }}" method="POST" class="space-y-10">
                @csrf

                <section>
                    <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2 border-b border-base-200 pb-2">
                        <x-lucide-alert-triangle class="w-5 h-5 text-blue-600" />
                        Alert Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="flex flex-col">
                            <label for="disaster_id" class="font-semibold text-sm text-base-content/80 mb-1">Select Disaster</label>
                            <select id="disaster_id" name="disaster_id"
                                class="select select-bordered w-full @error('disaster_id') select-error @enderror"
                                required>
                                <option value="" disabled selected>Choose a disaster update</option>
                                @foreach($disasters as $disaster)
                                    <option value="{{ $disaster->disaster_id }}" @selected(old('disaster_id') == $disaster->disaster_id)>
                                        {{ ucfirst($disaster->type ?? 'Untitled') }} — {{ $disaster->updated_at->format('M d, Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('disaster_id')
                                <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col">
                            <label class="font-semibold text-sm text-base-content/80 mb-1">Severity Level</label>
                            <div class="flex gap-4">
                                @foreach(['info', 'warning', 'critical'] as $level)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="severity" value="{{ $level }}"
                                            class="radio radio-primary" @checked(old('severity') === $level) required />
                                        <span class="capitalize">{{ $level }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('severity')
                                <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col">
                            <label for="expires_at" class="font-semibold text-sm text-base-content/80 mb-1">Expires At (optional)</label>
                            <input type="datetime-local" name="expires_at" id="expires_at"
                                value="{{ old('expires_at') }}"
                                class="input input-bordered w-full @error('expires_at') input-error @enderror">
                            <p class="text-xs text-base-content/60 mt-1">Leave empty if alert does not expire</p>
                            @error('expires_at')
                                <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2 border-b border-base-200 pb-2">
                        <x-lucide-file-text class="w-5 h-5 text-blue-600" />
                        Alert Message
                    </h2>

                    <div>
                        <label for="message" class="font-semibold text-sm text-base-content/80 mb-1">Message Content</label>
                        <textarea id="message" name="message" rows="7"
                            placeholder="Enter the alert message..."
                            class="textarea textarea-bordered w-full @error('message') textarea-error @enderror"
                            required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <div class="flex justify-end gap-3 pt-6 border-t border-base-200">
                    <a href="{{ route('admin.alerts.index') }}" class="btn btn-ghost btn-md">Cancel</a>
                    <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white btn-md px-6">
                        <x-lucide-bell class="w-4 h-4 mr-2" />
                        Broadcast Alert
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
