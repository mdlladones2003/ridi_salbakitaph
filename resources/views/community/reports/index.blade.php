<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Community Reports</h1>

            <a href="{{ route('reports.create') }}" class="btn flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Create Report
            </a>
        </div>

        <div class="bg-base-100 border border-base-300 p-4 rounded-md mb-4">
            <div class="flex flex-wrap gap-4">
                <input
                    type="text"
                    placeholder="Search reports..."
                    class="input input-bordered flex-1 min-w-[250px]"
                    id="searchInput"
                    onkeyup="filterReports()" />

                <select id="statusFilter" class="select select-bordered flex-1 min-w-[150px]">
                    <option value="">All Statuses</option>
                    <option value="verified">Verified</option>
                    <option value="resolved">Resolved</option>
                    @if(auth()->user()->role === 'official' || auth()->user()->role === 'volunteer')
                        <option value="pending">Pending</option>
                        <option value="false_alarm">False Alarm</option>
                    @endif
                </select>

                <select id="typeFilter" class="select select-bordered flex-1 min-w-[150px]">
                    <option value="">All Types</option>
                    <option value="flood">Flood</option>
                    <option value="fire">Fire</option>
                    <option value="earthquake">Earthquake</option>
                    <option value="landslide">Landslide</option>
                    <option value="storm">Storm</option>
                </select>
            </div>
        </div>

        <div id="reportList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($reports ?? [] as $report)
                <div class="card bg-base-100 shadow-sm border border-base-300 hover:shadow-md transition">
                    <div class="card-body p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold capitalize">{{ $report->type }}</h3>
                                <p class="text-xs opacity-60">
                                    📍 {{ $report->barangay->name }}, {{ $report->barangay->municipality }}
                                </p>
                            </div>

                            {{-- Status or Verification Count --}}
                            @if($report->status === 'pending')
                                <span class="badge badge-warning">
                                    {{ $report->verification_count }} Verification{{ ($report->verification_count) !== 1 ? 's' : '' }}
                                </span>
                            @elseif($report->status === 'verified')
                                <span class="badge badge-info">Verified</span>
                            @elseif($report->status === 'resolved')
                                <span class="badge badge-success">Resolved</span>
                            @elseif($report->status === 'false_alarm')
                                <span class="badge badge-error">False Alarm</span>
                            @else
                                <span class="badge badge-ghost">Unknown</span>
                            @endif
                        </div>

                        <p class="text-sm mt-2 line-clamp-3">{{ $report->content ?? 'No details provided.' }}</p>

                        @if(!empty($report->media) && is_array($report->media) && count($report->media) > 0)
                            <div class="mt-2 h-32">
                                <img src="{{ asset('storage/' . $report->media[0]) }}"
                                    alt="Report Media"
                                    class="rounded-lg w-full h-full object-cover">
                            </div>
                        @endif

                        <div class="mt-3 flex justify-between items-center text-xs text-gray-500">
                            <span>{{ $report->user->first_name }} {{ $report->user->last_name }}</span>
                            <span>{{ $report->created_at?->diffForHumans() }}</span>
                        </div>

                        <a href="{{ route('community.reports.show', $report) }}" class="btn btn-sm btn-outline w-full mt-3">
                            View Details
                        </a>

                        @if($report->status === 'pending')
                            <form action="{{ route('reports.verify', $report) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary w-full">Verify Report</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 opacity-60">
                    <div class="text-5xl mb-3">📋</div>
                    No reports found.
                </div>
            @endforelse
        </div>
    </div>

    @push('scripts')
    <script>
        function filterReports() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const status = document.getElementById('statusFilter').value;
            const type = document.getElementById('typeFilter').value;

            document.querySelectorAll('#reportList .card').forEach(card => {
                const text = card.innerText.toLowerCase();
                const matchesSearch = !search || text.includes(search);
                const matchesStatus = !status || text.includes(status);
                const matchesType = !type || text.includes(type);
                card.style.display = (matchesSearch && matchesStatus && matchesType) ? '' : 'none';
            });
        }

        document.getElementById('statusFilter').addEventListener('change', filterReports);
        document.getElementById('typeFilter').addEventListener('change', filterReports);
    </script>
    @endpush
</x-app-layout>
