<div x-data="{ modalIsOpen: @entangle('showModal') }">
    <!-- Modal Overlay -->
    <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
        x-on:keydown.esc.window="$wire.closeModal()" x-on:click.self="$wire.closeModal()"
        class="fixed inset-0 z-50 flex items-end justify-center bg-black/50 backdrop-blur-sm p-4 pb-8 sm:items-center lg:p-8"
        role="dialog" aria-modal="true" aria-labelledby="createModalTitle">

        <!-- Modal Dialog -->
        <div x-show="modalIsOpen"
            x-transition:enter="transition ease-out duration-300 delay-100 motion-reduce:transition-opacity"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="w-full max-w-lg flex flex-col gap-0 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-800">

            <!-- Dialog Header -->
            <div
                class="flex items-center justify-between border-b border-gray-200 bg-gray-50/80 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/50">
                <h3 id="createModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">
                    Crear Producto
                </h3>
                <button type="button" x-on:click="$wire.closeModal()"
                    class="rounded-lg p-1.5 text-gray-500 transition-colors hover:bg-gray-200 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:hover:bg-gray-700 dark:hover:text-gray-300"
                    aria-label="Cerrar modal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"
                        aria-hidden="true">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Dialog Body -->
            <div class="overflow-y-auto max-h-[calc(100vh-16rem)] px-6 py-6">
                <form wire:submit="save" class="space-y-5">
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="name" id="name" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm transition-colors focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ingresa el nombre del producto">
                        @error('name')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description Field -->
                    <div>
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Descripción
                        </label>
                        <textarea wire:model="description" id="description" rows="3"
                            class="block w-full rounded-lg border-gray-300 shadow-sm transition-colors focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 resize-none"
                            placeholder="Describe el producto (opcional)"></textarea>
                        @error('description')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price and Quantity Grid -->
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Price Field -->
                        <div>
                            <label for="price"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Precio <span class="text-red-500">*</span>
                            </label>
                            <div class="relative rounded-lg shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" wire:model="price" id="price" required min="0"
                                    class="block w-full rounded-lg border-gray-300 pl-7 pr-3 shadow-sm transition-colors focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="0.00">
                            </div>
                            @error('price')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity Field -->
                        <div>
                            <label for="quantity"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Cantidad <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="quantity" id="quantity" required min="0"
                                class="block w-full rounded-lg border-gray-300 shadow-sm transition-colors focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="0">
                            @error('quantity')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>

            <!-- Dialog Footer -->
            <div
                class="flex flex-col-reverse gap-3 border-t border-gray-200 bg-gray-50/80 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/50 sm:flex-row sm:justify-end">
                <button type="button" x-on:click="$wire.closeModal()"
                    class="inline-flex justify-center items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    Cancelar
                </button>
                <button type="button" wire:click="save" wire:loading.attr="disabled"
                    wire:loading.class="opacity-75 cursor-not-allowed"
                    class="inline-flex justify-center items-center rounded-lg border border-transparent bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-indigo-500 dark:hover:bg-indigo-600">
                    <span wire:loading.remove wire:target="save">Guardar Producto</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Guardando...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>