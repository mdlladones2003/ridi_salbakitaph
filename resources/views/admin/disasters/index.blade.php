<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="bg-primary/10 text-base-content p-2 rounded-lg">
                    <x-lucide-radiation class="w-6 h-6" />
                </div>
                <h1 class="text-3xl font-bold text-base-content">Disaster Updates</h1>
            </div>
            <a href="{{ route('admin.disasters.create') }}" class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white px-4">
                <x-lucide-plus class="w-4 h-4" />
                New Update
            </a>
        </div>

        <div class="flex justify-end">
            <form method="GET" action="{{ route('admin.disasters.index') }}"
                class="flex flex-wrap items-center gap-3 bg-base-100 border border-base-300 rounded-md shadow-sm p-3 w-auto">

                <select name="type" class="select select-bordered select-md w-44">
                    <option value="">All Type</option>
                    <option value="flood" @selected(request('type')=='flood')>Flood</option>
                    <option value="fire" @selected(request('type')=='fire')>Fire</option>
                    <option value="earthquake" @selected(request('type')=='earthquake')>Earthquake</option>
                    <option value="typhoon" @selected(request('type')=='typhoon')>Typhoon</option>
                    <option value="landslie" @selected(request('type')=='landslide')>Landslide</option>
                </select>

                <button type="submit" class="btn btn-md bg-blue-600 hover:bg-blue-700 text-white px-4">
                    <x-lucide-filter class="w-4 h-4" />
                    Filter
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow">
                <x-lucide-check-circle class="w-5 h-5" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error shadow">
                <x-lucide-alert-octagon class="w-5 h-5" />
                <span>{{ session('error') }}</span>
            </div>
        @endif


        <div class="overflow-x-auto border border-base-300 rounded-md bg-base-100 shadow-sm">
            <table class="table w-full">
                <thead class="text-base-content text-xs uppercase tracking-wider">
                    <tr>
                        <th>Type</th>
                        <th>Affected Area</th>
                        <th>Content</th>
                        <th class="text-center">Alerts</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($disasters as $disaster)
                        <tr class="hover:bg-blue-50/50 transition">
                            <td class="capitalize font-semibold">{{ ucfirst($disaster->type) }}</td>
                            <td>{{ $disaster->affected_area }}</td>
                            <td class="max-w-md truncate">{{ $disaster->content }}</td>
                            <td class="text-center text-sm">{{ $disaster->alerts_count }}</td>
                            <td class="text-center whitespace-nowrap">
                                <a href="{{ route('admin.disasters.show', $disaster) }}" class="btn btn-ghost btn-xs text-blue-600 hover:bg-blue-50">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>
                                <a href="{{ route('admin.disasters.edit', $disaster) }}" class="btn btn-ghost btn-xs text-yellow-600 hover:bg-yellow-50">
                                    <x-lucide-edit class="w-4 h-4" />
                                </a>

                                <label for="modal-delete-{{ $disaster->disaster_id }}" class="btn btn-ghost btn-xs text-red-600 hover:bg-red-50 cursor-pointer">
                                    <x-lucide-trash class="w-4 h-4" />
                                </label>

                                <input type="checkbox" id="modal-delete-{{ $disaster->disaster_id }}" class="modal-toggle" />
                                <div class="modal">
                                    <div class="modal-box rounded-md border border-base-300 shadow-lg p-6 max-w-sm">
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="bg-red-100 text-red-600 p-2 rounded-full">
                                                <x-lucide-alert-triangle class="w-5 h-5" />
                                            </div>
                                            <h3 class="font-semibold text-lg text-red-600">Confirm Deletion</h3>
                                        </div>

                                        <div class="space-y-2">
                                            <p class="text-sm text-base-content text-wrap">
                                                You are about to delete the disaster update for <strong>{{ ucfirst($disaster->affected_area) }}</strong>.
                                            </p>
                                            <p class="text-xs text-red-500 font-medium">
                                                This action cannot be undone.
                                            </p>
                                        </div>

                                        <div class="modal-action mt-5 flex justify-end gap-2">
                                            <label for="modal-delete-{{ $disaster->disaster_id }}"
                                                class="btn btn-ghost btn-sm border border-base-300 hover:bg-base-200">
                                                Cancel
                                            </label>

                                            <form action="{{ route('admin.disasters.destroy', $disaster) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm bg-red-600 hover:bg-red-700 text-white flex items-center px-2">
                                                    <x-lucide-trash-2 class="w-4 h-4" />
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-base-content/60">
                                No disaster updates found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $disasters->links() }}
        </div>
    </div>
</x-app-layout>
