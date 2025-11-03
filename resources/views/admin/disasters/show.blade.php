<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 space-y-6">
        <h1 class="text-3xl font-bold text-primary capitalize">{{ $disaster->type }} Update</h1>

        @if(session('success'))
            <div class="alert alert-success shadow">
            {{ session('success') }}
            </div>
        @endif

        <!-- Disaster Main Info -->
        <div class="bg-base-100 rounded-lg shadow p-6 space-y-4">
            <div>
                <h2 class="text-xl font-semibold text-secondary mb-1">Affected Area</h2>
                <p class="text-base-content">{{ $disaster->affected_area }}</p>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-secondary mb-1">Content</h2>
                <p class="whitespace-pre-line text-base-content">{{ $disaster->content }}</p>
            </div>

            <div class="flex gap-4 text-center mt-6">
                <div class="rounded-lg bg-info text-info-content px-4 py-3 flex-1">
                    <div class="text-lg font-bold">{{ $stats['total_alerts'] }}</div>
                    <div>Total Alerts</div>
                </div>
                <div class="rounded-lg bg-warning text-warning-content px-4 py-3 flex-1">
                    <div class="text-lg font-bold">{{ $stats['active_alerts'] }}</div>
                    <div>Active Alerts</div>
                </div>
                <div class="rounded-lg bg-error text-error-content px-4 py-3 flex-1">
                    <div class="text-lg font-bold">{{ $stats['critical_alerts'] }}</div>
                    <div>Critical Alerts</div>
                </div>
            </div>
        </div>

        <!-- Latest Alerts List -->
        <div>
        <h2 class="text-2xl font-semibold text-primary mb-4">Latest Alerts</h2>
        @if($disaster->alerts->isEmpty())
            <p class="text-base-content/60 italic">No alerts available for this disaster update.</p>
        @else
        <div class="overflow-x-auto rounded-lg border border-base-300 shadow-sm">
            <table class="table w-full table-zebra">
            <thead>
                <tr>
                <th>Message</th>
                <th>Severity</th>
                <th>Sent At</th>
                <th>Status</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($disaster->alerts as $alert)
                <tr>
                <td class="max-w-xs truncate" title="{{ $alert->message }}">{{ $alert->message }}</td>
                <td>
                    <span class="badge
                    @if($alert->severity === 'info') badge-info
                    @elseif($alert->severity === 'warning') badge-warning
                    @elseif($alert->severity === 'critical') badge-error
                    @else badge-neutral @endif
                    capitalize">
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
                <td>
                    <a href="{{ route('admin.alerts.index') }}" class="btn btn-sm btn-info">View All Alerts</a>
                </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        @endif
        </div>

        <!-- Back Button -->
        <div class="pt-4">
            <a href="{{ route('admin.disasters.index') }}" class="btn btn-outline">Back to Disaster Updates</a>
        </div>
    </div>
</x-app-layout>
