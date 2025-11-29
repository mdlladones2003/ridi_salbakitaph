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
