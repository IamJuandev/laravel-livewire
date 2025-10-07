<!-- Table Container -->
<div class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark">
    <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
        <thead
            class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
            <tr>
                <th scope="col" class="p-4 cursor-pointer" wire:click="sortBy('id')">
                    <x-ui.short-button label="ID" field="id" :sortBy="$sortField" :sortDirection="$sortDirection" />
                </th>
                <th scope="col" class="p-4 cursor-pointer" wire:click="sortBy('name')">
                    <x-ui.short-button label="Name" field="name" :sortBy="$sortField" :sortDirection="$sortDirection" />
                </th>
                <th scope="col" class="p-4 cursor-pointer" wire:click="sortBy('price')">
                    <x-ui.short-button label="Price" field="price" :sortBy="$sortField" :sortDirection="$sortDirection" />
                </th>
                <th scope="col" class="p-4 cursor-pointer" wire:click="sortBy('quantity')">
                    <x-ui.short-button label="Stock" field="quantity" :sortBy="$sortField" :sortDirection="$sortDirection" />
                </th>
                <th scope="col" class="p-4 cursor-pointer" wire:click="sortBy('created_at')">
                    <x-ui.short-button label="Created at" field="created_at" :sortBy="$sortField" :sortDirection="$sortDirection" />
                </th>
                <th scope="col" class="p-4">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline dark:divide-outline-dark">
            @forelse ($products as $product)
            <tr class="hover:bg-surface-alt/50 dark:hover:bg-surface-dark-alt/50">
                <td class="p-4">{{ $product->id }}</td>
                <td class="p-4 font-medium text-on-surface-strong dark:text-on-surface-dark-strong">{{ $product->name }}</td>
                <td class="p-4">{{ $product->price }}</td>
                <td class="p-4">{{ $product->quantity }}</td>
                <td class="p-4 text-xs text-on-surface/80 dark:text-on-surface-dark/80">{{ $product->created_at->format('Y-m-d') }}</td>
                <td class="p-4 flex items-center gap-x-4">
                    <a href="{{ route('products.edit', $product) }}" wire:navigate
                        class="whitespace-nowrap rounded-radius bg-transparent p-0.5 font-semibold text-primary outline-primary hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 dark:text-primary-dark dark:outline-primary-dark">Edit</a>
                    <button type="button" wire:click="delete({{ $product->id }})" wire:confirm="Are you sure you want to delete this product?"
                        class="whitespace-nowrap rounded-radius font-semibold text-danger hover:opacity-75">Delete</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-12 text-center">
                    <div class="text-lg font-semibold">No products found</div>
                    <div class="text-sm text-gray-500">Try adjusting your search or filter.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
