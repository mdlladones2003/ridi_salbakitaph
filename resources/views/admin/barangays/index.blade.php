<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="bg-primary/10 text-base-content p-2 rounded-lg">
                    <x-lucide-map-pin class="w-6 h-6" />
                </div>
                <h1 class="text-3xl font-bold text-base-content">Barangay Management</h1>
            </div>
            <a href="{{ route('admin.barangays.create') }}" class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white px-4">
                <x-lucide-plus class="w-4 h-4" />
                New Barangay
            </a>
        </div>

        <form method="GET" action="{{ route('admin.barangays.index') }}"
            class="flex flex-wrap items-center gap-3 bg-base-100 border border-base-300 rounded-md shadow-sm p-3 w-auto">

            <div class="flex items-center w-full sm:w-auto flex-grow relative">
                <input
                    type="text"
                    name="search"
                    placeholder="Search by name, municipality, or province..."
                    value="{{ request('search') }}"
                    class="input input-bordered w-full pr-10"
                />
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-base-content hover:text-primary-focus">
                    <x-lucide-search class="w-5 h-5" />
                </button>
            </div>

            <div class="flex items-center gap-3">
                <select name="risk_level" class="select select-bordered w-40">
                    <option value="">All Risk Levels</option>
                    @foreach(['low', 'medium', 'high'] as $level)
                        <option value="{{ $level }}" @selected(request('risk_level') === $level)>{{ ucfirst($level) }}</option>
                    @endforeach
                </select>

                <select name="province" class="select select-bordered w-44">
                    <option value="">All Provinces</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province }}" @selected(request('province') === $province)>{{ $province }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-md bg-blue-600 hover:bg-blue-700 text-white px-4">
                    <x-lucide-filter class="w-4 h-4" />
                    Filter
                </button>

                <a href="{{ route('admin.barangays.index') }}" class="btn btn-ghost border border-base-300 hover:bg-base-200">
                    Clear
                </a>
            </div>
        </form>

        @if(session('success'))
            <div class="alert alert-success shadow-sm flex items-center gap-2">
                <x-lucide-check-circle class="w-5 h-5" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error shadow-sm flex items-center gap-2">
                <x-lucide-alert-triangle class="w-5 h-5" />
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto border border-base-300 rounded-md bg-base-100 shadow-sm">
            <table class="table w-full">
                <thead class="text-base-content text-xs uppercase tracking-wider">
                    <tr>
                        <th>Name</th>
                        <th>Municipality</th>
                        <th>Province</th>
                        <th class="text-center">Reports</th>
                        <th class="text-center">Evacuation Centers</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barangays as $barangay)
                        <tr class="hover:bg-blue-50/50 transition">
                            <td class="font-semibold">{{ $barangay->name }}</td>
                            <td>{{ $barangay->municipality }}</td>
                            <td>{{ $barangay->province }}</td>
                            <td class="text-center">{{ $barangay->reports_count }}</td>
                            <td class="text-center">{{ $barangay->evacuation_centers_count }}</td>
                            <td class="text-center whitespace-nowrap">
                                <a href="{{ route('admin.barangays.show', $barangay) }}" class="btn btn-ghost btn-xs text-blue-600 hover:bg-blue-50">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>
                                <a href="{{ route('admin.barangays.edit', $barangay) }}" class="btn btn-ghost btn-xs text-yellow-600 hover:bg-yellow-50">
                                    <x-lucide-edit class="w-4 h-4" />
                                </a>

                                <label for="modal-delete-{{ $barangay->barangay_id }}" class="btn btn-ghost btn-xs text-red-600 hover:bg-red-50 cursor-pointer">
                                    <x-lucide-trash class="w-4 h-4" />
                                </label>

                                <input type="checkbox" id="modal-delete-{{ $barangay->barangay_id }}" class="modal-toggle" />
                                <div class="modal">
                                    <div class="modal-box rounded-md border border-base-300 shadow-lg p-6 max-w-sm">
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="bg-red-100 text-red-600 p-2 rounded-full">
                                                <x-lucide-alert-triangle class="w-5 h-5" />
                                            </div>
                                            <h3 class="font-semibold text-lg text-red-600">Confirm Deletion</h3>
                                        </div>

                                        <div class="space-y-2">
                                            <p class="text-sm text-base-content">
                                                Are you sure you want to delete
                                                <strong>{{ $barangay->name }}</strong>?
                                            </p>
                                            <p class="text-xs text-red-500 font-medium">
                                                This action cannot be undone.
                                            </p>
                                        </div>

                                        <div class="modal-action mt-5 flex justify-end gap-2">
                                            <label for="modal-delete-{{ $barangay->barangay_id }}"
                                                class="btn btn-ghost btn-sm border border-base-300 hover:bg-base-200">
                                                Cancel
                                            </label>

                                            <form action="{{ route('admin.barangays.destroy', $barangay) }}" method="POST" class="inline">
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
                                    <label for="modal-delete-{{ $barangay->barangay_id }}" class="modal-backdrop"></label>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-base-content/60">
                                No barangays found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $barangays->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
