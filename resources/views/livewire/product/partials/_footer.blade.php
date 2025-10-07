<!-- Footer -->
<div class="mt-4 flex items-center justify-between">
    <div class="relative flex items-center gap-x-2 text-sm">
        <label for="perPage" class="font-medium">Per Page</label>
        <select wire:model.live='perPage' id="perPage" name="perPage"
            class="w-20 appearance-none rounded-radius border border-outline bg-surface-alt px-2 py-1.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
    <div>
        {{ $products->links() }}
    </div>
</div>
