<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use Livewire\Component;

class Edit extends Component
{
    public Product $product;

    public $name;
    public $description;
    public $price;
    public $stock_quantity;
    public $threshold_quantity;

    protected $rules = [
        'name' => 'required|string|max:100|min:2',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'threshold_quantity' => 'required|integer|min:1',
    ];

    public function mount($product)
    {
        $this->product = $product;
        $this->name = $this->product->name;
        $this->description = $this->product->description;
        $this->price = $this->product->price;
        $this->stock_quantity = $this->product->stock_quantity;
        $this->threshold_quantity = $this->product->threshold_quantity;
    }

    public function submit()
    {
        $validatedData = $this->validate();

        $this->product->update([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'threshold_quantity' => $this->threshold_quantity,
        ]);

        session()->flash('message', 'Product successfully updated.');
    }

    public function render()
    {
        return view('livewire.admin.product.edit');
    }
}
