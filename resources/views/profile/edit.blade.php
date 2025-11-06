<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-md rounded-md p-8 space-y-8">

                {{-- Top Section: Avatar + Basic Info --}}
                <div class="flex flex-col md:flex-row items-start md:items-center md:justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="bg-blue-600 text-white rounded-full w-20 h-20 flex items-center justify-center text-3xl font-bold">
                            {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-2xl font-semibold">{{ $user->first_name }} {{ $user->last_name }}</h3>
                            <p class="text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-1">First Name</h4>
                        <p class="text-lg text-base-content">{{ $user->first_name }}</p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-1">Last Name</h4>
                        <p class="text-lg text-base-content">{{ $user->last_name }}</p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-1">Email</h4>
                        <p class="text-lg text-base-content">{{ $user->email }}</p>
                    </div>

                    @if ($user->phone ?? false)
                        <div>
                            <h4 class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-1">Phone</h4>
                            <p class="text-lg text-base-content">{{ $user->phone }}</p>
                        </div>
                    @endif

                    {{-- @if ($user->address ?? false)
                        <div class="md:col-span-2">
                            <h4 class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-1">Address</h4>
                            <p class="text-lg text-base-content">{{ $user->address }}</p>
                        </div>
                    @endif --}}
                </div>

                @if ($user->badges->isNotEmpty())
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Badges</h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($user->badges as $badge)
                                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $badge->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($user->posts->isNotEmpty())
                    <div>
                        <h3 class="text-lg font-semibold mb-3 mt-8">Recent Posts</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($user->posts->take(6) as $post)
                                <div class="border rounded-xl p-4 hover:shadow transition">
                                    <h4 class="font-semibold text-gray-800">{{ $post->title ?? 'Untitled Post' }}</h4>
                                    <p class="text-gray-500 text-sm mt-1">{{ Str::limit($post->content, 100) }}</p>
                                    <p class="text-xs text-gray-400 mt-2">
                                        {{ $post->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($user->reports->isNotEmpty())
                    <div>
                        <h3 class="text-lg font-semibold mb-3 mt-8">Recent Reports</h3>
                        <ul class="space-y-3">
                            @foreach ($user->reports->take(5) as $report)
                                <li class="border rounded-lg p-4">
                                    <p class="text-base-content font-medium">
                                        {{ $report->title ?? 'Report #'.$report->id }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ Str::limit($report->description ?? '', 120) }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
