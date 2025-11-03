<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8 mt-20">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('community.alerts') }}" class="btn btn-ghost btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Alerts
            </a>
        </div>

        @php
            $severityConfig = [
                'critical' => [
                    'alert' => 'alert-error',
                    'badge' => 'badge-error',
                    'icon' => '🚨',
                    'text' => 'Critical',
                    'bg' => 'bg-error/10'
                ],
                'warning' => [
                    'alert' => 'alert-warning',
                    'badge' => 'badge-warning',
                    'icon' => '⚠️',
                    'text' => 'Warning',
                    'bg' => 'bg-warning/10'
                ],
                'info' => [
                    'alert' => 'alert-info',
                    'badge' => 'badge-info',
                    'icon' => 'ℹ️',
                    'text' => 'Info',
                    'bg' => 'bg-info/10'
                ]
            ];
            $config = $severityConfig[$alert->severity] ?? $severityConfig['info'];
        @endphp

        <!-- Main Alert Card -->
        <div class="card bg-base-100 shadow-2xl border-t-4 {{ $alert->severity === 'critical' ? 'border-error' : ($alert->severity === 'warning' ? 'border-warning' : 'border-info') }}">
            <div class="card-body">
                <!-- Alert Header -->
                <div class="flex items-start gap-4 mb-6">
                    <div class="text-5xl">{{ $config['icon'] }}</div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <span class="badge {{ $config['badge'] }}">{{ $config['text'] }}</span>
                            @if($alert->is_active)
                                <span class="badge badge-success badge-sm">Active</span>
                            @else
                                <span class="badge badge-ghost badge-sm">Inactive</span>
                            @endif
                        </div>
                        <h1 class="text-3xl font-bold break-words">{{ $alert->title }}</h1>
                        <p class="text-sm opacity-60 mt-2">
                            Sent on {{ $alert->sent_at->format('F d, Y \a\t g:i A') }}
                        </p>
                    </div>
                </div>

                <!-- Alert Status -->
                @if($alert->action_required)
                    <div class="alert {{ $config['alert'] }} mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <h3 class="font-bold">Immediate Action Required</h3>
                            <p class="text-sm">Please follow the instructions below carefully.</p>
                        </div>
                    </div>
                @endif

                <!-- Alert Message -->
                <div class="mb-6">
                    <div class="bg-base-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-3">Alert Message</h3>
                        <div class="prose max-w-none">
                            <p class="whitespace-pre-wrap text-base leading-relaxed break-words">{{ $alert->message }}</p>
                        </div>
                    </div>
                </div>

                <!-- Alert Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    @if($alert->affected_areas)
                        <div class="stat bg-base-200 rounded-lg">
                            <div class="stat-figure text-2xl">📍</div>
                            <div class="stat-title">Affected Areas</div>
                            <div class="stat-value text-base break-words">{{ $alert->affected_areas }}</div>
                        </div>
                    @endif

                    @if($alert->expires_at)
                        <div class="stat bg-base-200 rounded-lg">
                            <div class="stat-figure text-2xl">⏰</div>
                            <div class="stat-title">Expires</div>
                            <div class="stat-value text-lg">{{ $alert->expires_at->format('M d, Y') }}</div>
                            <div class="stat-desc">{{ $alert->expires_at->format('g:i A') }}</div>
                        </div>
                    @endif

                    @if($alert->disasterUpdate)
                        <div class="stat bg-base-200 rounded-lg">
                            <div class="stat-figure text-2xl">🌪️</div>
                            <div class="stat-title">Disaster Type</div>
                            <div class="stat-value text-lg break-words">{{ $alert->disasterUpdate->type ?? 'N/A' }}</div>
                        </div>
                    @endif

                    <div class="stat bg-base-200 rounded-lg">
                        <div class="stat-figure text-2xl">📊</div>
                        <div class="stat-title">Status</div>
                        <div class="stat-value text-lg">
                            @if($alert->is_active && (!$alert->expires_at || $alert->expires_at > now()))
                                <span class="text-success">Active</span>
                            @else
                                <span class="text-error">Expired</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact Card -->
        {{-- <div class="card bg-base-100 shadow-xl mt-6">
            <div class="card-body">
                <h3 class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    Emergency Contacts
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="text-center p-4 bg-base-200 rounded-lg">
                        <p class="font-semibold">Emergency Hotline</p>
                        <p class="text-2xl font-bold text-error">911</p>
                    </div>
                    <div class="text-center p-4 bg-base-200 rounded-lg">
                        <p class="font-semibold">Disaster Response</p>
                        <p class="text-2xl font-bold text-warning">143</p>
                    </div>
                    <div class="text-center p-4 bg-base-200 rounded-lg">
                        <p class="font-semibold">Local Authorities</p>
                        <p class="text-2xl font-bold text-info">117</p>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</x-app-layout>
