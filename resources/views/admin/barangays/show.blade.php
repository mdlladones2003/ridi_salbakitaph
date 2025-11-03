<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 space-y-6 mt-20">
        <h1 class="text-3xl font-bold text-primary">{{ $barangay->name }}</h1>
        <p class="text-base-content/70">{{ $barangay->municipality }}, {{ $barangay->province }}</p>

        @if(session('success'))
            <div class="alert alert-success shadow">{{ session('success') }}</div>
        @endif

        <div class="bg-base-100 rounded-lg shadow p-6 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
                <div class="bg-info p-4 rounded-lg text-info-content">
                    <div class="text-xl font-bold">{{ $stats['total_reports'] }}</div>
                    <div>Total Reports</div>
                </div>
                <div class="bg-warning p-4 rounded-lg text-warning-content">
                    <div class="text-xl font-bold">{{ $stats['pending_reports'] }}</div>
                    <div>Pending Reports</div>
                </div>
                <div class="bg-success p-4 rounded-lg text-success-content">
                    <div class="text-xl font-bold">{{ $stats['verified_reports'] }}</div>
                    <div>Verified Reports</div>
                </div>
                <div class="bg-error p-4 rounded-lg text-error-content">
                    <div class="text-xl font-bold">{{ $stats['critical_reports'] }}</div>
                    <div>Critical Reports</div>
                </div>
                <div class="bg-primary p-4 rounded-lg text-primary-content">
                    <div class="text-xl font-bold">{{ $stats['evacuation_centers'] }}</div>
                    <div>Evacuation Centers</div>
                </div>
                <div class="bg-secondary p-4 rounded-lg text-secondary-content">
                    <div class="text-xl font-bold">{{ $stats['evacuation_routes'] }}</div>
                    <div>Evacuation Routes</div>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-semibold mb-4">Reports by Type</h2>
                <div class="space-y-4">
                    @forelse($reportsByType as $type)
                        <div class="bg-base-200 p-4 rounded-lg flex justify-between items-center">
                            <span class="font-semibold capitalize">{{ $type->type }}</span>
                            <span class="badge badge-primary">{{ $type->count }}</span>
                        </div>
                    @empty
                        <p class="text-base-content/60 italic">No reports data available.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-semibold mb-4">Recent Reports</h2>
                @if($barangay->reports->isEmpty())
                    <p class="text-base-content/60 italic">No recent reports.</p>
                @else
                    <ul class="list-disc list-inside space-y-1 max-h-72 overflow-auto">
                        @foreach($barangay->reports as $report)
                        <li>
                            <a href="{{ route('admin.reports.show', $report) }}" class="link link-primary">
                                {{ ucfirst($report->type) }} - {{ $report->reported_at->format('M d, Y') }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="pt-4">
            <a href="{{ route('admin.barangays.edit', $barangay) }}" class="btn btn-primary mr-4">Edit Barangay</a>
            <a href="{{ route('admin.barangays.index') }}" class="btn btn-outline">Back to List</a>
        </div>
    </div>
</x-app-layout>
