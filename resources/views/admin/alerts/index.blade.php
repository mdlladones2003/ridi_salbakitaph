<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 space-y-6 mt-20">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-primary">Active Alerts</h1>
            <a href="{{ route('admin.alerts.create') }}" class="btn btn-primary whitespace-nowrap">Create New Alert</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow">
            {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto rounded-lg border border-base-300 shadow-sm">
            <table class="table w-full table-zebra">
                <thead>
                    <tr>
                        <th>Alert ID</th>
                        <th>Disaster</th>
                        <th>Message</th>
                        <th>Severity</th>
                        <th>Sent At</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($alerts as $alert)
                <tr>
                    <td class="font-mono">{{ $alert->alert_id }}</td>
                    <td class="capitalize">{{ $alert->disasterUpdate->type ?? 'N/A' }}</td>
                    <td class="max-w-xs">{{ $alert->message }}</td>
                    <td>
                        <span class="badge
                            @if($alert->severity === 'info') badge-info
                            @elseif($alert->severity === 'warning') badge-warning
                            @elseif($alert->severity === 'critical') badge-error
                            @else badge-neutral @endif capitalize">
                            {{ $alert->severity }}
                        </span>
                    </td>
                    <td>{{ $alert->sent_at->format('M d, Y - h:i A') }}</td>
                    <td>
                        @if ($alert->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-neutral">Inactive</span>
                        @endif
                    </td>
                    <td class="text-center space-x-2 whitespace-nowrap">
                        <!-- Toggle Active Form -->
                        <form action="{{ route('admin.alerts.toggle', $alert) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-xs btn-ghost">
                                @if ($alert->is_active)
                                    <!-- Eye-Off Icon for Deactivate -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-7 0-10-7-10-7a17.555 17.555 0 014.058-5.196m3.819-1.64A10.06 10.06 0 0112 5c7 0 10 7 10 7a17.615 17.615 0 01-1.45 2.675m-2.673 2.674L6.343 6.343"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                    </svg>
                                @else
                                    <!-- Eye Icon for Activate -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                @endif
                            </button>
                        </form>

                        <!-- Delete Alert Form -->
                        <form action="{{ route('admin.alerts.destroy', $alert) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this alert?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-error">
                            <!-- Trash Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" />
                            </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-base-content/60">No alerts found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div>
        {{ $alerts->links() }}
        </div>
    </div>
</x-app-layout>
