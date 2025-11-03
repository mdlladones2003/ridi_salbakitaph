<x-app-layout>
  <div class="p-6 max-w-7xl mx-auto space-y-6">
    <h1 class="text-3xl font-bold mb-6">Reports Management</h1>

    <div class="overflow-x-auto rounded-lg border border-base-300 bg-base-100 shadow">
      <table class="table w-full table-zebra">
        <thead>
          <tr>
            <th>
              <input type="checkbox" id="select-all" class="checkbox" />
            </th>
            <th>Report ID</th>
            <th>Reported By</th>
            <th>Barangay</th>
            <th>Date Reported</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($reports as $report)
          <tr>
            <td>
              <input type="checkbox" name="report_ids[]" value="{{ $report->report_id }}" class="checkbox select-report" form="bulk-action-form" />
            </td>
            <td>{{ $report->report_id }}</td>
            <td>
              {{ $report->user->first_name ?? 'N/A' }} {{ $report->user->last_name ?? '' }}
              <br>
              <small class="text-xs opacity-60">{{ $report->user->email ?? '' }}</small>
            </td>
            <td>{{ $report->barangay->name ?? 'Unassigned' }}</td>
            <td>{{ $report->reported_at->format('M d, Y') }}</td>
            <td>
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
            </td>
            <td class="space-x-2 whitespace-nowrap">
              <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-xs btn-info">View</a>

              <!-- Status Update Form -->
              <form action="{{ route('admin.reports.update-status', $report) }}" method="POST" class="inline-block">
                @csrf
                @method('PATCH')
                <select name="status" class="select select-bordered select-sm" onchange="this.form.submit()">
                  @foreach(['pending', 'verified', 'resolved', 'false_alarm'] as $statusOption)
                    <option value="{{ $statusOption }}" @selected($report->status === $statusOption)>{{ ucfirst(str_replace('_', ' ', $statusOption)) }}</option>
                  @endforeach
                </select>
              </form>

              <!-- Delete Button with Modal -->
              <button onclick="deleteModal{{ $report->report_id }}.showModal()" class="btn btn-xs btn-error">Delete</button>

              <!-- Delete Modal -->
              <dialog id="deleteModal{{ $report->report_id }}" class="modal">
                <div class="modal-box">
                  <h3 class="font-bold text-lg">Confirm Delete</h3>
                  <p class="py-4">Are you sure you want to delete report <strong>#{{ $report->report_id }}</strong>?</p>
                  <p class="text-sm text-error mb-4">This action cannot be undone.</p>
                  <div class="modal-action">
                    <form method="dialog">
                      <button class="btn btn-ghost">Cancel</button>
                    </form>
                    <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" class="inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-error">Delete Report</button>
                    </form>
                  </div>
                </div>
                <form method="dialog" class="modal-backdrop">
                  <button>close</button>
                </form>
              </dialog>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center py-8">No reports found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Bulk Action Controls -->
    <div class="flex items-center gap-4">
      <select id="bulk-action-select" class="select select-bordered max-w-xs">
        <option value="" disabled selected>Select bulk action</option>
        <option value="delete">Delete Selected</option>
        <option value="mark_pending">Mark as Pending</option>
        <option value="mark_verified">Mark as Verified</option>
        <option value="mark_resolved">Mark as Resolved</option>
        <option value="mark_false_alarm">Mark as False Alarm</option>
      </select>
      <button type="button" class="btn btn-primary" onclick="openBulkActionModal()">Apply</button>
    </div>

    <!-- Bulk Action Confirmation Modal -->
    <dialog id="bulkActionModal" class="modal">
      <div class="modal-box">
        <h3 class="font-bold text-lg">Confirm Bulk Action</h3>
        <p class="py-4">Are you sure you want to apply <strong id="action-text"></strong> to <strong id="selected-count"></strong> selected report(s)?</p>
        <p class="text-sm text-warning mb-4">This action will affect multiple reports.</p>
        <div class="modal-action">
          <form method="dialog">
            <button class="btn btn-ghost">Cancel</button>
          </form>
          <form action="{{ route('admin.reports.bulk-action') }}" method="POST" id="bulk-action-form">
            @csrf
            <input type="hidden" name="action" id="hidden-action">
            <button type="submit" class="btn btn-primary">Confirm & Apply</button>
          </form>
        </div>
      </div>
      <form method="dialog" class="modal-backdrop">
        <button>close</button>
      </form>
    </dialog>

    <div class="mt-4">
      {{ $reports->links() }}
    </div>
  </div>

  <script>
    // Select/Deselect all checkboxes toggle
    document.getElementById('select-all').addEventListener('change', function() {
      const checked = this.checked;
      document.querySelectorAll('.select-report').forEach(cb => cb.checked = checked);
    });

    // Open bulk action modal
    function openBulkActionModal() {
      const action = document.getElementById('bulk-action-select').value;
      const selectedCheckboxes = document.querySelectorAll('.select-report:checked');

      if (!action) {
        alert('Please select an action first.');
        return;
      }

      if (selectedCheckboxes.length === 0) {
        alert('Please select at least one report.');
        return;
      }

      // Add hidden inputs for selected report IDs
      const form = document.getElementById('bulk-action-form');
      // Remove old hidden inputs
      form.querySelectorAll('input[name="report_ids[]"]').forEach(input => input.remove());

      // Add new hidden inputs for each selected report
      selectedCheckboxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'report_ids[]';
        input.value = checkbox.value;
        form.appendChild(input);
      });

      // Set action in hidden field
      document.getElementById('hidden-action').value = action;

      // Update modal text
      const actionText = document.getElementById('bulk-action-select').selectedOptions[0].text;
      document.getElementById('action-text').textContent = actionText;
      document.getElementById('selected-count').textContent = selectedCheckboxes.length;

      // Open modal
      document.getElementById('bulkActionModal').showModal();
    }
  </script>
</x-app-layout>
