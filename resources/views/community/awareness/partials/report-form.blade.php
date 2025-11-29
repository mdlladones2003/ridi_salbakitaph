<div x-show="showForm"
    x-collapse
    x-transition
    x-data="reportForm"
    class="mb-8 bg-white p-6 rounded-lg shadow-sm border">

    <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <h3 class="text-lg font-semibold mb-3 text-gray-800">Create a New Report</h3>

        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
            <h4 class="font-semibold text-base-content mb-3 flex items-center gap-2">
                <x-lucide-map-pin class="w-4 h-4" />
                Step 1: Pin the Incident Location
            </h4>

            <div class="mb-4">
                <button type="button"
                        class="text-sm bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition flex items-center gap-2"
                        @click="getCurrentLocation()">
                    <x-lucide-crosshair class="w-4 h-4" />
                    <span x-text="locationButtonText"></span>
                </button>
                <p class="text-xs text-gray-500 mt-1">Click to automatically detect your location</p>
            </div>

            <div class="relative">
                <div id="map" class="w-full h-96 rounded-lg border border-gray-300 shadow-sm bg-gray-100"></div>

                <div x-show="!mapInitialized"
                     class="absolute inset-0 flex items-center justify-center bg-gray-100 rounded-lg z-[999]">
                    <button type="button"
                            @click="initMap()"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-blue-700 transition flex items-center gap-2">
                        <x-lucide-map class="w-5 h-5" />
                        Load Map
                    </button>
                </div>

                <div x-show="mapInitialized" class="absolute top-2 right-2 bg-white px-3 py-2 rounded-md shadow-md text-xs text-gray-600 z-[1000]">
                    <strong>Tip:</strong> Drag marker or click map to set location
                </div>
            </div>

            <input type="hidden" name="latitude" id="latitude" x-model="latitude" required>
            <input type="hidden" name="longitude" id="longitude" x-model="longitude" required>

            <div x-show="latitude && longitude"
                 x-transition
                 class="mt-3 p-3 bg-green-50 border border-green-200 rounded-md">
                <div class="flex items-center gap-2 text-sm text-green-800">
                    <x-lucide-check-circle class="w-5 h-5 text-green-600" />
                    <span class="font-medium">Location set successfully</span>
                </div>
            </div>
        </div>

        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
            <h4 class="font-semibold text-base-content mb-3 flex items-center gap-2">
                <x-lucide-info class="w-4 h-4" />
                Step 2: Incident Information
            </h4>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-base-content">
                        Disaster Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" class="w-full border-gray-300 rounded-md mt-1" required>
                        <option value="">Select Disaster Type</option>
                        @foreach(['flood', 'fire', 'earthquake', 'typhoon', 'landslide'] as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-base-content">
                        Severity Level <span class="text-red-500">*</span>
                    </label>
                    <select name="severity" class="w-full border-gray-300 rounded-md mt-1" required>
                        <option value="">Select Severity</option>
                        <option value="low">Low - Minor impact</option>
                        <option value="moderate">Moderate - Significant concern</option>
                        <option value="high">High - Serious situation</option>
                        <option value="critical">Critical - Emergency</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-base-content">
                    Incident Description <span class="text-red-500">*</span>
                </label>
                <textarea name="content" rows="4"
                    placeholder="Describe what happened, when it started, current situation, and any immediate dangers..."
                    class="w-full border-gray-300 rounded-md mt-1"
                    required></textarea>
                <p class="text-xs text-gray-500 mt-1">Be specific and include important details that can help responders</p>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-base-content">Estimated Affected People</label>
                    <input type="number" name="affected_count" min="0"
                        class="w-full border-gray-300 rounded-md mt-1"
                        placeholder="e.g., 25">
                    <p class="text-xs text-gray-500 mt-1">Approximate number of people affected</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-base-content">
                        Attach Images (Optional)
                    </label>
                    <input type="file" name="media" accept="image/*"
                        class="w-full border-gray-300 rounded-md mt-1"
                        @change="handleFileSelect">
                    <p class="text-xs text-gray-500 mt-1">Upload photos of the incident (max 5 files)</p>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center pt-4 border-t">
            <div class="flex gap-3">
                <button type="button"
                        @click="showForm = false"
                        class="px-5 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow transition flex items-center gap-2">
                    <x-lucide-send class="w-5 h-5" />
                    Submit Report
                </button>
            </div>
        </div>
    </form>
</div>
