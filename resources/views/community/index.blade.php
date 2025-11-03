<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-9 gap-4 mt-5">
        <!-- Check-in Sidebar -->
        <aside class="card bg-base-100 shadow-md sticky top-6 h-fit col-span-2">
            <div class="card-body">
                <h3 class="card-title">Mark yourself as</h3>

                @php
                    $latestCheckIn = auth()->user()->checkIns()->latest('created_at')->first();
                    $userStatus = old('status') ?? ($latestCheckIn->status ?? '');
                    $userNotes = old('notes') ?? ($latestCheckIn->notes ?? '');
                @endphp

                <form method="POST" action="{{ route('check-in.store') }}" id="check-in-form">
                    @csrf

                    <div class="form-control mb-3 flex flex-col">
                        <label class="label cursor-pointer justify-start gap-2">
                            <input type="radio" name="status" value="safe" class="radio radio-success"
                                {{ $userStatus === 'safe' ? 'checked' : '' }}>
                            <span class="label-text">Safe</span>
                        </label>

                        <label class="label cursor-pointer justify-start gap-2">
                            <input type="radio" name="status" value="need_help" class="radio radio-error"
                                {{ $userStatus === 'need_help' ? 'checked' : '' }}>
                            <span class="label-text">Need Help</span>
                        </label>

                        <label class="label cursor-pointer justify-start gap-2">
                            <input type="radio" name="status" value="evacuating" class="radio radio-warning"
                                {{ $userStatus === 'evacuating' ? 'checked' : '' }}>
                            <span class="label-text">Evacuating</span>
                        </label>

                        @error('status')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control mb-3">
                        <label class="label">
                            <span class="label-text font-medium">Additional Notes (optional)</span>
                        </label>
                        <textarea id="notes" name="notes" rows="3" class="textarea textarea-bordered">{{ $userNotes }}</textarea>
                        @error('notes')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-full">
                        Submit
                    </button>
                </form>
            </div>
        </aside>

        <!-- Community Feed -->
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

                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            </div>
        </section>

        <!-- Active Users Sidebar -->
        <aside class="card bg-base-100 shadow-md sticky top-6 h-fit col-span-3">
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
                                    <p class="text-sm opacity-60">{{ $user->last_active_at->diffForHumans() ?? 'Recently active' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center opacity-60">No active users found.</p>
                @endif
            </div>
        </aside>
    </div>
</x-app-layout>
