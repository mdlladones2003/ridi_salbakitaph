<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">
            Search results for: <span class="text-blue-600">"{{ $query }}"</span>
        </h1>

        <div class="space-y-12">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l5 5v9a2 2 0 01-2 2z" />
                    </svg>
                    Posts
                </h2>

                @if($posts->isEmpty())
                    <p class="text-gray-500 italic">No posts found matching your search.</p>
                @else
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($posts as $post)
                            <div class="p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $post->author->first_name }} {{ $post->author->last_name }}</h3>
                                        <p class="text-gray-600 text-sm">{{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="text-sm text-gray-400">{{ $post->reactions_count }} 👍</span>
                                </div>
                                <p class="mt-2 text-gray-700">{{ Str::limit($post->content, 120) }}</p>
                                <a href="{{ route('community.index') }}#post-{{ $post->id }}" class="text-blue-600 text-sm mt-2 inline-block hover:underline">
                                    View Post →
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5V4H2v16h5m10 0v-6m0 6h-4m0-6h4m-4 6v-6" />
                    </svg>
                    Users
                </h2>

                @if($users->isEmpty())
                    <p class="text-gray-500 italic">No users found.</p>
                @else
                    <div class="space-y-6">
                        @foreach($users as $user)
                            <div class="p-6 bg-white rounded-xl shadow hover:shadow-md transition">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 text-blue-600 font-bold text-lg">
                                        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800 text-lg">{{ $user->first_name }} {{ $user->last_name }}</h3>
                                        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                                        <p class="text-gray-400 text-xs mt-1">{{ ucfirst($user->role ?? 'User') }}</p>
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-3 gap-4">
                                    <div>
                                        <h4 class="font-semibold text-gray-700 mb-2">Recent Posts</h4>
                                        @if($user->posts->isEmpty())
                                            <p class="text-gray-500 text-sm italic">No posts yet.</p>
                                        @else
                                            <ul class="space-y-1">
                                                @foreach($user->posts as $post)
                                                    <li class="text-sm text-gray-700">
                                                        <span class="block truncate">{{ Str::limit($post->content, 80) }}</span>
                                                        <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>

                                    <div>
                                        <h4 class="font-semibold text-gray-700 mb-2">Recent Reports</h4>
                                        @if($user->reports->isEmpty())
                                            <p class="text-gray-500 text-sm italic">No reports filed.</p>
                                        @else
                                            <ul class="space-y-1">
                                                @foreach($user->reports as $report)
                                                    <li class="text-sm text-gray-700">
                                                        <span class="block truncate">{{ Str::limit($report->description ?? 'No description', 80) }}</span>
                                                        <span class="text-xs text-gray-400">{{ $report->created_at->diffForHumans() }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>

                                    <div>
                                        <h4 class="font-semibold text-gray-700 mb-2">Recent Help Offers</h4>
                                        @if($user->helpOffers->isEmpty())
                                            <p class="text-gray-500 text-sm italic">No help offers yet.</p>
                                        @else
                                            <ul class="space-y-1">
                                                @foreach($user->helpOffers as $offer)
                                                    <li class="text-sm text-gray-700">
                                                        <span class="block truncate">{{ Str::limit($offer->title ?? 'Untitled Offer', 80) }}</span>
                                                        <span class="text-xs text-gray-400">{{ $offer->created_at->diffForHumans() }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0zM12 9v4m0 4h.01" />
                    </svg>
                    Alerts
                </h2>

                @if($alerts->isEmpty())
                    <p class="text-gray-500 italic">No active alerts found.</p>
                @else
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($alerts as $alert)
                            <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-md shadow-sm">
                                <h3 class="font-semibold text-yellow-800">{{ $alert->title ?? 'Untitled Alert' }}</h3>
                                <p class="text-gray-700 text-sm mt-1">{{ Str::limit($alert->message, 150) }}</p>
                                <p class="text-xs text-gray-500 mt-2">{{ $alert->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
