<x-app-layout>
    <div class="max-w-5xl mx-auto p-6 space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-base-content capitalize flex items-center gap-2">
                    <x-lucide-alert-octagon class="w-6 h-6" />
                    {{ $disaster->type }} Update
                </h1>
            </div>

            <a href="{{ route('admin.disasters.index') }}" class="btn btn-outline btn-sm">
                <x-lucide-arrow-left class="w-4 h-4 mr-1" /> Back to List
            </a>
        </div>

        <div class="bg-base-100 border border-base-300 rounded-md shadow-sm p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="flex items-center gap-2 text-lg font-semibold text-base-content mb-1">
                        <x-lucide-map-pin class="w-5 h-5" />
                        Affected Area
                    </h2>
                    <p class="text-base-content">{{ $disaster->affected_area }}</p>
                </div>

                <div>
                    <h2 class="flex items-center gap-2 text-lg font-semibold text-base-content mb-1">
                        <x-lucide-alert-octagon class="w-5 h-5" />
                        Disaster Type
                    </h2>
                    <span class="badge badge-lg capitalize
                        @switch($disaster->type)
                            @case('flood') badge-info @break
                            @case('fire') badge-error @break
                            @case('earthquake') badge-warning @break
                            @case('typhoon') badge-primary @break
                            @case('landslide') badge-secondary @break
                            @default badge-neutral
                        @endswitch">
                        {{ ucfirst($disaster->type) }}
                    </span>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-base-content mb-1">Content</h2>
                <p class="whitespace-pre-line leading-relaxed text-base-content">{{ $disaster->content }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
                <div class="relative bg-info text-info-content p-5 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="absolute top-3 right-3 bg-info-content/20 p-2 rounded-full">
                        <x-lucide-bell class="w-5 h-5" />
                    </div>
                    <div class="text-3xl font-bold leading-tight">{{ $stats['total_alerts'] }}</div>
                    <div class="text-sm uppercase tracking-wide font-medium opacity-90 mt-1">Total Alerts</div>
                </div>

                <div class="relative bg-warning text-warning-content p-5 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="absolute top-3 right-3 bg-warning-content/20 p-2 rounded-full">
                        <x-lucide-alert-circle class="w-5 h-5" />
                    </div>
                    <div class="text-3xl font-bold leading-tight">{{ $stats['active_alerts'] }}</div>
                    <div class="text-sm uppercase tracking-wide font-medium opacity-90 mt-1">Active Alerts</div>
                </div>

                <div class="relative bg-error text-error-content p-5 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="absolute top-3 right-3 bg-error-content/20 p-2 rounded-full">
                        <x-lucide-alert-triangle class="w-5 h-5" />
                    </div>
                    <div class="text-3xl font-bold leading-tight">{{ $stats['critical_alerts'] }}</div>
                    <div class="text-sm uppercase tracking-wide font-medium opacity-90 mt-1">Critical Alerts</div>
                </div>
            </div>

        </div>

        <div class="space-y-4">
            <h2 class="text-2xl font-semibold text-base-content flex items-center gap-2">
                <x-lucide-bell class="w-6 h-6" /> Latest Alerts
            </h2>

            @if($disaster->alerts->isEmpty())
                <p class="text-base-content/60 italic">
                    No alerts available for this disaster update.
                </p>
            @else
                <div class="overflow-x-auto border border-base-300 rounded-md bg-base-100 shadow-sm">
                    <table class="table w-full">
                        <thead class="text-base-content text-xs uppercase tracking-wider">
                            <tr>
                                <th>Message</th>
                                <th>Severity</th>
                                <th>Sent At</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($disaster->alerts as $alert)
                                <tr class="hover:bg-blue-50/50 transition">
                                    <td class="max-w-md truncate" title="{{ $alert->message }}">
                                        {{ $alert->message }}
                                    </td>
                                    <td>
                                        <span class="badge capitalize
                                            @if($alert->severity === 'info') badge-info
                                            @elseif($alert->severity === 'warning') badge-warning
                                            @elseif($alert->severity === 'critical') badge-error
                                            @else badge-neutral @endif">
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
                                    <td class="text-center">
                                        <a href="{{ route('admin.alerts.index') }}" class="btn btn-ghost btn-xs text-blue-600 hover:bg-blue-50">
                                            View All
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <style>
        .stat-card {
            @apply rounded-lg shadow-sm p-4 text-center border border-base-300;
        }
    </style>
</x-app-layout>
