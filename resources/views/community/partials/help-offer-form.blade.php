<div class="card bg-base-100 border border-base-300 shadow-md rounded-md p-4">
    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-base-content">
        <x-lucide-hand-heart class="w-4 h-4" />
        Create a Help Offer
    </h3>

    <form method="POST" action="{{ route('help-offers.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="label label-text font-medium text-base-content">Offer Type</label>
            <select name="offer_type" class="select select-bordered w-full rounded-md" required>
                <option value="">Select a type</option>
                <option value="rescue">Rescue</option>
                <option value="shelter">Shelter</option>
                <option value="medical">Medical</option>
                <option value="supplies">Supplies</option>
            </select>
            @error('offer_type')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="label label-text font-medium text-base-content">Description</label>
            <textarea
                name="description"
                rows="3"
                class="textarea textarea-bordered w-full rounded-md resize-none"
                placeholder="Describe what you can offer..."
                required
            ></textarea>
            @error('description')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="label label-text font-medium text-base-content">Capacity (optional)</label>
                <input
                    type="number"
                    name="capacity"
                    min="1"
                    class="input input-bordered w-full rounded-md"
                    placeholder="e.g. 5 people" />
                @error('capacity')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label label-text font-medium text-base-content">Valid Until (optional)</label>

                <input type="datetime-local" name="valid_until" class="input input-bordered w-full rounded-md" />
                @error('valid_until')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="pt-2 flex justify-end">
            <button type="submit" class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white px-4">
                <x-lucide-send class="w-4 h-4" />
                <span>Submit Offer</span>
            </button>
        </div>
    </form>
</div>
