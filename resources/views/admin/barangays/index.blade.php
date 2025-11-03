<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 space-y-6">
        <h1 class="text-3xl font-bold text-primary">Barangays</h1>

        <!-- Filters and Search -->
        <form method="GET" action="{{ route('admin.barangays.index') }}" class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
            <input type="text" name="search" placeholder="Search by name, municipality, province" value="{{ request('search') }}"
                class="input input-bordered w-full sm:flex-grow" aria-label="Search barangays" />

            <select name="risk_level" class="select select-bordered w-full sm:w-40" aria-label="Filter by risk level">
                <option value="">All Risk Levels</option>
                @foreach(['low', 'medium', 'high'] as $level)
                    <option value="{{ $level }}" @selected(request('risk_level') === $level)>{{ ucfirst($level) }}</option>
                @endforeach
            </select>

            <select name="province" class="select select-bordered w-full sm:w-48" aria-label="Filter by province">
                <option value="">All Provinces</option>
                @foreach($provinces as $province)
                    <option value="{{ $province }}" @selected(request('province') === $province)>{{ $province }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary whitespace-nowrap">Filter</button>
            <a href="{{ route('admin.barangays.create') }}" class="btn btn-outline btn-primary whitespace-nowrap">New Barangay</a>
        </form>

            @if(session('success'))
                <div class="alert alert-success shadow">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error shadow">{{ session('error') }}</div>
            @endif

            <!-- Barangays Table -->
            <div class="overflow-x-auto rounded-lg border border-base-300 shadow-sm">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Municipality</th>
                            <th>Province</th>
                            <th class="text-center">Reports</th>
                            <th class="text-center">Evacuation Centers</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barangays as $barangay)
                            <tr>
                                <td class="font-semibold">{{ $barangay->name }}</td>
                                <td>{{ $barangay->municipality }}</td>
                                <td>{{ $barangay->province }}</td>
                                <td class="text-center">{{ $barangay->reports_count }}</td>
                                <td class="text-center">{{ $barangay->evacuation_centers_count }}</td>
                                <td class="space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.barangays.show', $barangay) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.barangays.edit', $barangay) }}" class="btn btn-sm btn-warning">Edit</a>

                                <!-- Delete Modal Trigger -->
                                <label for="modal-delete-{{ $barangay->id }}" class="btn btn-sm btn-error cursor-pointer">Delete</label>

                                <!-- Delete Modal -->
                                <input type="checkbox" id="modal-delete-{{ $barangay->id }}" class="modal-toggle" />
                                <div class="modal">
                                    <div class="modal-box">
                                        <h3 class="font-bold text-lg text-error">Confirm Delete</h3>
                                        <p class="py-4">Are you sure you want to delete <strong>{{ $barangay->name }}</strong>?</p>
                                        <div class="modal-action">
                                            <label for="modal-delete-{{ $barangay->id }}" class="btn btn-ghost">Cancel</label>
                                            <form action="{{ route('admin.barangays.destroy', $barangay) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                    <label for="modal-delete-{{ $barangay->id }}" class="modal-backdrop"></label>
                                </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-base-content/60">No barangays found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        <!-- Pagination -->
        <div>{{ $barangays->withQueryString()->links() }}</div>
    </div>
</x-app-layout>
