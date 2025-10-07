<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">{{
        request()->routeIs('products.create') ? 'Create Product': 'Update Product' }}</h2>

    <div class="bg-white p-6 rounded-lg shadow-md dark:bg-surface-dark">
        <form wire:submit.prevent="save">
            <div class="flex flex-col gap-4 mb-6">
                <x-form.imput label="Nombre del Producto" name="form.name" placeholder="Nombre del Producto"
                    wire:model="form.name" />
                <x-form.imput label="Precio" name="form.price" type="number" placeholder="Precio"
                    wire:model="form.price" />
                <x-form.imput label="Cantidad" name="form.quantity" type="number" placeholder="Stock"
                    wire:model="form.quantity" />
                <x-form.imput label="descripcion" name="form.description" placeholder="Cantidad"
                    wire:model="form.description" />

                <button type="submit"
                    class="whitespace-nowrap rounded-radius bg-success border border-success px-4 py-2 text-sm font-medium tracking-wide text-onSuccess transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-success active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-success dark:border-success dark:text-onSuccess dark:focus-visible:outline-success">{{
                    request()->routeIs('products.create') ? 'Create Product': 'Update Product' }}</button>
            </div>


        </form>
    </div>
</div>