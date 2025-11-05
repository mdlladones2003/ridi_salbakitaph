<x-app-layout>
    <div class="p-6 max-w-6xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="flex items-center gap-2 text-3xl font-bold text-base-content">
                <x-lucide-file-text class="w-6 h-6" />
                Report Details
            </h1>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline btn-sm">
                <x-lucide-arrow-left class="w-4 h-4 mr-1" /> Back to Reports
            </a>
        </div>

        <div class="bg-base-100 rounded-md shadow-md p-8 border border-base-300">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h2 class="text-lg font-semibold text-blue-600 mb-3 flex items-center gap-2">
                        <x-lucide-file-text class="w-5 h-5" /> Report Information
                    </h2>
                    <ul class="space-y-2 text-sm text-base-content">
                        <li><strong>Reported At:</strong> {{ $report->reported_at->format('M d, Y - h:i A') }}</li>
                        <li><strong>Status:</strong>
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
                        </li>
                        @if ($report->resolved_at)
                            <li><strong>Resolved At:</strong> {{ $report->resolved_at->format('M d, Y - h:i A') }}</li>
                        @endif
                    </ul>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-blue-600 mb-3 flex items-center gap-2">
                        <x-lucide-user class="w-5 h-5" /> Reporter Details
                    </h2>
                    @if ($report->user)
                        <ul class="space-y-2 text-sm text-base-content">
                            <li><strong>Name:</strong> {{ $report->user->first_name }} {{ $report->user->last_name }}</li>
                            <li><strong>Email:</strong> {{ $report->user->email }}</li>
                            <li><strong>Phone:</strong> {{ $report->user->phone_number ?? 'N/A' }}</li>
                        </ul>
                    @else
                        <p class="italic text-base-content/60 text-sm">No reporter information available.</p>
                    @endif
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-blue-600 mb-3 flex items-center gap-2">
                        <x-lucide-map-pin class="w-5 h-5" /> Barangay
                    </h2>
                    <p class="text-sm text-base-content">
                        {{ $report->barangay->name }}, {{ $report->barangay->municipality }}
                    </p>
                </div>
            </div>

            @if(!empty($report->content))
                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-blue-600 mb-3 flex items-center gap-2">
                        <x-lucide-align-left class="w-5 h-5" /> Report Content
                    </h2>
                    <div class="bg-base-200 rounded-md p-4 text-sm text-base-content border border-base-300">
                        {{ $report->content }}
                    </div>
                </div>
            @endif

            <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-base-300 pt-4">
                <form action="{{ route('admin.reports.update-status', $report) }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="select select-bordered select-md" required>
                        @foreach(['pending', 'verified', 'resolved', 'false_alarm'] as $statusOption)
                            <option value="{{ $statusOption }}" @selected($report->status === $statusOption)>
                                {{ ucfirst(str_replace('_', ' ', $statusOption)) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-md bg-blue-600 hover:bg-blue-700 text-white flex items-center gap-2 px-4">
                        <x-lucide-refresh-cw class="w-4 h-4" />
                        Update Status
                    </button>
                </form>

                <label for="modal-delete-{{ $report->report_id }}"
                    class="btn btn-md bg-red-600 hover:bg-red-700 text-white flex items-center gap-2 px-4 cursor-pointer">
                    <x-lucide-trash-2 class="w-4 h-4" />
                    Delete Report
                </label>
            </div>

            <input type="checkbox" id="modal-delete-{{ $report->report_id }}" class="modal-toggle" />
            <div class="modal">
                <div class="modal-box rounded-md border border-base-300 shadow-lg p-6 max-w-sm">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="bg-red-100 text-red-600 p-2 rounded-full">
                            <x-lucide-alert-triangle class="w-5 h-5" />
                        </div>
                        <h3 class="font-semibold text-lg text-red-600">Confirm Deletion</h3>
                    </div>

                    <div class="space-y-2 text-center">
                        <p class="text-sm text-base-content">
                            You are about to delete <strong>Report Information</strong>.
                        </p>
                        <p class="text-xs text-red-500 font-medium">
                            This action cannot be undone.
                        </p>
                    </div>

                    <div class="modal-action mt-5 flex justify-end gap-2">
                        <label for="modal-delete-{{ $report->report_id }}"
                            class="btn btn-ghost btn-sm border border-base-300 hover:bg-base-200">
                            Cancel
                        </label>

                        <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-sm bg-red-600 hover:bg-red-700 text-white flex text-sm items-center px-2">
                                <x-lucide-trash-2 class="w-4 h-4" />
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
