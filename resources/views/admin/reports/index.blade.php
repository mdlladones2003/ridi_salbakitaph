<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-lucide-clipboard-list class="w-6 h-6" />
                <h1 class="text-2xl font-bold text-base-content">Reports Management</h1>
            </div>
        </div>

        <div class="flex justify-end">
            <form method="GET" action="{{ route('admin.reports.index') }}"
                class="flex flex-wrap items-center gap-3 bg-base-100 border border-base-300 rounded-md shadow-sm p-3 w-auto">

                <select name="status" class="select select-bordered select-md w-44">
                    <option value="">All Status</option>
                    <option value="pending" @selected(request('status')=='pending')>Pending</option>
                    <option value="verified" @selected(request('status')=='verified')>Verified</option>
                    <option value="resolved" @selected(request('status')=='resolved')>Resolved</option>
                    <option value="false_alarm" @selected(request('status')=='false_alarm')>False Alarm</option>
                </select>

                <button type="submit" class="btn btn-md bg-blue-600 hover:bg-blue-700 text-white px-4">
                    <x-lucide-filter class="w-4 h-4" />
                    Filter
                </button>
            </form>
        </div>

        <div class="bg-white border border-base-300 rounded-md shadow-sm overflow-hidden">
            <table class="table w-full text-sm">
                <thead class="text-base-content text-xs uppercase tracking-wider">
                    <tr>
                        <th><input type="checkbox" id="select-all" class="checkbox checkbox-sm" /></th>
                        <th>ID</th>
                        <th>Reporter</th>
                        <th>Barangay</th>
                        <th>Date Reported</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr class="hover:bg-blue-50/50 transition">
                            <td>
                                <input type="checkbox" name="report_ids[]" value="{{ $report->report_id }}"
                                    class="checkbox checkbox-sm select-report" form="bulk-action-form" />
                            </td>
                            <td class="font-semibold text-base-content/80">#{{ $report->report_id }}</td>
                            <td>
                                <div class="items-center gap-2">
                                    <div class="leading-tight">
                                        <div class="font-medium text-xs">{{ $report->user->full_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
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
                            <td>{{ $report->reported_at->format('M d, Y') }}</td>
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
                            <td class="text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-ghost btn-xs text-blue-600 hover:bg-blue-50">
                                        <x-lucide-eye class="w-4 h-4" />
                                    </a>

                                    <div>
                                        <form action="{{ route('admin.reports.update-status', $report) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="select select-bordered select-md" onchange="this.form.submit()">
                                                @foreach(['pending', 'verified', 'resolved', 'false_alarm'] as $statusOption)
                                                    <option value="{{ $statusOption }}" @selected($report->status === $statusOption)>
                                                        {{ ucfirst(str_replace('_', ' ', $statusOption)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </div>

                                    <label for="modal-delete-{{ $report->report_id }}" class="btn btn-ghost btn-xs text-red-600 hover:bg-red-50 cursor-pointer">
                                        <x-lucide-trash class="w-4 h-4" />
                                    </label>

                                    <input type="checkbox" id="modal-delete-{{ $report->report_id }}" class="modal-toggle" />
                                    <div class="modal">
                                        <div class="modal-box rounded-md border border-base-300 shadow-lg p-6 max-w-sm">
                                            <div class="flex items-center gap-2 mb-3">
                                                <div class="bg-red-100 text-red-600 p-2 rounded-full">
                                                    <x-lucide-alert-triangle class="w-5 h-5" />
                                                </div>
                                                <h3 class="font-semibold text-lg text-red-600">Confirm Deletion</h3>
                                            </div>

                                            <div class="space-y-2">
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
                                                            class="btn btn-sm bg-red-600 hover:bg-red-700 text-white flex items-center px-2">
                                                        <x-lucide-trash-2 class="w-4 h-4" />
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-base-content/60">No reports found.</td>
                        </tr>
                    @endforelse
                </tbody>
             </table>
        </div>

        <div class="flex items-center gap-3">
            <select id="bulk-action-select" class="select select-bordered select-md w-52">
                <option value="" disabled selected>Select bulk action</option>
                <option value="delete">Delete Selected</option>
                <option value="mark_pending">Mark as Pending</option>
                <option value="mark_verified">Mark as Verified</option>
                <option value="mark_resolved">Mark as Resolved</option>
                <option value="mark_false_alarm">Mark as False Alarm</option>
            </select>
            <button type="button" class="btn bg-blue-600 hover:bg-blue-700 btn-md text-white px-4" onclick="openBulkActionModal()">Apply</button>
        </div>

        <dialog id="bulkActionModal" class="modal">
            <div class="modal-box rounded-md">
                <h3 class="font-bold text-lg text-base-content">Confirm Bulk Action</h3>
                <p class="py-4">Apply <strong id="action-text"></strong> to
                <strong id="selected-count"></strong> selected report(s)?</p>
                <div class="modal-action">
                <form method="dialog"><button class="btn btn-ghost btn-md px-4">Cancel</button></form>
                <form action="{{ route('admin.reports.bulk-action') }}" method="POST" id="bulk-action-form">
                    @csrf
                    <input type="hidden" name="action" id="hidden-action">
                    <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 btn-md text-white px-4">Confirm</button>
                </form>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop"><button>close</button></form>
        </dialog>

        <div class="flex justify-end mt-4">
            {{ $reports->links() }}
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
        document.querySelectorAll('.select-report').forEach(cb => cb.checked = this.checked);
        });

        function openBulkActionModal() {
        const action = document.getElementById('bulk-action-select').value;
        const selected = document.querySelectorAll('.select-report:checked');
        if (!action) return alert('Please select an action.');
        if (selected.length === 0) return alert('Select at least one report.');

        const form = document.getElementById('bulk-action-form');
        form.querySelectorAll('input[name="report_ids[]"]').forEach(e => e.remove());
        selected.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'report_ids[]';
            input.value = cb.value;
            form.appendChild(input);
        });

        document.getElementById('hidden-action').value = action;
        document.getElementById('action-text').textContent = document.querySelector(`#bulk-action-select option[value="${action}"]`).textContent;
        document.getElementById('selected-count').textContent = selected.length;
        document.getElementById('bulkActionModal').showModal();
        }
    </script>
</x-app-layout>
