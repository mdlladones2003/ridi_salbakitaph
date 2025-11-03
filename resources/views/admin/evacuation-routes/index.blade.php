<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 space-y-6 mt-20">
        <h1 class="text-3xl font-bold text-primary">Evacuation Routes</h1>

        @if(session('success'))
            <div class="alert alert-success shadow">{{ session('success') }}</div>
        @endif

        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.evacuation-routes.create') }}" class="btn btn-outline btn-primary whitespace-nowrap">New Route</a>
        </div>

        <div class="overflow-x-auto rounded-lg border border-base-300 shadow-sm">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Route Name</th>
                        <th>Barangay</th>
                        <th>Start Point</th>
                        <th>End Point</th>
                        <th>Coordinates</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($routes as $route)
                        <tr>
                            <td class="font-semibold">{{ $route->route_name }}</td>
                            <td>{{ $route->barangay->name ?? 'N/A' }}</td>
                            <td>{{ $route->start_point }}</td>
                            <td>{{ $route->end_point }}</td>
                            <td>{{ $route->latitude }}, {{ $route->longitude }}</td>
                            <td>
                                @if($route->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-neutral">Inactive</span>
                                @endif
                            </td>
                            <td class="space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.evacuation-routes.show', $route) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.evacuation-routes.edit', $route) }}" class="btn btn-sm btn-warning">Edit</a>

                                <!-- Delete Modal Trigger -->
                                <label for="modal-delete-{{ $route->evacuation_route_id }}" class="btn btn-sm btn-error cursor-pointer">Delete</label>

                                <!-- Delete Modal -->
                                <input type="checkbox" id="modal-delete-{{ $route->evacuation_route_id }}" class="modal-toggle" />
                                <div class="modal">
                                    <div class="modal-box">
                                        <h3 class="font-bold text-lg text-error">Confirm Delete</h3>
                                        <p class="py-4">Are you sure you want to delete the route <strong>{{ $route->route_name }}</strong>?</p>
                                        <div class="modal-action">
                                            <label for="modal-delete-{{ $route->evacuation_route_id }}" class="btn btn-ghost">Cancel</label>
                                            <form action="{{ route('admin.evacuation-routes.destroy', $route) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                    <label for="modal-delete-{{ $route->evacuation_route_id }}" class="modal-backdrop"></label>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-base-content/60">No evacuation routes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $routes->links() }}</div>
    </div>
</x-app-layout>
