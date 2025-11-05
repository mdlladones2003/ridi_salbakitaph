<x-app-layout>
    <div class="px-8 py-6 max-w-7xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-base-content flex items-center gap-2">
                <x-lucide-home class="w-6 h-6" />
                Evacuation Centers
            </h1>

            <a href="{{ route('admin.evacuation-centers.create') }}" class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white px-4">
                <x-lucide-plus class="w-4 h-4" /> New Center
            </a>
        </div>

        <div class="bg-white border border-base-300 shadow-sm rounded-md p-5">
            <form method="GET" action="{{ route('admin.evacuation-centers.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                <input
                    type="text"
                    name="search"
                    placeholder="Search by name..."
                    value="{{ request('search') }}"
                    class="input input-bordered w-full"
                />

                <select name="barangay_id" class="select select-bordered w-full">
                    <option value="">All Barangays</option>
                    @foreach($barangays as $barangay)
                        <option value="{{ $barangay->barangay_id }}" @selected(request('barangay_id') == $barangay->barangay_id)>
                            {{ $barangay->name }}
                        </option>
                    @endforeach
                </select>

                <select name="is_active" class="select select-bordered w-full">
                    <option value="">All Status</option>
                    <option value="1" @selected(request('is_active') === '1')>Active</option>
                    <option value="0" @selected(request('is_active') === '0')>Inactive</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary w-full md:w-auto flex-1">
                        <x-lucide-filter class="w-4 h-4 mr-1" /> Filter
                    </button>
                    <a href="{{ route('admin.evacuation-centers.index') }}" class="btn btn-ghost w-full md:w-auto flex-1">
                        <x-lucide-rotate-ccw class="w-4 h-4 mr-1" /> Reset
                    </a>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow flex items-center gap-2">
                <x-lucide-check-circle class="w-5 h-5" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto border border-base-300 rounded-md bg-base-100 shadow-sm">
            <table class="table w-full">
                <thead class="text-base-content text-xs uppercase tracking-wider">
                    <tr>
                        <th>Name</th>
                        <th>Barangay</th>
                        <th>Address</th>
                        <th>Capacity</th>
                        <th>Occupancy</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($centers as $center)
                        <tr>
                            <td class="font-semibold">{{ $center->name }}</td>
                            <td>{{ $center->barangay->name }}</td>
                            <td class="max-w-lg">{{ $center->address }}</td>
                            <td class="text-center">{{ $center->capacity }}</td>
                            <td class="text-center">{{ $center->current_occupancy }}</td>
                            <td>
                                @if ($center->is_active)
                                    <span class="badge bg-green-100 text-green-700 border-none font-medium">Active</span>
                                @else
                                    <span class="badge bg-gray-200 text-gray-700 border-none font-medium">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center whitespace-nowrap">
                                <a href="{{ route('admin.evacuation-centers.show', $center) }}" class="btn btn-ghost btn-xs text-blue-600 hover:bg-blue-50">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>
                                <a href="{{ route('admin.evacuation-centers.edit', $center) }}" class="btn btn-ghost btn-xs text-yellow-600 hover:bg-yellow-50">
                                    <x-lucide-edit class="w-4 h-4" />
                                </a>

                                <label for="modal-delete-{{ $center->evacuation_center_id }}" class="btn btn-ghost btn-xs text-red-600 hover:bg-red-50 cursor-pointer">
                                    <x-lucide-trash class="w-4 h-4" />
                                </label>

                                <input type="checkbox" id="modal-delete-{{ $center->evacuation_center_id }}" class="modal-toggle" />
                                <div class="modal">
                                    <div class="modal-box rounded-md border border-base-300 shadow-lg p-6 max-w-sm">
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="bg-red-100 text-red-600 p-2 rounded-full">
                                                <x-lucide-alert-triangle class="w-5 h-5" />
                                            </div>
                                            <h3 class="font-semibold text-lg text-red-600">Confirm Deletion</h3>
                                        </div>

                                        <div class="space-y-2 text-wrap">
                                            <p class="text-sm text-base-content">Are you sure you want to delete <strong>{{ $center->name }}</strong>?</p>
                                            <p class="text-xs text-red-500 font-medium">
                                                This action cannot be undone.
                                            </p>
                                        </div>

                                        <div class="modal-action mt-5 flex justify-end gap-2">
                                            <label for="modal-delete-{{ $center->evacuation_center_id }}"
                                                class="btn btn-ghost btn-sm border border-base-300 hover:bg-base-200">
                                                Cancel
                                            </label>

                                            <form action="{{ route('admin.evacuation-centers.destroy', $center) }}" method="POST" class="inline">
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
                                    <label for="modal-delete-{{ $center->evacuation_center_id }}" class="modal-backdrop"></label>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-base-content/60">
                                No evacuation centers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4">{{ $centers->withQueryString()->links() }}</div>
    </div>
</x-app-layout>
