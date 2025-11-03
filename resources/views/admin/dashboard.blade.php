<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-base-content">
                    Admin Dashboard
                </h2>
                <p class="text-sm text-base-content/60 mt-1">
                    <span id="current-time"></span> • Welcome back!
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

                <!-- Total Users -->
                <div class="stats shadow hover:shadow-xl transition-all hover:scale-105 cursor-pointer bg-primary text-primary-content" onclick="window.location='{{ route('admin.users.index') }}'">
                    <div class="stat p-5">
                        <div class="stat-figure text-primary-content opacity-20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="stat-title text-primary-content opacity-70 text-xs font-medium uppercase tracking-wide">Total Users</div>
                        <div class="stat-value text-primary-content counter" data-target="{{ $stats['total_users'] }}">0</div>
                        <div class="stat-desc text-primary-content opacity-60">Registered accounts</div>
                    </div>
                </div>

                <!-- Total Reports -->
                <div class="stats shadow hover:shadow-xl transition-all hover:scale-105 cursor-pointer bg-secondary text-secondary-content" onclick="window.location='{{ route('admin.reports.index') }}'">
                    <div class="stat p-5">
                        <div class="stat-figure text-secondary-content opacity-20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="stat-title text-secondary-content opacity-70 text-xs font-medium uppercase tracking-wide">Total Reports</div>
                        <div class="stat-value text-secondary-content counter" data-target="{{ $stats['total_reports'] }}">0</div>
                        <div class="stat-desc text-secondary-content opacity-60">All time reports</div>
                    </div>
                </div>

                <!-- Pending Reports -->
                <div class="stats shadow hover:shadow-xl transition-all hover:scale-105 cursor-pointer bg-warning text-warning-content" onclick="filterReports('pending')">
                    <div class="stat p-5">
                        <div class="stat-figure text-warning-content opacity-20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="stat-title text-warning-content opacity-70 text-xs font-medium uppercase tracking-wide">Pending</div>
                        <div class="stat-value text-warning-content counter" data-target="{{ $stats['pending_reports'] }}">0</div>
                        <div class="stat-desc text-warning-content opacity-60">Awaiting review</div>
                    </div>
                </div>

                <!-- Active Alerts -->
                <div class="stats shadow hover:shadow-xl transition-all hover:scale-105 cursor-pointer bg-error text-error-content" onclick="window.location='{{ route('admin.alerts.index') }}'">
                    <div class="stat p-5">
                        <div class="stat-figure text-error-content opacity-20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="stat-title text-error-content opacity-70 text-xs font-medium uppercase tracking-wide">Active Alerts</div>
                        <div class="stat-value text-error-content counter" data-target="{{ $stats['active_alerts'] }}">0</div>
                        <div class="stat-desc text-error-content opacity-60">Ongoing emergencies</div>
                    </div>
                </div>

                <!-- Today Check-ins -->
                <div class="stats shadow hover:shadow-xl transition-all hover:scale-105 cursor-pointer bg-success text-success-content">
                    <div class="stat p-5">
                        <div class="stat-figure text-success-content opacity-20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="stat-title text-success-content opacity-70 text-xs font-medium uppercase tracking-wide">Today's Check-ins</div>
                        <div class="stat-value text-success-content counter" data-target="{{ $stats['today_check_ins'] }}">0</div>
                        <div class="stat-desc text-success-content opacity-60">Active today</div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Recent Reports - Takes 2 columns -->
                <div class="lg:col-span-2">
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="card-title text-2xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Recent Reports
                                </h2>
                                <div class="flex gap-2">
                                    <!-- Filter Dropdown -->
                                    <div class="dropdown dropdown-end">
                                        <label tabindex="0" class="btn btn-ghost btn-sm gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                            </svg>
                                            Filter
                                        </label>
                                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                            <li><a onclick="filterReportsTable('all')">All Reports</a></li>
                                            <li><a onclick="filterReportsTable('pending')">Pending Only</a></li>
                                            <li><a onclick="filterReportsTable('verified')">Verified Only</a></li>
                                            <li><a onclick="filterReportsTable('resolved')">Resolved Only</a></li>
                                        </ul>
                                    </div>
                                    <a href="{{ route('admin.reports.index') }}" class="btn btn-primary btn-sm gap-2">
                                        View All
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="table table-zebra" id="reportsTable">
                                    <thead>
                                        <tr>
                                            <th class="bg-base-200">ID</th>
                                            <th class="bg-base-200">Type</th>
                                            <th class="bg-base-200">Location</th>
                                            <th class="bg-base-200">Reporter</th>
                                            <th class="bg-base-200">Status</th>
                                            <th class="bg-base-200">Date</th>
                                            <th class="bg-base-200">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentReports as $report)
                                            <tr class="hover report-row" data-status="{{ $report->status }}">
                                                <td class="font-mono text-xs font-semibold">{{ $report->report_id }}</td>
                                                <td>
                                                    <span class="badge badge-outline badge-sm">{{ ucfirst($report->type) }}</span>
                                                </td>
                                                <td>
                                                    <div class="flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                        <span class="text-sm">{{ $report->barangay->name ?? 'N/A' }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex items-center gap-3">
                                                        <div class="avatar placeholder">
                                                            <div class="bg-primary text-primary-content rounded-full w-10 h-10 flex items-center justify-center">
                                                                <span class="text-sm font-semibold">{{ substr($report->user->first_name, 0, 1) . substr($report->user->last_name, 0, 1) }}</span>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="font-semibold text-sm">{{ $report->user->full_name }}</div>
                                                            <div class="text-xs text-base-content/60">{{ $report->user->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($report->status === 'pending')
                                                        <span class="badge badge-warning gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Pending
                                                        </span>
                                                    @elseif($report->status === 'verified')
                                                        <span class="badge badge-success gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Verified
                                                        </span>
                                                    @elseif($report->status === 'resolved')
                                                        <span class="badge badge-info gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Resolved
                                                        </span>
                                                    @else
                                                        <span class="badge badge-error gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            Rejected
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-sm">
                                                    <div class="flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        {{ $report->reported_at->format('M d, Y') }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-ghost btn-xs gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-12">
                                                    <div class="flex flex-col items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-base-content/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        <div class="text-base-content/60 font-medium">No reports found</div>
                                                        <p class="text-sm text-base-content/40">Reports will appear here once submitted</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Reports by Type Chart -->
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <h2 class="card-title text-xl mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Reports by Type
                            </h2>

                            <div class="space-y-4">
                                @forelse($reportByType as $type)
                                    <div class="bg-base-200 p-4 rounded-lg hover:bg-base-300 transition-colors cursor-pointer" onclick="filterByType('{{ $type->type }}')">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm font-semibold">{{ ucfirst($type->type) }}</span>
                                            <div class="badge badge-primary badge-sm">{{ $type->count }}</div>
                                        </div>
                                        <progress
                                            class="progress progress-primary w-full h-2 progress-bar"
                                            value="0"
                                            data-target="{{ $type->count }}"
                                            max="{{ $stats['total_reports'] }}">
                                        </progress>
                                        <div class="text-xs text-base-content/60 mt-1">
                                            {{ $stats['total_reports'] > 0 ? number_format(($type->count / $stats['total_reports']) * 100, 1) : 0 }}% of total
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-base-content/20 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <div class="text-base-content/60 font-medium">No report data</div>
                                        <p class="text-xs text-base-content/40 mt-1">Statistics will appear here</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Counter Animation
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current).toLocaleString();
                }
            }, 16);
        }

        // Animate progress bars
        function animateProgressBars() {
            document.querySelectorAll('.progress-bar').forEach(bar => {
                const target = parseInt(bar.getAttribute('data-target'));
                let current = 0;
                const increment = target / 50;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        bar.value = target;
                        clearInterval(timer);
                    } else {
                        bar.value = Math.floor(current);
                    }
                }, 20);
            });
        }

        // Update current time
        function updateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            document.getElementById('current-time').textContent = now.toLocaleDateString('en-US', options);
        }

        // Filter reports table
        function filterReportsTable(status) {
            const rows = document.querySelectorAll('.report-row');
            rows.forEach(row => {
                if (status === 'all' || row.getAttribute('data-status') === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Filter by type (redirect to reports page with filter)
        function filterByType(type) {
            window.location.href = '{{ route("admin.reports.index") }}?type=' + type;
        }

        // Filter reports by status
        function filterReports(status) {
            window.location.href = '{{ route("admin.reports.index") }}?status=' + status;
        }

        // Refresh dashboard
        function refreshDashboard() {
            location.reload();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Animate counters
            document.querySelectorAll('.counter').forEach(counter => {
                animateCounter(counter);
            });

            // Animate progress bars
            animateProgressBars();

            // Update time
            updateTime();
            setInterval(updateTime, 60000); // Update every minute

            // Add fade-in animation to cards
            const cards = document.querySelectorAll('.card, .stats');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Auto-refresh stats every 5 minutes
        setInterval(() => {
            console.log('Auto-refreshing stats...');
            // You can implement AJAX call here to update stats without page reload
        }, 300000);
    </script>
</x-app-layout>
