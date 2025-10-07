<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;

class CreateProductModal extends Component
{
    public bool $showModal = false;
    public string $name = '';
    public string $description = '';
    public float $price = 0;
    public int $quantity = 0;

    #[On('openCreateModal')]
    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
        ]);

        $this->closeModal();
        $this->dispatch('productCreated');
        session()->flash('success', 'Product created successfully.');
    }

    public function render()
    {
        return view('livewire.product.create-product-modal');
    }
}
