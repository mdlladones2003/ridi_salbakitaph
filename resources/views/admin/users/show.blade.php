<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-base-content flex items-center gap-2">
                    <x-lucide-user class="w-6 h-6" />
                    {{ $user->first_name }} {{ $user->last_name }}
                </h1>
                <p class="text-sm text-base-content/70 mt-1 capitalize">Role: {{ str_replace('_', ' ', $user->role) }}</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn bg-blue-600 hover:bg-blue-700 text-white btn-sm">
                    <x-lucide-pencil class="w-4 h-4" /> Edit
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">
                    <x-lucide-arrow-left class="w-4 h-4" /> Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border border-base-300 rounded-md shadow-sm p-4 space-y-8">
                <div>
                    <h2 class="text-lg font-semibold text-blue-600 border-b pb-2 border-blue-200 flex items-center gap-2">
                        <x-lucide-info class="w-5 h-5" /> Basic Information
                    </h2>
                    <div class="mt-3 space-y-2 text-sm">
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Phone:</strong> {{ $user->phone_number ?? 'N/A' }}</p>
                        <p class="flex items-center gap-1">
                            <strong>Status:</strong>
                            @if ($user->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-error">Inactive</span>
                            @endif
                        </p>
                        <p class="flex items-center gap-1">
                            <strong>Verified:</strong>
                            @if ($user->is_verified)
                                <span class="badge badge-success">Yes</span>
                            @else
                                <span class="badge badge-warning">No</span>
                            @endif
                        </p>
                        <p><strong>Reputation:</strong> {{ $user->reputation_score ?? 'N/A' }}</p>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-blue-600 border-b pb-2 border-blue-200 flex items-center gap-2">
                        <x-lucide-bar-chart-3 class="w-5 h-5" /> Statistics
                    </h2>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li>Total Reports: <span class="font-semibold">{{ $stats['total_reports'] }}</span></li>
                        <li>Verified Reports: <span class="font-semibold">{{ $stats['verified_reports'] }}</span></li>
                        <li>Pending Reports: <span class="font-semibold">{{ $stats['pending_reports'] }}</span></li>
                        <li>Resolved Reports: <span class="font-semibold">{{ $stats['resolved_reports'] }}</span></li>
                        <li>Total Check-ins: <span class="font-semibold">{{ $stats['total_check_ins'] }}</span></li>
                        <li>Total Posts: <span class="font-semibold">{{ $stats['total_posts'] }}</span></li>
                        <li>Total Verifications: <span class="font-semibold">{{ $stats['total_verifications'] }}</span></li>
                        <li>Total Help Offers: <span class="font-semibold">{{ $stats['total_help_offers'] }}</span></li>
                        <li>Total Badges: <span class="font-semibold">{{ $stats['total_badges'] }}</span></li>
                    </ul>
                </div>
            </div>

            <div class="md:col-span-2 bg-white border border-base-300 rounded-md shadow-sm p-4 space-y-6">
                <h2 class="text-xl font-semibold text-blue-600 border-b pb-3 border-blue-200 flex items-center gap-2">
                    <x-lucide-activity class="w-5 h-5" /> Recent Activity
                </h2>

                <div>
                    <h3 class="font-semibold text-base text-blue-600 flex items-center gap-1">
                        <x-lucide-file-text class="w-4 h-4" /> Reports
                    </h3>
                    <ul class="list-disc list-inside mt-1 space-y-1 text-sm">
                        @forelse ($recentActivity['reports'] as $report)
                            <li>{{ $report->title ?? 'No title' }} –
                                <span class="capitalize font-medium">{{ str_replace('_', ' ', $report->status) }}</span>
                            </li>
                        @empty
                            <li class="italic text-base-content/60">No recent reports.</li>
                        @endforelse
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-base text-blue-600 flex items-center gap-1">
                        <x-lucide-map-pin class="w-4 h-4" /> Check-ins
                    </h3>
                    <ul class="list-disc list-inside mt-1 space-y-1 text-sm">
                        @forelse ($recentActivity['check_ins'] as $checkIn)
                            <li>{{ $checkIn->location ?? 'No location' }} on {{ $checkIn->created_at->format('M d, Y') }}</li>
                        @empty
                            <li class="italic text-base-content/60">No recent check-ins.</li>
                        @endforelse
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-base text-blue-600 flex items-center gap-1">
                        <x-lucide-message-square class="w-4 h-4" /> Posts
                    </h3>
                    <ul class="list-disc list-inside mt-1 space-y-1 text-sm">
                        @forelse ($recentActivity['posts'] as $post)
                            <li>{{ $post->title ?? 'No title' }} on {{ $post->created_at->format('M d, Y') }}</li>
                        @empty
                            <li class="italic text-base-content/60">No recent posts.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
