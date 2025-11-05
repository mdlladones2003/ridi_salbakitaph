<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="bg-primary/10 text-base-content p-2 rounded-lg">
                    <x-lucide-bell-ring class="w-6 h-6" />
                </div>
                <h1 class="text-3xl font-bold text-base-content">Active Alerts</h1>
            </div>
            <a href="{{ route('admin.alerts.create') }}" class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white px-4">
                <x-lucide-plus class="w-4 h-4" />
                New Alert
            </a>
        </div>

        <div class="flex justify-end">
            <form method="GET" action="{{ route('admin.alerts.index') }}"
                class="flex flex-wrap items-center gap-3 bg-base-100 border border-base-300 rounded-md shadow-sm p-3 w-auto">

                <select name="severity" class="select select-bordered select-md w-44">
                    <option value="">All Severities</option>
                    <option value="info" @selected(request('severity')=='info')>Info</option>
                    <option value="warning" @selected(request('severity')=='warning')>Warning</option>
                    <option value="critical" @selected(request('severity')=='critical')>Critical</option>
                </select>

                <button type="submit" class="btn btn-md bg-blue-600 hover:bg-blue-700 text-white px-4">
                    <x-lucide-filter class="w-4 h-4" />
                    Filter
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow">
                <x-lucide-check-circle class="w-5 h-5" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto border border-base-300 rounded-md bg-base-100 shadow-sm">
            <table class="table w-full">
                <thead class="text-base-content text-xs uppercase tracking-wider">
                    <tr>
                        <th>Disaster Type</th>
                        <th>Message</th>
                        <th>Severity</th>
                        <th>Sent At</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alerts as $alert)
                        <tr class="hover:bg-blue-50/50 transition">
                            <td class="capitalize font-semibold">{{ ucfirst($alert->disasterUpdate->type ) }}</td>
                            <td class="max-w-md">{{ $alert->message }}</td>
                            <td>
                                <span class="badge capitalize
                                    @if($alert->severity === 'info') bg-blue-100 text-blue-700
                                    @elseif($alert->severity === 'warning') bg-yellow-100 text-yellow-700
                                    @elseif($alert->severity === 'critical') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $alert->severity }}
                                </span>
                            </td>
                            <td class="text-sm">{{ $alert->sent_at->format('M d, Y - h:i A') }}</td>
                            <td>
                                @if ($alert->is_active)
                                    <span class="badge bg-green-100 text-green-700 border-none font-medium">Active</span>
                                @else
                                    <span class="badge bg-gray-200 text-gray-700 border-none font-medium">Inactive</span>
                                @endif
                            </td>

                            <td class="text-center whitespace-nowrap">
                                <form action="{{ route('admin.alerts.toggle', $alert) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-ghost btn-xs text-green-600 hover:bg-green-50">
                                        @if ($alert->is_active)
                                            <x-lucide-eye-off class="w-4 h-4" />
                                        @else
                                            <x-lucide-eye class="w-4 h-4" />
                                        @endif
                                    </button>
                                </form>

                                <label for="modal-delete-{{ $alert->alert_id }}" class="btn btn-ghost btn-xs text-red-600 hover:bg-red-50 cursor-pointer">
                                    <x-lucide-trash class="w-4 h-4" />
                                </label>

                                <input type="checkbox" id="modal-delete-{{ $alert->alert_id }}" class="modal-toggle" />
                                <div class="modal">
                                    <div class="modal-box rounded-md border border-base-300 shadow-lg p-6 max-w-sm">
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="bg-red-100 text-red-600 p-2 rounded-full">
                                                <x-lucide-alert-triangle class="w-5 h-5" />
                                            </div>
                                            <h3 class="font-semibold text-lg text-red-600">Confirm Deletion</h3>
                                        </div>

                                        <div class="space-y-2">
                                            <p class="text-sm text-base-content text-wrap">
                                                You are about to delete the alert:
                                                <strong>{{ $alert->message }}</strong>
                                            </p>
                                            <p class="text-xs text-red-500 font-medium">
                                                This action cannot be undone.
                                            </p>
                                        </div>

                                        <div class="modal-action mt-5 flex justify-end gap-2">
                                            <label for="modal-delete-{{ $alert->alert_id }}"
                                                class="btn btn-ghost btn-sm border border-base-300 hover:bg-base-200">
                                                Cancel
                                            </label>

                                            <form action="{{ route('admin.alerts.destroy', $alert) }}" method="POST" class="inline">
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-base-content/60">
                                No alerts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $alerts->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
