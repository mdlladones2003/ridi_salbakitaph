<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="bg-primary/10 text-base-content p-2 rounded-lg">
                    <x-lucide-map-pin class="w-6 h-6" />
                </div>
                <h1 class="text-3xl font-bold text-base-content">Barangay Management</h1>
            </div>
        </div>

        <div class="flex justify-end">
            <form method="GET" action="{{ route('admin.barangays.index') }}"
                class="flex flex-wrap items-center gap-3 bg-base-100 border border-base-300 rounded-md shadow-sm p-3 w-auto">

                <div class="flex items-center gap-3">
                    <select name="risk_level" class="select select-bordered w-40">
                        <option value="">All Risk Levels</option>
                        @foreach(['low', 'medium', 'high'] as $level)
                            <option value="{{ $level }}" @selected(request('risk_level') === $level)>
                                {{ ucfirst($level) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-md bg-blue-600 hover:bg-blue-700 text-white px-4">
                        <x-lucide-filter class="w-4 h-4" />
                        Filter
                    </button>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm flex items-center gap-2">
                <x-lucide-check-circle class="w-5 h-5" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error shadow-sm flex items-center gap-2">
                <x-lucide-alert-triangle class="w-5 h-5" />
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto border border-base-300 rounded-md bg-base-100 shadow-sm">
            <table class="table w-full">
                <thead class="text-base-content text-xs uppercase tracking-wider">
                    <tr>
                        <th>Name</th>
                        <th>Municipality</th>
                        <th>Province</th>
                        <th>Risk Level</th>
                        <th class="text-center">Reports</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barangays as $barangay)
                        <tr class="hover:bg-blue-50/50 transition">
                            <td class="font-semibold">{{ $barangay->name }}</td>
                            <td>{{ $barangay->municipality }}</td>
                            <td>{{ $barangay->province }}</td>
                            <td>
                                @php
                                    $color = match($barangay->risk_level) {
                                        'low' => 'bg-green-100 text-green-700 border-green-300',
                                        'medium' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                                        'high' => 'bg-red-100 text-red-700 border-red-300',
                                        default => 'bg-gray-100 text-gray-700 border-gray-300',
                                    };
                                @endphp
                                <span class="badge border {{ $color }} font-medium capitalize">
                                    {{ $barangay->risk_level ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-center">{{ $barangay->reports_count }}</td>
                            <td class="text-center whitespace-nowrap">
                                <a href="{{ route('admin.barangays.show', $barangay) }}" class="btn btn-ghost btn-xs text-blue-600 hover:bg-blue-50">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-base-content/60">
                                No barangays found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $barangays->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
