<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{

    use \Livewire\WithPagination;

    public $search = '';

    public $perPage = 10;

    public $sortField = 'id';
    public $sortDirection = 'desc';

    #[On('productCreated')]
    public function refresh()
    {
        //
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function delete(Product $product)
    {
        $product->delete();

        session()->flash('success', 'Product deleted successfully.');
        $this->redirectRoute('products.index', navigate: true);
    }



    public function render()
    {
        return view(
            'livewire.product.index',
            [

                'products' => Product::search($this->search)
                    ->orderBy($this->sortField, $this->sortDirection)
                    ->paginate($this->perPage),
            ]
        );
    }
}
