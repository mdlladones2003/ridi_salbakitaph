@foreach($posts as $post)
    <article
        x-data="{ showReactions: false, showComments: false, editingPost: false, showDeleteModal: false }"
        class="card bg-base-100 border border-base-300 shadow-sm hover:shadow-md transition-all duration-200 mb-6 rounded-md">
        <div class="card-body space-y-4">

            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="avatar placeholder">
                        <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-lg font-bold">
                            {{ strtoupper(substr($post->author?->first_name ?? 'A', 0, 1) . substr($post->author?->last_name ?? 'N', 0, 1)) }}
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-base-content leading-tight">
                            {{ $post->author?->first_name ?? 'Unknown' }} {{ $post->author?->last_name ?? 'User' }}
                        </h3>
                        <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                @if(auth()->id() === $post->author_id)
                <div class="dropdown dropdown-end">
                    <button tabindex="0" class="btn btn-sm btn-ghost rounded-full">
                        <x-lucide-more-horizontal class="w-5 h-5" />
                    </button>
                    <ul tabindex="0" class="dropdown-content z-10 menu p-2 shadow bg-base-100 rounded-box w-32">
                        <li><button @click="editingPost = !editingPost">Edit</button></li>
                        <li><button @click="showDeleteModal = true" class="text-error">Delete</button></li>
                    </ul>
                </div>
                @endif
            </div>

            <div x-show="editingPost" x-transition class="border-t pt-4 space-y-3">
                <form method="POST" action="{{ route('posts.update', $post->post_id) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <textarea name="content" rows="3" class="textarea textarea-bordered w-full rounded-md" required>{{ old('content', $post->content) }}</textarea>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <select name="category" required class="select select-bordered w-full">
                            <option disabled>Choose category</option>
                            <option value="story" {{ $post->category === 'story' ? 'selected' : '' }}>Story</option>
                            <option value="tips" {{ $post->category === 'tips' ? 'selected' : '' }}>Tips</option>
                            <option value="update" {{ $post->category === 'update' ? 'selected' : '' }}>Update</option>
                        </select>

                        <label for="image-upload-{{ $post->post_id }}" class="sm:col-span-2 cursor-pointer flex items-center justify-center gap-2 border border-dashed border-base-300 rounded-lg p-3 hover:bg-base-200 transition">
                            <x-lucide-image-plus class="w-5 h-5 text-primary" />
                            <span class="text-sm font-medium text-gray-600">Choose an image (optional)</span>
                            <input id="image-upload-{{ $post->post_id }}" type="file" name="image" accept="image/*" class="hidden" />
                        </label>
                    </div>

                    <div class="flex gap-2 justify-end">
                        <button
                            type="button"
                            @click="editingPost = false"
                            class="btn btn-ghost btn-sm flex items-center gap-1 px-4">
                            <span>Cancel</span>
                        </button>

                        <button
                            type="submit"
                            class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white px-4">
                            <span>Update</span>
                        </button>
                    </div>
                </form>
            </div>

            <div x-show="!editingPost" x-transition>
                <p class="text-base text-gray-800 leading-relaxed whitespace-pre-wrap mb-3">{{ $post->content }}</p>

                @if($post->image)
                <figure class="rounded-xl overflow-hidden border border-base-300">
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="w-full max-h-[400px] object-cover">
                </figure>
                @endif
            </div>

            @php
                $reactionEmojis = ['helpful' => '👍', 'warning' => '⚠️', 'folded_hand' => '🙏', 'sad' => '😢'];
                $userReaction = $userReactionByPost[$post->post_id] ?? null;
                $currentEmoji = $reactionEmojis[$userReaction ?? 'helpful'] ?? '👍';
            @endphp

            <div class="flex items-center justify-between border-t pt-3">
                <div class="flex items-center gap-6 relative">
                    <div
                        class="flex items-center gap-1 cursor-pointer select-none"
                        @mouseenter="showReactions = true"
                        @mouseleave="showReactions = false"
                        role="button">
                        <span class="text-2xl">{!! $currentEmoji !!}</span>
                        <span class="text-sm font-medium text-gray-600">{{ $post->reactions->count() }}</span>
                    </div>

                    <div
                        class="flex items-center gap-1 cursor-pointer select-none"
                        @click="showComments = !showComments">
                        💬 <span class="text-sm font-medium text-gray-600">{{ $post->comments->count() }}</span>
                    </div>

                    <div
                        x-show="showReactions"
                        @mouseenter="showReactions = true"
                        @mouseleave="showReactions = false"
                        x-transition
                        class="absolute bg-base-100 border border-base-300 shadow-md rounded-lg p-2 flex gap-2 mt-8 left-0 z-20">
                        <form method="POST" action="{{ route('reactions.store', $post->post_id) }}" class="flex gap-2">
                            @csrf
                            @foreach ($reactionEmojis as $type => $emoji)
                                <button
                                    type="submit"
                                    name="emoji_type"
                                    value="{{ $type }}"
                                    class="btn btn-ghost btn-sm text-2xl hover:bg-base-200 rounded-full"
                                    title="{{ ucfirst(str_replace('_', ' ', $type)) }}"
                                    @if ($userReaction === $type) style="background-color: rgba(59,130,246,0.2);" @endif>
                                    {!! $emoji !!}
                                </button>
                            @endforeach
                        </form>
                    </div>
                </div>
            </div>

            <div x-show="showComments" x-transition x-cloak class="mt-4 border-t pt-4 space-y-4">
                <h4 class="font-semibold text-gray-700">Comments</h4>

                @foreach($post->comments as $comment)
                    <div x-data="{ editingComment: false, showDeleteCommentModal: false }" class="bg-base-200 p-3 rounded-xl">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">{{ $comment->user->first_name }} {{ $comment->user->last_name }}</p>
                                <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>

                            @if(auth()->id() === $comment->user_id)
                            <div class="flex gap-2 text-xs text-gray-500">
                                <button @click="editingComment = true">Edit</button>
                                <button @click="showDeleteCommentModal = true" class="text-error">Delete</button>
                            </div>
                            @endif
                        </div>

                        <div x-show="!editingComment" x-transition class="mt-2 text-sm text-gray-800">{{ $comment->content }}</div>
                        <template x-if="showDeleteCommentModal">
                            <dialog class="modal modal-open">
                                <div class="modal-box border border-base-300 shadow-md rounded-md bg-base-100">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="bg-red-100 text-red-600 p-2 rounded-full">
                                            <x-lucide-trash class="w-5 h-5" />
                                        </div>
                                        <h3 class="font-semibold text-lg text-base-content">Delete Comment</h3>
                                    </div>

                                    <p class="text-sm text-center text-base-content/80 leading-relaxed mb-6">
                                        Are you sure you want to delete this comment? <br>
                                        <span class="text-red-600 font-medium">This action cannot be undone.</span>
                                    </p>

                                    <div class="modal-action flex justify-end gap-3">
                                        <button
                                            @click="showDeleteCommentModal = false"
                                            class="btn btn-ghost btn-sm gap-1">
                                            Cancel
                                        </button>

                                        <form method="POST" action="{{ route('comments.destroy', $comment->comment_id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="btn bg-red-600 text-white btn-sm px-4">
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>
                        </template>

                        <div x-show="editingComment" x-transition class="mt-3 space-y-2">
                            <form method="POST" action="{{ route('comments.update', $comment->comment_id) }}" class="space-y-4">
                                @csrf
                                @method('PUT')
                                <textarea name="content" rows="2" class="textarea textarea-bordered w-full" required>{{ old('content', $comment->content) }}</textarea>

                                <div class="flex gap-2 justify-end">
                                    <button
                                        type="button"
                                        @click="editingComment = false"
                                        class="btn btn-ghost btn-sm flex items-center gap-1 px-4">
                                        <span>Cancel</span>
                                    </button>

                                    <button
                                        type="submit"
                                        class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white px-4">
                                        <span>Update</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach

                <form method="POST" action="{{ route('comments.store', $post->post_id) }}" class="space-y-2">
                    @csrf
                    <textarea name="content" rows="2" class="textarea textarea-bordered w-full" placeholder="Write a comment..." required></textarea>
                    <div class="text-right">
                        <button type="submit" class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white px-4">Comment</button>
                    </div>
                </form>
            </div>

            <template x-if="showDeleteModal">
                <dialog class="modal modal-open">
                    <div class="modal-box border border-base-300 shadow-md rounded-md bg-base-100">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-red-100 text-red-600 p-2 rounded-full">
                                <x-lucide-trash class="w-5 h-5" />
                            </div>
                            <h3 class="font-semibold text-lg text-base-content">Delete Post</h3>
                        </div>

                        <p class="text-sm text-center text-base-content/80 leading-relaxed mb-6">
                            Are you sure you want to delete this post? <br>
                            <span class="text-red-600 font-medium">This action cannot be undone.</span>
                        </p>

                        <div class="modal-action flex justify-end gap-3">
                            <button
                                @click="showDeleteModal = false"
                                class="btn btn-ghost btn-sm gap-1">
                                Cancel
                            </button>

                            <form method="POST" action="{{ route('posts.destroy', $post->post_id) }}">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="btn bg-red-600 text-white btn-sm px-4">
                                    <span>Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </dialog>
            </template>
        </div>
    </article>
@endforeach
