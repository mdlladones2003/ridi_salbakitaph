<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold">Alert Archive</h1>
                <p class="text-sm opacity-60 mt-1">Browse past disaster alerts and updates</p>
            </div>
            <a href="{{ route('community.alerts') }}" class="btn btn-outline btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Active Alerts
            </a>
        </div>

        <!-- Stats Section -->
        <div class="stats shadow mb-6">
            <div class="stat py-3">
                <div class="stat-title text-xs">Total Archived</div>
                <div class="stat-value text-2xl text-primary">{{ $alerts->total() }}</div>
            </div>

            <div class="stat py-3">
                <div class="stat-title text-xs">Showing</div>
                <div class="stat-value text-2xl text-secondary">{{ $alerts->firstItem() }}-{{ $alerts->lastItem() }}</div>
            </div>
        </div>

        @if($alerts->count() > 0)
            <div class="space-y-4">
                @foreach($alerts as $alert)
                    @php
                        $severityConfig = [
                            'critical' => [
                                'badge' => 'badge-error',
                                'border' => 'border-error',
                                'icon' => '🚨',
                                'text' => 'Critical'
                            ],
                            'high' => [
                                'badge' => 'badge-warning',
                                'border' => 'border-warning',
                                'icon' => '⚠️',
                                'text' => 'High'
                            ],
                            'medium' => [
                                'badge' => 'badge-info',
                                'border' => 'border-info',
                                'icon' => 'ℹ️',
                                'text' => 'Medium'
                            ],
                            'low' => [
                                'badge' => 'badge-success',
                                'border' => 'border-success',
                                'icon' => '✓',
                                'text' => 'Low'
                            ]
                        ];
                        $config = $severityConfig[$alert->severity] ?? $severityConfig['medium'];
                    @endphp

                    <div class="card bg-base-100 shadow-xl border-l-4 {{ $config['border'] }}">
                        <div class="card-body">
                            <!-- Alert Header -->
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div class="flex items-start gap-3 flex-1">
                                    <div class="text-3xl">{{ $config['icon'] }}</div>
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="badge {{ $config['badge'] }} badge-sm">{{ $config['text'] }}</span>
                                            <span class="badge badge-outline badge-sm">{{ $alert->type }}</span>
                                            @if(!$alert->is_active)
                                                <span class="badge badge-ghost badge-sm">Inactive</span>
                                            @elseif($alert->expires_at && $alert->expires_at <= now())
                                                <span class="badge badge-ghost badge-sm">Expired</span>
                                            @endif
                                            @if($alert->expires_at)
                                                <span class="text-xs opacity-60">
                                                    Expired: {{ $alert->expires_at->format('M d, Y g:i A') }}
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="card-title text-xl">{{ $alert->title }}</h3>
                                        <p class="text-xs opacity-60 mt-1">
                                            Sent {{ $alert->sent_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('community.alerts.show', $alert) }}" class="btn btn-sm btn-ghost">
                                    View Details
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>

                            <!-- Alert Message -->
                            <div class="prose prose-sm max-w-none">
                                <p class="whitespace-pre-wrap line-clamp-3">{{ $alert->message }}</p>
                            </div>

                            <!-- Alert Footer -->
                            <div class="flex flex-wrap justify-between items-center gap-2 mt-4">
                                <div class="flex flex-wrap items-center gap-2 text-sm opacity-70">
                                    @if($alert->disasterUpdate)
                                        <span class="badge badge-outline badge-sm">
                                            {{ $alert->disasterUpdate->disaster_type ?? $alert->disasterUpdate->title ?? 'Disaster Update' }}
                                        </span>
                                    @endif
                                    @if($alert->affected_areas)
                                        <span class="badge badge-outline badge-sm">
                                            📍 {{ Str::limit($alert->affected_areas, 40) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $alerts->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body items-center text-center py-16">
                    <div class="text-6xl mb-4">📦</div>
                    <h2 class="card-title text-2xl mb-2">No Archived Alerts</h2>
                    <p class="text-base opacity-60 max-w-md">
                        There are currently no archived alerts in the system. Past alerts will appear here once they expire or become inactive.
                    </p>
                    <div class="card-actions mt-6">
                        <a href="{{ route('community.alerts') }}" class="btn btn-primary">View Active Alerts</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
