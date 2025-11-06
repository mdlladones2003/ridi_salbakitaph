<div x-show="activeTab === 'reports'" x-transition x-data="{ filter: 'all', showForm: false }">
    <div class="flex flex-wrap justify-between items-center mb-6 gap-3">
        <h2 class="text-2xl font-semibold text-gray-800">Community Reports</h2>

        <div class="flex items-center gap-3">
            <select x-model="filter" class="border border-gray-300 rounded-md px-4 py-2 text-sm focus:ring focus:ring-blue-200">
                <option value="all">All</option>
                <option value="pending">Pending</option>
                <option value="verified">Verified</option>
                <option value="resolved">Resolved</option>
                <option value="false alarm">False Alarm</option>
            </select>

            <button @click="showForm = !showForm" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-md shadow transition">
                <span x-show="!showForm" class="flex items-center gap-2" x-transition>
                    <svg xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Create Report</span>
                </span>

                <span x-show="showForm" class="flex items-center gap-2" x-transition>
                    <svg xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Close Form</span>
                </span>
            </button>
        </div>
    </div>

    {{-- Report Form --}}
    @include('community.awareness.partials.report-form', ['barangays' => $barangays])

    {{-- Reports Grid --}}
    <div class="grid md:grid-cols-3 gap-4">
        @forelse ($reports ?? [] as $report)
            <template x-if="filter === 'all' || filter === '{{ strtolower($report->status) }}'">
                <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition p-4 flex flex-col">
                    @if(!empty($report->image_path))
                        <img src="{{ asset('storage/' . $report->image_path) }}"
                            alt="Report Image"
                            class="w-full h-40 object-cover rounded-md mb-3">
                    @else
                        <div class="w-full h-40 bg-gray-100 flex items-center justify-center rounded-md mb-3">
                            <span class="text-gray-400 text-sm ml-2">No image</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-semibold text-gray-800 capitalize leading-tight">
                            {{ ucfirst($report->type) }}
                        </h3>
                        <div class="flex flex-col items-end space-y-0.5">
                            <span class="text-xs text-gray-500">
                                {{ $report->created_at->format('M d, Y · h:i A') }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $report->barangay->name }}, {{ $report->barangay->municipality }}
                            </span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-700 flex-1">{{ $report->content }}</p>

                    <div class="mt-3 flex justify-between items-center">
                        <span class="text-xs font-medium px-2 py-1 rounded-full
                            @switch($report->status)
                                @case('pending') bg-yellow-100 text-yellow-700 @break
                                @case('verified') bg-blue-100 text-blue-700 @break
                                @case('resolved') bg-green-100 text-green-700 @break
                                @case('false alarm') bg-red-100 text-red-700 @break
                                @default bg-gray-100 text-gray-600
                            @endswitch">
                            {{ Str::of($report->status)->replace('_', ' ')->title() }}
                            @if($report->status === 'verified' && isset($report->report_verified_count))
                                <span class="ml-1 font-semibold text-blue-700">
                                    ({{ $report->report_verified_count }})
                                </span>
                            @endif
                        </span>

                        @if($report->status === 'pending' && !$report->verified_by_me)
                            <form action="{{ route('reports.verify', $report->report_id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="text-sm text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-md">
                                    Verify
                                </button>
                            </form>
                        @elseif($report->verified_by_me && $report->report_verified_count <= 3)
                            <span class="text-sm text-green-700 font-medium px-3 py-1 bg-green-100 rounded-md">
                                You Verified
                            </span>
                        @elseif($report->status === 'verified' && $report->verified_by_me)
                            <span class="text-sm text-green-700 font-medium px-3 py-1 bg-green-100 rounded-md">
                                You Verified
                            </span>
                        @endif
                    </div>
                </div>
            </template>
        @empty
            <p class="text-gray-500 col-span-3">No reports found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $reports->links() }}
    </div>
</div>
