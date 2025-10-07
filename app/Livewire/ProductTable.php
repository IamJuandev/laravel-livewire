<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Product;

class ProductTable extends DataTableComponent
{
    protected $model = Product::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchEnabled();
        $this->setColumnSelectEnabled();
        $this->setBulkActions(['deleteSelected' => 'Eliminar Seleccionados',]);
        $this->setTableRowUrl(fn($row) => route('products.edit', $row));
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->sortable()
                ->searchable(),
            Column::make("Description", "description")
                ->sortable(),
            Column::make("Price", "price")
                ->sortable(),
            Column::make("Stock", "quantity")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
    public function deleteSelected()
    {
        $products = $this->getSelected(); // Obtiene los IDs de las filas seleccionadas [18]

        // Lógica para eliminar productos (debes adaptarla a tu modelo)
        Product::whereIn('id', $products)->delete();

        $this->clearSelected(); // Limpia la selección después de la acción [19]
    }
}
