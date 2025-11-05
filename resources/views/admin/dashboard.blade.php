<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-base-content">Admin Dashboard</h2>
                <p class="text-sm text-base-content/60 mt-1">
                    <span id="current-time"></span> • Welcome back!
                </p>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto space-y-8">
            <style>
                ::-webkit-scrollbar {
                    width: 0;
                    height: 0; }
                html, body {
                    overflow: hidden; }
            </style>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                @php
                    $cards = [
                        ['title' => 'Total Users', 'desc' => 'Registered accounts', 'route' => route('admin.users.index'), 'value' => $stats['total_users'], 'bg' => 'bg-blue-500', 'icon' => 'users'],
                        ['title' => 'Total Reports', 'desc' => 'All time reports', 'route' => route('admin.reports.index'), 'value' => $stats['total_reports'], 'bg' => 'bg-purple-500', 'icon' => 'file-text'],
                        ['title' => 'Pending', 'desc' => 'Awaiting review', 'route' => route('admin.reports.index', ['status' => 'pending']), 'value' => $stats['pending_reports'], 'bg' => 'bg-amber-500', 'icon' => 'clock'],
                        ['title' => 'Active Alerts', 'desc' => 'Ongoing emergencies', 'route' => route('admin.alerts.index'), 'value' => $stats['active_alerts'], 'bg' => 'bg-red-500', 'icon' => 'alert-triangle'],
                        ['title' => "Today's Check-ins", 'desc' => 'Active today', 'route' => '#', 'value' => $stats['today_check_ins'], 'bg' => 'bg-green-500', 'icon' => 'check-circle'],
                    ];
                @endphp

                @foreach ($cards as $card)
                    <div
                        onclick="window.location='{{ $card['route'] }}'"
                        class="relative rounded-xl shadow-md {{ $card['bg'] }} text-white p-4 sm:p-5 hover:shadow-lg hover:scale-[1.03] transition-all duration-300 cursor-pointer flex flex-col justify-between h-28 sm:h-32">
                        <x-dynamic-component :component="'lucide-' . $card['icon']"
                            class="absolute top-3 right-3 h-6 w-6 sm:h-7 sm:w-7 opacity-70" />
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide opacity-90">
                                {{ $card['title'] }}
                            </div>
                            <div class="text-2xl sm:text-3xl font-bold counter mt-1" data-target="{{ $card['value'] }}">0</div>
                        </div>
                        <div class="text-xs opacity-80 mt-1 sm:mt-2">
                            {{ $card['desc'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-4 space-y-4">
                    <div class="bg-white border border-base-300 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-lg font-semibold flex items-center gap-2">
                                    <x-lucide-file-text class="w-5 h-5 text-blue-600" />
                                    Recent Reports
                                </h2>

                                <div class="flex gap-2">
                                    <div class="dropdown dropdown-end">
                                        <label tabindex="0" class="btn btn-ghost btn-md text-base-content gap-1">
                                            <x-lucide-filter class="h-4 w-4" /> Filter
                                        </label>
                                        <ul tabindex="0"
                                            class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-44 text-sm">
                                            <li><a onclick="filterReportsTable('all')">All</a></li>
                                            <li><a onclick="filterReportsTable('pending')">Pending</a></li>
                                            <li><a onclick="filterReportsTable('verified')">Verified</a></li>
                                            <li><a onclick="filterReportsTable('resolved')">Resolved</a></li>
                                        </ul>
                                    </div>

                                    <a href="{{ route('admin.reports.index') }}"
                                        class="btn btn-md bg-blue-600 hover:bg-blue-700 text-white flex items-center gap-1">
                                        View All
                                        <x-lucide-chevron-right class="w-4 h-4 text-white" />
                                    </a>
                                </div>
                            </div>

                            <div class="overflow-x-auto border rounded-md">
                                <table class="table text-sm">
                                    <thead class="bg-base-200 text-base-content/80 text-xs uppercase">
                                        <tr>
                                            <th>Type</th>
                                            <th>Barangay</th>
                                            <th>Reporter</th>
                                            <th>Status</th>
                                            <th>Date Reported</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentReports as $report)
                                            <tr class="hover:bg-base-200/40 report-row transition" data-status="{{ $report->status }}">
                                                <td>
                                                    <span class="badge badge-outline badge-sm">{{ ucfirst($report->type) }}</span>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="flex items-start gap-2">
                                                        <x-lucide-map-pin class="w-4 h-4 text-base-content/50 mt-[2px]" />
                                                        <div class="flex flex-col leading-tight">
                                                            <span class="font-medium text-xs">{{ $report->barangay->name }}</span>
                                                            <span class="text-[11px] text-base-content/60">
                                                                {{ $report->barangay->municipality ?? '' }}, {{ $report->barangay->province ?? '' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="items-center gap-2">
                                                        <div class="leading-tight">
                                                            <div class="font-medium text-xs">{{ $report->user->full_name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-sm
                                                        @switch($report->status)
                                                            @case('pending') badge-warning @break
                                                            @case('verified') badge-info @break
                                                            @case('resolved') badge-success @break
                                                            @case('false_alarm') badge-error @break
                                                            @default badge-neutral
                                                        @endswitch">
                                                        {{ ucwords(str_replace('_', ' ', $report->status)) }}
                                                    </span>
                                                </td>
                                                <td class="text-xs whitespace-nowrap">
                                                    {{ $report->reported_at->format('M d, Y') }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-ghost btn-xs text-blue-600 hover:bg-blue-50">
                                                        <x-lucide-eye class="w-4 h-4" />
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-6 text-sm text-base-content/60">
                                                    No reports found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white border border-base-300 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="p-5">
                            <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <x-lucide-bar-chart-3 class="w-5 h-5 text-blue-600" />
                                Reports by Type
                            </h2>

                            <div class="space-y-2">
                                @forelse($reportByType as $type)
                                    <div class="p-3 rounded-lg hover:bg-blue-50 transition cursor-pointer"
                                        onclick="filterByType('{{ $type->type }}')">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium">{{ ucfirst($type->type) }}</span>
                                            <span class="badge bg-blue-600 text-white badge-sm">{{ $type->count }}</span>
                                        </div>
                                        <progress class="progress w-full h-2 mt-1 bg-blue-100 [&::-webkit-progress-value]:bg-blue-600 [&::-moz-progress-bar]:bg-blue-600"
                                            value="{{ $type->count }}"
                                            max="{{ $stats['total_reports'] }}"></progress>
                                        <p class="text-[11px] text-base-content/60 mt-1">
                                            {{ $stats['total_reports'] > 0 ? number_format(($type->count / $stats['total_reports']) * 100, 1) : 0 }}%
                                            of total
                                        </p>
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-sm text-base-content/60">No report data available</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ JS --}}
    <script>
        function animateCounter(el) {
            const target = +el.dataset.target;
            const duration = 1500, step = target / (duration / 16);
            let val = 0;
            const timer = setInterval(() => {
                val += step;
                if (val >= target) {
                    el.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else el.textContent = Math.floor(val).toLocaleString();
            }, 16);
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.counter').forEach(animateCounter);
            updateTime();
            setInterval(updateTime, 60000);
        });

        function updateTime() {
            const now = new Date();
            const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            document.getElementById('current-time').textContent = now.toLocaleDateString('en-US', opts);
        }

        function filterReportsTable(status) {
            document.querySelectorAll('.report-row').forEach(row => {
                row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
            });
        }

        function filterByType(type) {
            window.location.href = '{{ route('admin.reports.index') }}?type=' + type;
        }
    </script>
</x-app-layout>
