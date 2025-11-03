<x-app-layout>
    <div class="p-6 space-y-6 max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-primary">{{ $user->first_name }} {{ $user->last_name }} <span class="text-base-content/70 text-lg font-normal">({{ ucfirst($user->role) }})</span></h1>

        <div class="flex gap-8">
            <div class="w-1/3 bg-base-200 rounded-lg p-6 shadow-md space-y-6">
                <div>
                    <h2 class="text-xl font-semibold text-secondary mb-3 border-b border-secondary/30 pb-2">Basic Info</h2>
                    <p class="text-base-content"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="text-base-content"><strong>Phone:</strong> {{ $user->phone_number ?? 'N/A' }}</p>
                    <p class="text-base-content"><strong>Status:</strong>
                        @if ($user->is_active)
                            <span class="badge badge-success ml-2">Active</span>
                        @else
                            <span class="badge badge-error ml-2">Inactive</span>
                        @endif
                    </p>
                    <p class="text-base-content"><strong>Verified:</strong>
                        @if ($user->is_verified)
                            <span class="badge badge-success ml-2">Yes</span>
                        @else
                            <span class="badge badge-warning ml-2">No</span>
                        @endif
                    </p>
                    <p class="text-base-content"><strong>Reputation Score:</strong> {{ $user->reputation_score ?? 'N/A' }}</p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-secondary mb-3 border-b border-secondary/30 pb-2">Statistics</h2>
                    <ul class="list-disc list-inside space-y-1 text-base-content">
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

            <div class="flex-1 bg-base-200 rounded-lg p-6 shadow-md space-y-6">
                <h2 class="text-2xl font-semibold text-secondary border-b border-secondary/30 pb-3">Recent Activity</h2>

                <div>
                    <h3 class="font-semibold text-primary mb-2">Reports</h3>
                    <ul class="list-disc list-inside space-y-1 text-base-content">
                        @forelse ($recentActivity['reports'] as $report)
                            <li>{{ $report->title ?? 'No title' }} - <span class="capitalize font-medium">{{ $report->status }}</span></li>
                        @empty
                            <li class="italic text-base-content/60">No recent reports.</li>
                        @endforelse
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-primary mb-2">Check-ins</h3>
                    <ul class="list-disc list-inside space-y-1 text-base-content">
                        @forelse ($recentActivity['check_ins'] as $checkIn)
                            <li>{{ $checkIn->location ?? 'No location' }} on {{ $checkIn->created_at->format('M d, Y') }}</li>
                        @empty
                            <li class="italic text-base-content/60">No recent check-ins.</li>
                        @endforelse
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-primary mb-2">Posts</h3>
                    <ul class="list-disc list-inside space-y-1 text-base-content">
                        @forelse ($recentActivity['posts'] as $post)
                            <li>{{ $post->title ?? 'No title' }} on {{ $post->created_at->format('M d, Y') }}</li>
                        @empty
                            <li class="italic text-base-content/60">No recent posts.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="pt-6">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary mr-4">Edit User</a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Back to List</a>
        </div>
    </div>
</x-app-layout>
