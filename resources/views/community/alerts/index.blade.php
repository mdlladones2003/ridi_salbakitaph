<x-app-layout>
    <div class="max-w-7xl mx-auto py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold">Active Alerts</h1>
                <p class="text-sm opacity-60 mt-1">Stay informed about current emergencies and warnings</p>
            </div>
            <a href="{{ route('community.alerts.archive') }}" class="btn btn-outline btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
                View Archive
            </a>
        </div>

        @if($alerts->count() > 0)
            <div class="space-y-4">
                @foreach($alerts as $alert)
                    @php
                        $severityConfig = [
                            'critical' => [
                                'alert' => 'alert-error',
                                'badge' => 'badge-error',
                                'icon' => '🚨',
                                'text' => 'Critical'
                            ],
                            'warning' => [
                                'alert' => 'alert-warning',
                                'badge' => 'badge-warning',
                                'icon' => '⚠️',
                                'text' => 'Warning'
                            ],
                            'info' => [
                                'alert' => 'alert-info',
                                'badge' => 'badge-info',
                                'icon' => 'ℹ️',
                                'text' => 'Info'
                            ]
                        ];
                        $config = $severityConfig[$alert->severity] ?? $severityConfig['info'];
                    @endphp

                    <div class="card bg-base-100 shadow-md border-l-4 {{ $alert->severity === 'critical' ? 'border-error' : ($alert->severity === 'warning' ? 'border-warning' : 'border-info') }}">
                        <div class="card-body">
                            <!-- Alert Header -->
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <div class="flex items-start gap-3 flex-1">
                                    <div class="text-3xl">{{ $config['icon'] }}</div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="badge {{ $config['badge'] }} badge-sm">{{ $config['text'] }}</span>
                                            @if($alert->expires_at)
                                                <span class="text-xs opacity-60">
                                                    Expires: {{ $alert->expires_at->format('M d, Y g:i A') }}
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
                            <div class="card-actions justify-between items-center mt-4">
                                <div class="flex items-center gap-2 text-sm opacity-70">
                                    @if($alert->disasterUpdate)
                                        <span class="badge badge-outline badge-sm">
                                            {{ $alert->disasterUpdate->disaster_type ?? 'General Alert' }}
                                        </span>
                                    @endif
                                    @if($alert->affected_areas)
                                        <span class="badge badge-outline badge-sm">
                                            📍 {{ Str::limit($alert->affected_areas, 40) }}
                                        </span>
                                    @endif
                                </div>

                                @if($alert->action_required)
                                    <div class="alert alert-sm {{ $config['alert'] }} py-2 px-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span class="text-xs font-semibold">Action Required</span>
                                    </div>
                                @endif
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
                    <div class="text-6xl mb-4">✅</div>
                    <h2 class="card-title text-2xl mb-2">No Active Alerts</h2>
                    <p class="text-base opacity-60 max-w-md">
                        There are currently no active alerts in your area. Check back later or view the archive for past alerts.
                    </p>
                    <div class="card-actions mt-6">
                        <a href="{{ route('alerts.archive') }}" class="btn btn-outline">View Archive</a>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
