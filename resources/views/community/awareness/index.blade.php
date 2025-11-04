<x-app-layout>
    <div
        x-data="{
            activeTab: '{{ $activeTab ?? 'reports' }}',
            open: true
        }"
        x-init="
            const urlTab = new URLSearchParams(window.location.search).get('tab');
            if (urlTab) activeTab = urlTab;
        "
        class="flex flex-row max-w-7xl mx-auto">
        <div>
            <div class="py-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-700">Awareness</h2>
            </div>

            <nav class="py-4 space-y-2">
                <div @click="activeTab = 'reports'; history.replaceState(null, '', '?tab=reports&page=1')"
                    :class="activeTab === 'reports' ? 'ring-2 ring-blue-300 bg-blue-50' : 'border-gray-200 bg-white hover:bg-gray-100'"
                    class="cursor-pointer border rounded-xl p-4 shadow-sm transition transform hover:scale-[1.02]">
                    <div class="flex items-center gap-3">
                        <div>
                            <h3 class="font-semibold text-gray-800">Community Reports</h3>
                            <p class="text-sm text-gray-500">Submitted incident updates</p>
                        </div>
                    </div>
                </div>

                <div @click="activeTab = 'alerts'; history.replaceState(null, '', '?tab=alerts&page=1')"
                    :class="activeTab === 'alerts' ? 'ring-2 ring-blue-300 bg-blue-50' : 'border-gray-200 bg-white hover:bg-gray-100'"
                    class="cursor-pointer border rounded-xl p-4 shadow-sm transition transform hover:scale-[1.02]">
                    <div class="flex items-center gap-3">
                        <div>
                            <h3 class="font-semibold text-gray-800">Active Alerts</h3>
                            <p class="text-sm text-gray-500">Ongoing disaster notifications</p>
                        </div>
                    </div>
                </div>

                <div @click="activeTab = 'disasters'; history.replaceState(null, '', '?tab=disasters&page=1')"
                    :class="activeTab === 'disasters' ? 'ring-2 ring-blue-300 bg-blue-50' : 'border-gray-200 bg-white hover:bg-gray-100'"
                    class="cursor-pointer border rounded-xl p-4 shadow-sm transition transform hover:scale-[1.02]">
                    <div class="flex items-center gap-3">
                        <div>
                            <h3 class="font-semibold text-gray-800">Disaster Updates</h3>
                            <p class="text-sm text-gray-500">Latest official announcements</p>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <main class="flex-1 p-6 overflow-y-auto">
            {{-- Reports --}}
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

                <div x-show="showForm"
                    x-collapse
                    x-transition
                    x-data="reportForm"
                    class="mb-8 bg-white p-6 rounded-lg shadow-sm border">

                    <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h3 class="text-lg font-semibold mb-3 text-gray-800">Create a New Report</h3>

                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Barangay</label>
                                <select name="barangay_id" id="barangaySelect"
                                        class="w-full border-gray-300 rounded-md mt-1"
                                        x-model="selectedBarangay"
                                        @change="updateMapFromSelect">
                                    <option value="">Select Barangay</option>
                                    @foreach($barangays as $barangay)
                                        <option value="{{ $barangay->barangay_id }}"
                                            data-lat="{{ $barangay->latitude }}"
                                            data-lon="{{ $barangay->longitude }}">
                                            {{ $barangay->name }} ({{ $barangay->municipality }})
                                        </option>
                                    @endforeach
                                </select>

                                <button type="button"
                                        class="text-sm text-blue-600 hover:underline mt-2"
                                        @click="showNewBarangay = !showNewBarangay">
                                    <template x-if="!showNewBarangay">
                                        <span>+ Add New Barangay</span>
                                    </template>
                                    <template x-if="showNewBarangay">
                                        <span>× Cancel New Barangay</span>
                                    </template>
                                </button>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Disaster Type</label>
                                <select name="type" class="w-full border-gray-300 rounded-md mt-1" required>
                                    <option value="">Select Type</option>
                                    @foreach(['flood', 'fire', 'earthquake', 'typhoon', 'landslide'] as $type)
                                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Severity Level</label>
                                <select name="severity" class="w-full border-gray-300 rounded-md mt-1" required>
                                    <option value="">Select Severity</option>
                                    @foreach(['low', 'moderate', 'high', 'critical'] as $severity)
                                        <option value="{{ $severity }}">{{ ucfirst($severity) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div x-show="showNewBarangay" x-collapse class="mt-4 border-t pt-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Add New Barangay</h4>
                            <div class="grid md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Barangay Name</label>
                                    <input type="text" name="barangay_name"
                                        class="w-full border-gray-300 rounded-md mt-1"
                                        placeholder="Enter barangay name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Municipality</label>
                                    <input type="text" name="municipality"
                                        class="w-full border-gray-300 rounded-md mt-1"
                                        placeholder="Enter municipality">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Risk Level</label>
                                    <select name="risk_level" class="w-full border-gray-300 rounded-md mt-1">
                                        <option value="">Select Risk Level</option>
                                        <option value="low">Low</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Incident Description</label>
                            <textarea name="content" rows="3" placeholder="Describe the incident"
                                class="w-full border-gray-300 rounded-md mt-1"></textarea>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Attach Image (optional)</label>
                                <input type="file" name="media[]" multiple accept="image/*"
                                    class="w-full border-gray-300 rounded-md mt-1">
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Estimated Affected People</label>
                                <input type="number" name="affected_count" min="0"
                                    class="w-full border-gray-300 rounded-md mt-1" placeholder="e.g. 25">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-semibold mb-2">Barangay Location</label>
                            <div id="map" class="w-full h-80 rounded-lg border"></div>
                        </div>

                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                        <div class="mt-4 flex justify-end">
                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">
                                Submit Report
                            </button>
                        </div>
                    </form>
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    @forelse ($reports ?? [] as $report)
                        <template x-if="filter === 'all' || filter === '{{ strtolower($report->status) }}'">
                            <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition p-4 flex flex-col">
                                @if(!empty($report->media) && is_array($report->media) && count($report->media) > 0)
                                    <img src="{{ asset('storage/' . $report->media[0]) }}"
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

            {{-- Alerts --}}
            <div x-show="activeTab === 'alerts'" x-transition>
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Community Alerts</h2>
                @forelse ($alerts as $alert)
                    <div class="p-4 mb-3 bg-white shadow-sm rounded-md border-l-4
                        @switch($alert->severity)
                            @case('critical') border-red-500 @break
                            @case('warning') border-yellow-500 @break
                            @default border-blue-500
                        @endswitch">
                        <div class="flex justify-between items-center">
                            <h3 class="font-semibold text-lg text-gray-800 capitalize">{{ $alert->disasterUpdate->type }}</h3>
                            <span class="text-xs text-gray-500 whitespace-nowrap">{{ $alert->sent_at->format('M d, Y · h:i A') }}</span>
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $alert->message }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">No active alerts at this time.</p>
                @endforelse

                <div class="mt-4">
                    {{ $alerts->links() }}
                </div>
            </div>

            {{-- Disasters --}}
            <div x-show="activeTab === 'disasters'" x-transition>
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Community Disaster Updates</h2>
                @forelse ($disasters as $disaster)
                    <div class="p-4 mb-3 bg-white shadow rounded-md border-l-4
                        @switch(strtolower($disaster->type))
                            @case('flood') border-blue-500 @break
                            @case('fire') border-red-500 @break
                            @case('earthquake') border-yellow-500 @break
                            @case('typhoon') border-indigo-500 @break
                            @case('landslide') border-green-500 @break
                            @default border-gray-400
                        @endswitch">
                        <div class="flex justify-between items-center">
                            <h3 class="font-semibold text-lg text-gray-800 capitalize">{{ $disaster->type }}</h3>
                            <p class="text-xs text-gray-400 whitespace-nowrap">{{ $disaster->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ Str::limit($disaster->content, 120) }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">No disaster updates available.</p>
                @endforelse

                <div class="mt-4">
                    {{ $disasters->links() }}
                </div>
            </div>
        </main>

    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('reportForm', () => ({
                    showNewBarangay: false,
                    selectedBarangay: '',
                    map: null,
                    marker: null,
                    geoApiKey: 'YOUR_GEOAPIFY_API_KEY',

                    init() {
                        this.initMap();
                        this.setupNewBarangayWatcher();
                    },

                    initMap() {
                        this.map = L.map('map').setView([13.4108, 123.3267], 11);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                        }).addTo(this.map);
                    },

                    updateMap(lat, lon) {
                        if (!lat || !lon) return;
                        this.map.setView([lat, lon], 14);
                        if (this.marker) this.map.removeLayer(this.marker);
                        this.marker = L.marker([lat, lon]).addTo(this.map);

                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lon;
                    },

                    updateMapFromSelect(event) {
                        const option = event.target.selectedOptions[0];
                        const lat = option.dataset.lat;
                        const lon = option.dataset.lon;
                        this.updateMap(lat, lon);
                    },

                    setupNewBarangayWatcher() {
                        document.querySelectorAll('input[name="barangay_name"], input[name="municipality"]').forEach(input => {
                            input.addEventListener('change', () => this.searchAndSetLocation());
                        });
                    },

                    async searchAndSetLocation() {
                        const barangay = document.querySelector('input[name="barangay_name"]').value.trim();
                        const municipality = document.querySelector('input[name="municipality"]').value.trim();
                        if (!barangay || !municipality) return;

                        const query = encodeURIComponent(`${barangay}, ${municipality}, Philippines`);
                        try {
                            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`);
                            const data = await res.json();

                            if (data.length > 0) {
                                const { lat, lon } = data[0];
                                this.updateMap(lat, lon);
                            } else {
                                alert('Location not found. Please adjust the barangay or municipality name.');
                            }
                        } catch (error) {
                            console.error('Geocoding error:', error);
                        }
                    }
                }))
            })
        </script>
    @endpush
</x-app-layout>
