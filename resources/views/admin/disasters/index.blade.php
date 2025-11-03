<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 space-y-6">
        <h1 class="text-3xl font-bold text-primary">Disaster Updates</h1>

        <!-- Filters and Search -->
        <form method="GET" action="{{ route('admin.disasters.index') }}" class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
            <select name="type" class="select select-bordered w-full sm:w-48" aria-label="Filter by disaster type">
                <option value="">All Types</option>
                @foreach(['flood', 'fire', 'earthquake', 'typhoon', 'landslide'] as $type)
                    <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>

            <input
                type="text"
                name="search"
                placeholder="Search content or affected area"
                value="{{ request('search') }}"
                class="input input-bordered w-full sm:flex-grow"
                aria-label="Search disaster updates"
            />

            <button type="submit" class="btn btn-primary whitespace-nowrap">Filter</button>
            <a href="{{ route('admin.disasters.create') }}" class="btn btn-outline btn-primary whitespace-nowrap">New Disaster Update</a>
        </form>

        @if(session('success'))
            <div class="alert alert-success shadow">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error shadow">
                {{ session('error') }}
            </div>
        @endif

        <!-- Disaster Updates Table -->
        <div class="overflow-x-auto rounded-lg border border-base-300 shadow-sm">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Affected Area</th>
                        <th>Content</th>
                        <th>Alerts Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($disasters as $disaster)
                        <tr>
                            <td class="capitalize font-semibold">{{ ucfirst($disaster->type) }}</td>
                            <td>{{ $disaster->affected_area }}</td>
                            <td class="max-w-lg truncate">{{ $disaster->content }}</td>
                            <td class="text-center">
                                <span class="badge badge-primary">{{ $disaster->alerts_count }}</span>
                            </td>
                            <td class="space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.disasters.show', $disaster) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.disasters.edit', $disaster) }}" class="btn btn-sm btn-warning">Edit</a>

                                <!-- Delete Modal Trigger -->
                                <label for="modal-delete-{{ $disaster->disaster_id }}" class="btn btn-sm btn-error cursor-pointer">Delete</label>

                                <!-- Delete Confirmation Modal -->
                                <input type="checkbox" id="modal-delete-{{ $disaster->disaster_id }}" class="modal-toggle" />
                                <div class="modal">
                                    <div class="modal-box">
                                        <h3 class="font-bold text-lg text-error">Confirm Delete</h3>
                                        <p class="py-4">Are you sure you want to delete this disaster update?<br><strong>{{ ucfirst($disaster->type) }} - {{ $disaster->affected_area }}</strong></p>
                                        <div class="modal-action">
                                            <label for="modal-delete-{{ $disaster->disaster_id }}" class="btn btn-ghost">Cancel</label>
                                            <form action="{{ route('admin.disasters.destroy', $disaster) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                    <label for="modal-delete-{{ $disaster->disaster_id }}" class="modal-backdrop"></label>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-base-content/60">No disaster updates found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div>
            {{ $disasters->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
