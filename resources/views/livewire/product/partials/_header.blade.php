<!-- Header -->
<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Products</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">A list of all the products in your account.</p>
    </div>

    <div class="mt-4 sm:mt-0 sm:ml-16 flex items-center gap-x-4">
        <!-- Search Input -->
        <div class="relative flex-grow max-w-xs">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" aria-hidden="true"
                class="absolute left-3 top-1/2 size-5 -translate-y-1/2 text-on-surface/50 dark:text-on-surface-dark/50">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="search" wire:model.live.debounce.300ms="search"
                class="w-full rounded-radius border border-outline bg-surface-alt py-2 pl-10 pr-4 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                placeholder="Search products...">
        </div>

        <!-- Create Button -->
        <button type="button" wire:click="$dispatch('openCreateModal')"
            class="whitespace-nowrap rounded-radius bg-primary border border-primary px-4 py-2 text-center text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75 dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark">
            Create Product
        </button>
    </div>
</div>