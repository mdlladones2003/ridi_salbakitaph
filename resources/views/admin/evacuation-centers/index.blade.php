<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 space-y-6">
        <h1 class="text-3xl font-bold text-primary">Evacuation Centers</h1>

        <!-- Filters and Search -->
        <form method="GET" action="{{ route('admin.evacuation-centers.index') }}" class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
            <input type="text" name="search" placeholder="Search by name" value="{{ request('search') }}"
                class="input input-bordered w-full sm:flex-grow" aria-label="Search evacuation centers" />

            <select name="barangay_id" class="select select-bordered w-full sm:w-48" aria-label="Filter by barangay">
                <option value="">All Barangays</option>
                @foreach($barangays as $barangay)
                    <option value="{{ $barangay->barangay_id }}" @selected(request('barangay_id') == $barangay->barangay_id)>{{ $barangay->name }}</option>
                @endforeach
            </select>

            <select name="is_active" class="select select-bordered w-full sm:w-32" aria-label="Filter by status">
                <option value="">All Status</option>
                <option value="1" @selected(request('is_active') === '1')>Active</option>
                <option value="0" @selected(request('is_active') === '0')>Inactive</option>
            </select>

            <button type="submit" class="btn btn-primary whitespace-nowrap">Filter</button>
            <a href="{{ route('admin.evacuation-centers.create') }}" class="btn btn-outline btn-primary whitespace-nowrap">New Center</a>
        </form>

        @if(session('success'))
            <div class="alert alert-success shadow">{{ session('success') }}</div>
        @endif

        <!-- Centers Table -->
        <div class="overflow-x-auto rounded-lg border border-base-300 shadow-sm">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Barangay</th>
                        <th>Address</th>
                        <th>Capacity</th>
                        <th>Occupancy</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($centers as $center)
                        <tr>
                            <td class="font-semibold">{{ $center->name }}</td>
                            <td>{{ $center->barangay->name ?? 'N/A' }}</td>
                            <td class="max-w-lg truncate">{{ $center->address }}</td>
                            <td>{{ $center->capacity }}</td>
                            <td>{{ $center->current_occupancy }}</td>
                            <td>
                                @if ($center->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-neutral">Inactive</span>
                                @endif
                            </td>
                            <td class="space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.evacuation-centers.show', $center) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.evacuation-centers.edit', $center) }}" class="btn btn-sm btn-warning">Edit</a>

                                <!-- Delete Modal Trigger -->
                                <label for="modal-delete-{{ $center->evacuation_center_id }}" class="btn btn-sm btn-error cursor-pointer">Delete</label>

                            <!-- Modal -->
                                <input type="checkbox" id="modal-delete-{{ $center->evacuation_center_id }}" class="modal-toggle" />
                                <div class="modal">
                                    <div class="modal-box">
                                        <h3 class="font-bold text-lg text-error">Confirm Delete</h3>
                                        <p class="py-4">Are you sure you want to delete <strong>{{ $center->name }}</strong>?</p>
                                        <div class="modal-action">
                                            <label for="modal-delete-{{ $center->evacuation_center_id }}" class="btn btn-ghost">Cancel</label>
                                            <form action="{{ route('admin.evacuation-centers.destroy', $center) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                    <label for="modal-delete-{{ $center->evacuation_center_id }}" class="modal-backdrop"></label>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-base-content/60">No evacuation centers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $centers->withQueryString()->links() }}</div>
    </div>
</x-app-layout>
