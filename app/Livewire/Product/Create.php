<?php

namespace App\Livewire\Product;

use App\Livewire\Forms\ProductForm;
use Livewire\Component;

class Create extends Component
{
    public ProductForm $form;



    public function save()
    {
        $this->form->store();

        session()->flash('success', 'Product created successfully.');
        $this->redirectRoute('products.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.product.create');
    }
}
