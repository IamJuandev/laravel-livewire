<div>
    {{-- Botón de Edición --}}
    <a href="{{ route('products.edit', $product) }}" class="text-blue-500 hover:text-blue-700">
        Editar
    </a>

    {{-- Botón de Eliminación (usando Livewire/Alpine.js para confirmación) --}}
    <button wire:click="deleteProduct({{ $product->id }})"
        onclick="confirm('¿Estás seguro de que quieres eliminar {{ $product->name }}?') || event.stopImmediatePropagation()"
        class="text-red-500 hover:text-red-700 ml-2">
        Eliminar
    </button>
</div>