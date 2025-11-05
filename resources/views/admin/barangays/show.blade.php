<x-app-layout>
    <div class="max-w-5xl mx-auto p-6 space-y-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-primary/10 text-base-content p-2 rounded-lg">
                    <x-lucide-map-pin class="w-6 h-6" />
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-base-content">{{ $barangay->name }}</h1>
                    <p class="text-base-content/60 text-sm">
                        {{ $barangay->municipality }}, {{ $barangay->province }}
                    </p>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.barangays.edit', $barangay) }}" class="btn bg-blue-600 hover:bg-blue-700 text-white btn-sm">
                    <x-lucide-pencil class="w-4 h-4" /> Edit
                </a>
                <a href="{{ route('admin.barangays.index') }}" class="btn btn-outline btn-sm">
                    <x-lucide-arrow-left class="w-4 h-4 mr-1" /> Back to List
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow flex items-center gap-2">
                <x-lucide-check-circle class="w-5 h-5" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-blue-50 text-blue-800 rounded-md p-4 shadow-sm relative">
                <x-lucide-file-text class="w-5 h-5 absolute top-3 right-3 opacity-60" />
                <div class="text-2xl font-bold">{{ $stats['total_reports'] }}</div>
                <p class="text-sm font-medium">Total Reports</p>
            </div>
            <div class="bg-yellow-50 text-yellow-800 rounded-md p-4 shadow-sm relative">
                <x-lucide-clock class="w-5 h-5 absolute top-3 right-3 opacity-60" />
                <div class="text-2xl font-bold">{{ $stats['pending_reports'] }}</div>
                <p class="text-sm font-medium">Pending</p>
            </div>
            <div class="bg-green-50 text-green-800 rounded-md p-4 shadow-sm relative">
                <x-lucide-badge-check class="w-5 h-5 absolute top-3 right-3 opacity-60" />
                <div class="text-2xl font-bold">{{ $stats['verified_reports'] }}</div>
                <p class="text-sm font-medium">Verified</p>
            </div>
            <div class="bg-red-50 text-red-800 rounded-md p-4 shadow-sm relative">
                <x-lucide-alert-triangle class="w-5 h-5 absolute top-3 right-3 opacity-60" />
                <div class="text-2xl font-bold">{{ $stats['critical_reports'] }}</div>
                <p class="text-sm font-medium">Critical</p>
            </div>
            <div class="bg-primary text-primary-content rounded-md p-4 shadow-sm relative">
                <x-lucide-house class="w-5 h-5 absolute top-3 right-3 opacity-60" />
                <div class="text-2xl font-bold">{{ $stats['evacuation_centers'] }}</div>
                <p class="text-sm font-medium">Evacuation Centers</p>
            </div>
        </div>

        <div class="bg-base-100 rounded-md shadow p-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                <x-lucide-bar-chart-3 class="w-5 h-5 text-primary" />
                Reports by Type
            </h2>
            <div class="space-y-3">
                @forelse($reportsByType as $type)
                    <div class="flex justify-between items-center bg-base-200 p-3 rounded-lg">
                        <span class="capitalize font-medium">{{ $type->type }}</span>
                        <span class="badge bg-blue-100 text-blue-700 border-none">{{ $type->count }}</span>
                    </div>
                @empty
                    <p class="text-base-content/60 italic">No reports data available.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-base-100 rounded-md shadow p-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                <x-lucide-history class="w-5 h-5 text-primary" />
                Recent Reports
            </h2>

            @if($barangay->reports->isEmpty())
                <p class="text-base-content/60 italic">No recent reports.</p>
            @else
                <ul class="divide-y divide-base-300 max-h-72 overflow-y-auto">
                    @foreach($barangay->reports as $report)
                        <li class="py-2 flex justify-between items-center">
                            <a href="{{ route('admin.reports.show', $report) }}" class="link link-primary font-medium capitalize">
                                {{ $report->type }}
                            </a>
                            <span class="text-sm text-base-content/70">{{ $report->reported_at->format('M d, Y') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>
</x-app-layout>
