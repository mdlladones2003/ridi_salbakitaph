<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 space-y-6 mt-20">
        <h1 class="text-3xl font-bold text-primary">{{ $evacuationRoute->route_name }}</h1>

        @if(session('success'))
            <div class="alert alert-success shadow">{{ session('success') }}</div>
        @endif

        <div class="bg-base-100 rounded-lg shadow p-6 space-y-4">
            <div>
                <h2 class="text-xl font-semibold text-secondary mb-2">Barangay</h2>
                <p class="text-base-content">{{ $evacuationRoute->barangay->name ?? 'N/A' }}</p>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-secondary mb-2">Route Details</h2>
                <p><strong>Start Point:</strong> {{ $evacuationRoute->start_point }}</p>
                <p><strong>End Point:</strong> {{ $evacuationRoute->end_point }}</p>
                <p><strong>Coordinates:</strong> {{ $evacuationRoute->latitude }}, {{ $evacuationRoute->longitude }}</p>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-secondary mb-2">Status</h2>
                @if($evacuationRoute->is_active)
                    <span class="badge badge-success">Active</span>
                @else
                    <span class="badge badge-neutral">Inactive</span>
                @endif
            </div>

            <div class="pt-4">
                <a href="{{ route('admin.evacuation-routes.edit', $evacuationRoute) }}" class="btn btn-primary mr-4">Edit Route</a>
                <a href="{{ route('admin.evacuation-routes.index') }}" class="btn btn-outline">Back to List</a>
            </div>
        </div>
    </div>
</x-app-layout>
