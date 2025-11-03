<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto space-y-6 mt-20">
        <h1 class="text-3xl font-bold text-primary mb-6">Report Details</h1>
        <div class="bg-base-100 rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                <!-- Report Info -->
                <div>
                    <h2 class="text-xl font-semibold text-secondary mb-2">Report Information</h2>
                    <p class="text-base-content"><strong>Report ID:</strong> {{ $report->report_id }}</p>
                    <p class="text-base-content"><strong>Reported At:</strong> {{ $report->reported_at->format('M d, Y - h:i A') }}</p>
                    <p class="text-base-content"><strong>Status:</strong>
                        <span class="badge
                        @switch($report->status)
                            @case('pending') badge-warning @break
                            @case('verified') badge-info @break
                            @case('resolved') badge-success @break
                            @case('false_alarm') badge-error @break
                            @default badge-neutral
                        @endswitch
                        ">
                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                        </span>
                    </p>
                    @if ($report->resolved_at)
                    <   p class="text-base-content"><strong>Resolved At:</strong> {{ $report->resolved_at->format('M d, Y - h:i A') }}</>
                    @endif
                </div>

                <!-- Reporter Info -->
                <div>
                    <h2 class="text-xl font-semibold text-secondary mb-2">Reporter Details</h2>
                    @if ($report->user)
                        <p class="text-base-content"><strong>Name:</strong> {{ $report->user->first_name }} {{ $report->user->last_name }}</p>
                        <p class="text-base-content"><strong>Email:</strong> {{ $report->user->email }}</p>
                        <p class="text-base-content"><strong>Phone:</strong> {{ $report->user->phone_number ?? 'N/A' }}</p>
                    @else
                        <p class="italic text-base-content/60">No reporter information available.</p>
                    @endif
                </div>

                <!-- Barangay Info -->
                <div>
                    <h2 class="text-xl font-semibold text-secondary mb-2">Barangay</h2>
                    @if ($report->barangay)
                        <p class="text-base-content">{{ $report->barangay->name }}</p>
                    @else
                        <p class="italic text-base-content/60">Not assigned</p>
                    @endif
                </div>
            </div>

            <!-- Report Description or Details Section (optional) -->
            @if(!empty($report->description))
                <div>
                    <h2 class="text-xl font-semibold text-secondary mb-2">Description</h2>
                    <p class="whitespace-pre-line text-base-content">{{ $report->description }}</p>
                </div>
            @endif

            <!-- Actions -->
            <div class="mt-6 flex flex-wrap gap-4">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline">Back to Reports</a>

                <!-- Form for status update -->
                <form action="{{ route('admin.reports.update-status', $report) }}" method="POST" class="flex gap-2 items-center">
                    @csrf
                    @method('PATCH')

                    <select name="status" class="select select-bordered select-primary" required>
                        @foreach(['pending', 'verified', 'resolved', 'false_alarm'] as $statusOption)
                        <option value="{{ $statusOption }}" @selected($report->status === $statusOption)>
                            {{ ucfirst(str_replace('_', ' ', $statusOption)) }}
                        </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>

                <!-- Delete Report -->
                <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-error">Delete Report</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
