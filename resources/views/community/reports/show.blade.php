<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-8 space-y-6">
        <a href="{{ route('community.reports') }}" class="btn btn-ghost btn-sm gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Reports
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 space-y-4">
                <div class="card bg-base-100 shadow-md border border-base-300">
                    <div class="card-body">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @php
                                        $typeColors = [
                                            'flood' => 'badge-info',
                                            'fire' => 'badge-error',
                                            'earthquake' => 'badge-warning',
                                            'typhoon' => 'badge-primary',
                                            'landslide' => 'badge-warning',
                                        ];
                                        $typeBadge = $typeColors[$report->type] ?? 'badge-primary';

                                        $severityColors = [
                                            'critical' => 'badge-error',
                                            'high' => 'badge-warning',
                                            'moderate' => 'badge-info',
                                            'low' => 'badge-success',
                                        ];
                                        $severityBadge = $severityColors[$report->severity] ?? 'badge-ghost';
                                    @endphp

                                    <span class="badge {{ $typeBadge }} font-semibold">{{ Str::of($report->type)->replace('_', ' ')->title() }}</span>
                                    <span class="badge {{ $severityBadge }} font-semibold">{{ ucfirst($report->severity) }}</span>

                                    <span class="badge
                                        @if($report->status === 'verified') badge-success
                                        @elseif($report->status === 'pending') badge-warning
                                        @elseif($report->status === 'investigating') badge-info
                                        @elseif($report->status === 'resolved') badge-primary
                                        @elseif($report->status === 'false_alarm') badge-error
                                        @else badge-ghost @endif
                                        font-semibold">
                                        {{ Str::of($report->status)->replace('_', ' ')->title() }}
                                    </span>
                                </div>

                                <h1 class="text-3xl font-bold">{{ Str::of($report->type)->replace('_', ' ')->title() }} Report</h1>

                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mt-2">
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $report->user->first_name }} {{ $report->user->last_name }}
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $report->barangay->name }}, {{ $report->barangay->municipality }}
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $report->reported_at->format('M d, Y g:i A') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <h3 class="text-lg font-semibold mb-2">Content</h3>
                        <p class="text-base-content/80 leading-relaxed whitespace-pre-wrap">{{ $report->content }}</p>

                        @if($report->affected_count > 0)
                            <div class="alert alert-warning mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4a2 2 0 00-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span><strong>{{ $report->affected_count }}</strong> people affected</span>
                            </div>
                        @endif

                        @if($report->media && count($report->media) > 0)
                            <div class="mt-6">
                                <h3 class="text-lg font-semibold mb-3">Attached Media</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($report->media as $mediaItem)
                                        <a href="{{ Storage::url($mediaItem) }}" target="_blank" class="group relative overflow-hidden rounded-lg border border-base-300 hover:border-primary transition-all">
                                            @if(Str::endsWith($mediaItem, ['.mp4', '.mov', '.avi']))
                                                <video src="{{ Storage::url($mediaItem) }}" class="w-full h-40 object-cover"></video>
                                            @else
                                                <img src="{{ Storage::url($mediaItem) }}" alt="Report media" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300" />
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($report->resolved_at)
                            <div class="alert alert-success mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Resolved on <strong>{{ $report->resolved_at->format('M d, Y g:i A') }}</strong></span>
                            </div>
                        @endif
                    </div>
                </div>

                @if($report->latitude && $report->longitude)
                    <div class="card bg-base-100 shadow-md border border-base-300">
                        <div class="card-body">
                            <h3 class="text-lg font-semibold mb-3">Location Map</h3>
                            <div id="report-map" class="w-full h-72 rounded-lg"></div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-4">
                <div class="card bg-base-100 shadow-md border border-base-300">
                    <div class="card-body space-y-3">
                        <h3 class="card-title text-lg">Report Info</h3>
                        <div class="text-sm space-y-2">
                            <div><span class="font-semibold">Status:</span> {{ Str::of($report->status)->replace('_', ' ')->title() }}</div>
                            <div><span class="font-semibold">Type:</span> {{ ucfirst($report->type) }}</div>
                            <div><span class="font-semibold">Severity:</span> {{ ucfirst($report->severity) }}</div>
                            <div><span class="font-semibold">Location:</span> {{ $report->barangay->name }}, {{ $report->barangay->municipality }}</div>
                            <div><span class="font-semibold">Reported:</span> {{ $report->reported_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

                @if($report->verification_count > 0 && $report->verifications)
                    <div class="card bg-base-100 shadow-md border border-base-300 mt-6">
                        <div class="card-body">
                            <h3 class="text-lg font-semibold mb-3 flex items-center justify-between">
                                Community Verifications
                                <span class="badge badge-primary">{{ $report->verification_count }}</span>
                            </h3>

                            <div class="space-y-3">
                                @foreach($report->verifications as $verification)
                                    <div class="flex items-start gap-3 p-3 bg-base-200 rounded-lg">
                                        <div class="avatar placeholder">
                                            <div class="bg-primary text-primary-content rounded-full w-10 flex items-center justify-center">
                                                <span class="text-sm font-semibold">
                                                    @if($verification->verifier)
                                                        {{ strtoupper(substr($verification->verifier->first_name, 0, 1)) }}{{ strtoupper(substr($verification->verifier->last_name, 0, 1)) }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex-1">
                                            <div class="font-semibold">
                                                @if($verification->verifier)
                                                    {{ $verification->verifier->first_name }} {{ $verification->verifier->last_name }}
                                                @endif
                                            </div>
                                            <div class="text-xs opacity-60">
                                                {{ $verification->created_at ? $verification->created_at->diffForHumans() : 'Unknown time' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card bg-base-100 shadow-md border border-base-300">
                    <div class="card-body">
                        <h3 class="card-title text-lg mb-2">Reported By</h3>
                        <div class="flex items-center gap-3">
                            <div class="avatar placeholder">
                                <div class="bg-primary text-primary-content rounded-full w-10 flex items-center justify-center">
                                    <span class="text-sm font-semibold">
                                        {{ strtoupper(substr($report->user->first_name, 0, 1)) }}{{ strtoupper(substr($report->user->last_name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="font-semibold">{{ $report->user->first_name }} {{ $report->user->last_name }}</div>
                                <div class="text-xs opacity-60">{{ $report->user->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($report->latitude && $report->longitude)
        @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const map = L.map('report-map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                L.marker([{{ $report->latitude }}, {{ $report->longitude }}]).addTo(map)
                    .bindPopup('<strong>{{ ucfirst($report->type) }}</strong><br>{{ $report->barangay->name ?? 'Unknown' }}');
            });
        </script>
        @endpush
    @endif
</x-app-layout>
