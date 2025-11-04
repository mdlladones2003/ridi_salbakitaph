<x-app-layout>
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-9 gap-4 mt-5">
        <aside class="grid gap-4 h-fit col-span-2">
            <div class="card bg-base-100 shadow-md border border-base-300">
                <div class="card-body">
                    <h3 class="card-title">Mark yourself as</h3>

                    @php
                        $latestCheckIn = auth()->user()->checkIns()->latest('created_at')->first();
                        $userStatus = $latestCheckIn->status ?? '';
                    @endphp

                    <form method="POST" action="{{ route('check-in.store') }}" id="check-in-form">
                        @csrf

                        <div class="form-control mb-3 flex flex-col">
                            @foreach (['safe' => 'Safe', 'need_help' => 'Need Help', 'evacuating' => 'Evacuating'] as $value => $label)
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="{{ $value }}"
                                        class="radio
                                            @if($value === 'safe') radio-success
                                            @elseif($value === 'need_help') radio-error
                                            @else radio-warning @endif"
                                        {{ $userStatus === $value ? 'checked' : '' }}
                                    >
                                    <span class="label-text">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>

            <div class="card bg-base-100 shadow-md border border-base-300">
                <div class="card-body">
                    <h3 class="card-title">Active Users</h3>

                    @if(isset($activeUsers) && $activeUsers->count() > 0)
                        <div class="space-y-3">
                            @foreach($activeUsers as $user)
                                <div class="flex items-center gap-3 pb-3 border-b border-base-300 last:border-0">
                                    <div class="avatar placeholder">
                                        <div class="bg-neutral text-neutral-content rounded-full w-10">
                                            <span>{{ strtoupper(substr($user->first_name, 0, 1)) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $user->first_name }} {{ $user->last_name }}</p>
                                        <p class="text-sm opacity-60">
                                            {{ $user->last_active_at?->diffForHumans() ?? 'Recently active' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center opacity-60">No active users found.</p>
                    @endif
                </div>
            </div>

            <div x-data="{ tab: 'reporters' }" class="bg-base-100 shadow-md rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold flex items-center gap-2">Leaderboards</h2>
                </div>

                <div class="flex flex-wrap gap-3 border-b border-base-300 pb-2 mb-4 justify-around">
                    <button
                        @click="tab = 'reporters'"
                        :class="tab === 'reporters' ? 'btn btn-sm btn-primary gap-2' : 'btn btn-sm btn-ghost gap-2'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h2l1-1h4l1 1h2a2 2 0 012 2v16a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="hidden sm:inline">Reporters</span>
                    </button>

                    <button
                        @click="tab = 'verifiers'"
                        :class="tab === 'verifiers' ? 'btn btn-sm btn-primary gap-2' : 'btn btn-sm btn-ghost gap-2'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z"/>
                        </svg>
                        <span class="hidden sm:inline">Verifiers</span>
                    </button>

                    <button
                        @click="tab = 'helpers'"
                        :class="tab === 'helpers' ? 'btn btn-sm btn-primary gap-2' : 'btn btn-sm btn-ghost gap-2'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 14s1-2 3-2 4 2 4 2 2-2 4-2 3 2 3 2m-6-4l3-3a2 2 0 00-3-3l-1 1-1-1a2 2 0 00-3 3l3 3z"/>
                        </svg>
                        <span class="hidden sm:inline">Helpers</span>
                    </button>

                    <button
                        @click="tab = 'reputation'"
                        :class="tab === 'reputation' ? 'btn btn-sm btn-primary gap-2' : 'btn btn-sm btn-ghost gap-2'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.963a1 1 0 00.95.69h4.168c.969 0 1.372 1.24.588 1.81l-3.375 2.455a1 1 0 00-.364 1.118l1.287 3.963c.3.921-.755 1.688-1.54 1.118l-3.375-2.455a1 1 0 00-1.176 0l-3.375 2.455c-.785.57-1.84-.197-1.54-1.118l1.287-3.963a1 1 0 00-.364-1.118L2.49 9.39c-.784-.57-.38-1.81.588-1.81h4.168a1 1 0 00.95-.69l1.286-3.963z"/>
                        </svg>
                        <span class="hidden sm:inline">Reputation</span>
                    </button>
                </div>

                <div class="mt-4 space-y-2">
                    <div x-show="tab === 'reporters'" x-cloak>
                        <h3 class="font-semibold mb-3">Top Reporters</h3>
                        <ul class="space-y-2">
                            @forelse($topReporters as $user)
                                <li class="flex justify-between items-center bg-base-200 p-3 rounded-lg">
                                    <span>{{ $user->full_name ?? ($user->first_name . ' ' . $user->last_name) }}</span>
                                    <span class="badge badge-primary">{{ $user->report_verified_count }}</span>
                                </li>
                            @empty
                                <li class="text-center opacity-60">No verified reports yet.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div x-show="tab === 'verifiers'" x-cloak>
                        <h3 class="font-semibold mb-3 flex items-center gap-2">🕵️‍♂️ Top Verifiers</h3>

                        <ul class="space-y-2">
                            @forelse($topVerifiers as $user)
                                <li class="flex justify-between items-center bg-base-200 p-3 rounded-lg">
                                    <span>{{ $user->full_name ?? ($user->first_name . ' ' . $user->last_name) }}</span>
                                    <span class="badge badge-info">{{ $user->verified_count }}</span>
                                </li>
                            @empty
                                <li class="text-center opacity-60">No verifiers yet.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div x-show="tab === 'helpers'" x-cloak>
                        <h3 class="font-semibold mb-3">Top Helpers</h3>
                        <ul class="space-y-2">
                            @forelse($topHelpers as $user)
                                <li class="flex justify-between items-center bg-base-200 p-3 rounded-lg">
                                    <span>{{ $user->full_name ?? ($user->first_name . ' ' . $user->last_name) }}</span>
                                    <span class="badge badge-warning">{{ $user->help_offers_count }}</span>
                                </li>
                            @empty
                                <li class="text-center opacity-60">No data available</li>
                            @endforelse
                        </ul>
                    </div>

                    <div x-show="tab === 'reputation'" x-cloak>
                        <h3 class="font-semibold mb-3">Top Reputation</h3>
                        <ul class="space-y-2">
                            @forelse($topByReputation as $user)
                                <li class="flex justify-between items-center bg-base-200 p-3 rounded-lg">
                                    <span>{{ $user->full_name ?? ($user->first_name . ' ' . $user->last_name) }}</span>
                                    <span class="badge badge-info">{{ $user->reputation_score }}</span>
                                </li>
                            @empty
                                <li class="text-center opacity-60">No data available</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </aside>

        <section class="card bg-base-100 shadow-md col-span-4">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-4">Community Feed</h2>

                <div x-data="{ open: false }" class="mb-6">
                    <button
                        @click="open = !open"
                        class="btn btn-primary btn-sm mb-3"
                        type="button">
                        Create Post
                    </button>

                    <div x-show="open" x-transition class="card bg-base-200">
                        <div class="card-body">
                            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div class="form-control">
                                    <textarea
                                        name="content"
                                        rows="3"
                                        class="textarea textarea-bordered"
                                        placeholder="What's on your mind?"
                                        required
                                    ></textarea>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                    <select name="category" required class="select select-bordered w-full sm:w-1/3">
                                        <option value="" disabled selected>Choose category</option>
                                        <option value="story">Story</option>
                                        <option value="tips">Tips</option>
                                        <option value="update">Update</option>
                                    </select>

                                    <label for="image-upload" class="btn btn-outline btn-sm w-full sm:w-2/3">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M4 12l4-4m0 0l4 4m-4-4v12"></path>
                                        </svg>
                                        Choose an image (optional)
                                        <input id="image-upload" type="file" name="image" accept="image/*" class="hidden" />
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm">
                                    Post
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                @foreach($posts as $post)
                    <article
                        class="card bg-base-200 shadow mb-6"
                        x-data="{ showReactions: false, showComments: false, editingPost: false, showDeleteModal: false }">
                        <div class="card-body">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="avatar placeholder">
                                    <div class="bg-primary text-primary-content rounded-full w-10">
                                        <span class="text-lg">{{ strtoupper(substr($post->author->first_name, 0, 1) . substr($post->author->last_name, 0, 1)) }}</span>
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <h3 class="font-bold text-lg">
                                        {{ $post->author->first_name }} {{ $post->author->last_name }}
                                    </h3>
                                    <p class="text-sm opacity-60">{{ $post->created_at->diffForHumans() }}</p>
                                </div>

                                @if(auth()->id() === $post->author_id)
                                    <div class="flex gap-2">
                                        <button @click="editingPost = !editingPost" type="button" class="btn btn-ghost btn-sm">
                                            Edit
                                        </button>
                                        <button @click="showDeleteModal = true" type="button" class="btn btn-ghost btn-sm text-error">
                                            Delete
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div x-show="editingPost" x-transition class="mb-4">
                                <form method="POST" action="{{ route('posts.update', $post->post_id) }}" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="content" rows="3" class="textarea textarea-bordered w-full" required>{{ old('content', $post->content) }}</textarea>

                                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                        <select name="category" required class="select select-bordered w-full sm:w-1/3">
                                            <option value="" disabled>Choose category</option>
                                            <option value="story" {{ $post->category === 'story' ? 'selected' : '' }}>Story</option>
                                            <option value="tips" {{ $post->category === 'tips' ? 'selected' : '' }}>Tips</option>
                                            <option value="update" {{ $post->category === 'update' ? 'selected' : '' }}>Update</option>
                                        </select>

                                        <label for="image-upload-{{ $post->id }}" class="btn btn-outline btn-sm w-full sm:w-2/3">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M4 12l4-4m0 0l4 4m-4-4v12" />
                                            </svg>
                                            Choose an image (optional)
                                            <input id="image-upload-{{ $post->id }}" type="file" name="image" accept="image/*" class="hidden" />
                                        </label>
                                    </div>

                                    <div class="flex gap-2">
                                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                                        <button type="button" @click="editingPost = false" class="btn btn-ghost btn-sm">Cancel</button>
                                    </div>
                                </form>
                            </div>

                            <div x-show="!editingPost" x-transition x-cloak>
                                <p class="mb-4 whitespace-pre-wrap">{{ $post->content }}</p>
                                @if($post->image)
                                    <figure class="mb-4">
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="rounded-lg max-h-80 object-cover w-full" />
                                    </figure>
                                @endif
                            </div>

                            @php
                                $reactionEmojis = [
                                    'helpful'       => '👍',
                                    'warning'       => '⚠️',
                                    'folded_hand'   => '🙏',
                                    'sad'           => '😢'
                                ];
                            @endphp

                            <div class="card-actions items-center gap-6 relative">
                                @php
                                    $userReaction = $userReactionByPost[$post->post_id] ?? null;
                                    $currentEmoji = $reactionEmojis[$userReaction ?? 'helpful'] ?? '👍';
                                @endphp

                                <div
                                    class="flex items-center gap-1 cursor-pointer"
                                    @mouseenter="showReactions = true"
                                    @mouseleave="showReactions = false"
                                    role="button">
                                    <span class="text-2xl">{!! $currentEmoji !!}</span>
                                    <span class="badge badge-lg">{{ $post->reactions->count() }}</span>
                                </div>

                                <div
                                    class="flex items-center gap-1 cursor-pointer"
                                    @click="showComments = !showComments"
                                    role="button">
                                    <span class="text-2xl">💬</span>
                                    <span class="badge badge-lg">{{ $post->comments->count() }}</span>
                                </div>

                                <div
                                    x-show="showReactions"
                                    @mouseenter="showReactions = true"
                                    @mouseleave="showReactions = false"
                                    class="absolute card bg-base-100 shadow-md p-2 mt-6 left-0 z-20"
                                    style="min-width: 200px">
                                    <form method="POST" action="{{ route('reactions.store', $post->post_id) }}" class="flex gap-2 justify-center">
                                        @csrf
                                        @foreach ($reactionEmojis as $type => $emoji)
                                            <button
                                                type="submit"
                                                name="emoji_type"
                                                value="{{ $type }}"
                                                class="btn btn-ghost btn-sm text-2xl hover:bg-base-200"
                                                title="{{ ucfirst(str_replace('_', ' ', $type)) }}"
                                                @if ($userReaction === $type) style="background-color: rgba(59, 130, 246, 0.2);" @endif>
                                                {!! $emoji !!}
                                            </button>
                                        @endforeach
                                    </form>
                                </div>
                            </div>

                            <div x-show="showComments" x-cloak class="divider"></div>

                            <div x-show="showComments" x-cloak>
                                <h4 class="font-semibold mb-4">Comments</h4>

                                @foreach($post->comments as $comment)
                                    <div class="chat chat-start mb-4" x-data="{ editingComment: false, showDeleteCommentModal: false }">
                                        <div class="chat-header mb-1">
                                            {{ $comment->user->first_name }} {{ $comment->user->last_name }}
                                            <time class="text-xs opacity-50">{{ $comment->created_at->diffForHumans() }}</time>
                                        </div>

                                        <div x-show="editingComment" x-transition>
                                            <form method="POST" action="{{ route('comments.update', $comment->comment_id) }}" class="space-y-2">
                                                @csrf
                                                @method('PUT')
                                                <textarea name="content" rows="2" class="textarea textarea-bordered w-full" required>{{ old('content', $comment->content) }}</textarea>
                                                <div class="flex gap-2">
                                                    <button type="submit" class="btn btn-success btn-xs">Update</button>
                                                    <button type="button" @click="editingComment = false" class="btn btn-ghost btn-xs">Cancel</button>
                                                </div>
                                            </form>
                                        </div>

                                        <div x-show="!editingComment" x-transition>
                                            <div class="chat-bubble">{{ $comment->content }}</div>
                                            @if(auth()->id() === $comment->user_id)
                                                <div class="chat-footer opacity-50 flex gap-2 mt-1">
                                                    <button @click="editingComment = true" class="link link-hover text-xs">Edit</button>
                                                    <button @click="showDeleteCommentModal = true" class="link link-hover link-error text-xs">Delete</button>
                                                </div>
                                            @endif
                                        </div>

                                        <template x-if="showDeleteCommentModal">
                                            <dialog class="modal modal-open">
                                                <div class="modal-box">
                                                    <h3 class="font-bold text-lg">Delete comment?</h3>
                                                    <p class="py-4">Are you sure you want to delete this comment? This action cannot be undone.</p>
                                                    <div class="modal-action">
                                                        <button @click="showDeleteCommentModal = false" class="btn">Cancel</button>
                                                        <form method="POST" action="{{ route('comments.destroy', $comment->comment_id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-error">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </dialog>
                                        </template>
                                    </div>
                                @endforeach

                                <form method="POST" action="{{ route('comments.store', $post->post_id) }}" class="mt-4">
                                    @csrf
                                    <div class="form-control">
                                        <textarea name="content" rows="2" class="textarea textarea-bordered" placeholder="Write a comment..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Comment</button>
                                </form>
                            </div>

                            <template x-if="showDeleteModal">
                                <dialog class="modal modal-open">
                                    <div class="modal-box">
                                        <h3 class="font-bold text-lg">Delete post?</h3>
                                        <p class="py-4">Are you sure you want to delete this post? This action cannot be undone.</p>
                                        <div class="modal-action">
                                            <button @click="showDeleteModal = false" class="btn">Cancel</button>
                                            <form method="POST" action="{{ route('posts.destroy', $post->post_id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </dialog>
                            </template>
                        </div>
                    </article>
                @endforeach

                {{-- <div class="mt-6">
                    {{ $posts->links() }}
                </div> --}}
            </div>
        </section>

        <aside class="card bg-base-100 shadow-md h-fit col-span-3">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="card-title">Help Offers</h3>
                    <button
                        type="button"
                        class="btn btn-sm btn-outline"
                        onclick="document.getElementById('offerForm').classList.toggle('hidden')">
                        ➕ Make an Offer
                    </button>
                </div>

                <div id="offerForm" class="hidden border border-base-300 rounded-lg p-4 mb-4 bg-base-200">
                    <form method="POST" action="{{ route('help-offers.store') }}">
                        @csrf

                        <div class="form-control mb-3">
                            <label class="label">
                                <span class="label-text font-medium">Offer Type</span>
                            </label>
                            <select name="offer_type" class="select select-bordered w-full" required>
                                <option value="">Select a type</option>
                                <option value="rescue">Rescue</option>
                                <option value="shelter">Shelter</option>
                                <option value="medical">Medical</option>
                                <option value="supplies">Supplies</option>
                            </select>
                            @error('offer_type')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control mb-3">
                            <label class="label">
                                <span class="label-text font-medium">Description</span>
                            </label>
                            <textarea name="description" rows="3" class="textarea textarea-bordered" placeholder="Describe what you can offer..." required></textarea>
                            @error('description')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control mb-3">
                            <label class="label">
                                <span class="label-text font-medium">Capacity (optional)</span>
                            </label>
                            <input type="number" name="capacity" class="input input-bordered" min="1" placeholder="e.g. 5 people">
                            @error('capacity')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-medium">Valid Until (optional)</span>
                            </label>
                            <input type="datetime-local" name="valid_until" class="input input-bordered">
                            @error('valid_until')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-full">Submit Offer</button>
                    </form>
                </div>

                @if(isset($offers) && $offers->count() > 0)
                    <div class="space-y-4">
                        @foreach($offers as $offer)
                            <div class="border border-base-300 rounded-lg p-3 hover:shadow-sm transition">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold capitalize">{{ $offer->offer_type }}</h4>
                                    @if(auth()->id() === $offer->user_id || auth()->user()->role === 'official')
                                        <form method="POST" action="{{ route('help-offers.toggle', $offer) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="badge {{ $offer->is_available ? 'badge-success' : 'badge-ghost' }} cursor-pointer border-none">
                                                {{ $offer->is_available ? 'Available' : 'Unavailable' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge {{ $offer->is_available ? 'badge-success' : 'badge-ghost' }}">
                                            {{ $offer->is_available ? 'Available' : 'Unavailable' }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm mb-2">{{ $offer->description ?? 'No description provided.' }}</p>

                                @if($offer->capacity)
                                    <p class="text-xs opacity-70">🧍 Capacity: {{ $offer->capacity }}</p>
                                @endif

                                @if($offer->valid_until)
                                    <p class="text-xs opacity-70">⏰ Valid until: {{ \Carbon\Carbon::parse($offer->valid_until)->diffForHumans() }}</p>
                                @endif

                                <div class="mt-2 text-xs text-gray-500">
                                    Offered by: <span class="font-medium">
                                        {{ $offer->user->first_name }} {{ $offer->user->last_name }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center opacity-60 py-6">
                        No active help offers available.
                    </p>
                @endif
            </div>
        </aside>

    </div>

    @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const radios = document.querySelectorAll('#check-in-form input[type=radio]');
    let lastChecked = document.querySelector('#check-in-form input[type=radio]:checked');

    radios.forEach(radio => {
        radio.addEventListener('click', function() {
            const form = this.form;

            if (lastChecked === this) {
                // If same radio clicked again → uncheck it and clear status
                this.checked = false;
                lastChecked = null;

                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'status';
                hidden.value = ''; // send empty to clear
                form.appendChild(hidden);
                form.submit();
            } else {
                lastChecked = this;
                form.submit(); // submit new status
            }
        });
    });
});
</script>
@endpush
</x-app-layout>
