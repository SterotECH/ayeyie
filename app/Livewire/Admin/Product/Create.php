<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $description = '';
    public $price = '';
    public $stock_quantity = '';
    public $threshold_quantity = 50;

    protected $rules = [
        'name' => 'required|string|max:100|min:2',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'threshold_quantity' => 'required|integer|min:1',
    ];

    public function submit()
    {
        $validatedData = $this->validate();

        Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'threshold_quantity' => $this->threshold_quantity,
        ]);

        session()->flash('message', 'Product successfully created.');
        $this->reset();
        $this->redirectRoute('admin.products.index');
    }
    public function render()
    {
        return view('livewire.admin.product.create');
    }
}
