<div x-data="{ open: false }" class="mb-8">
    <button
        @click="open = !open"
        class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white px-4"
        type="button">
        <x-lucide-edit-3 class="w-4 h-4" />
        <span>Create Post</span>
    </button>

    <div
        x-show="open"
        x-transition
        x-cloak
        class="mt-4 card bg-base-100 border border-base-300 shadow-md rounded-md overflow-hidden">
        <div class="card-body space-y-4">
            <h3 class="font-semibold text-lg flex items-center gap-2 text-base-content">
                <x-lucide-message-square class="w-4 h-4" />
                New Post
            </h3>

            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="form-control">
                    <textarea
                        name="content"
                        rows="4"
                        class="textarea textarea-bordered w-full rounded-md focus:ring focus:ring-primary/30 resize-none"
                        placeholder="Share your thoughts, stories, or updates..."
                        required
                    ></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <select
                        name="category"
                        required
                        class="select select-bordered w-full rounded-md">
                        <option value="" disabled selected>Category</option>
                        <option value="story">Story</option>
                        <option value="tips">Tips</option>
                        <option value="update">Update</option>
                    </select>

                    <label
                        for="image-upload"
                        class="sm:col-span-2 text-base-content cursor-pointer flex items-center justify-center gap-2 border border-dashed border-base-300 rounded-md hover:bg-base-200 transition">
                        <x-lucide-image-plus class="w-4 h-4" />
                        <span class="text-sm font-medium">Choose an image (optional)</span>
                        <input id="image-upload" type="file" name="image" accept="image/*" class="hidden" />
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white px-4">
                        <x-lucide-send class="w-4 h-4 mr-1" />
                        Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
