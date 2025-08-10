<?php

namespace App\Livewire\Admin\Product;

use App\Enums\AuditAction;
use App\Models\Product;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}
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

        $product = Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'threshold_quantity' => $this->threshold_quantity,
        ]);

        // Log the product creation event
        $this->auditLogService->logProductManagement(
            AuditAction::PRODUCT_CREATED,
            $product,
            Auth::user(),
            [
                'product_name' => $this->name,
                'price' => $this->price,
                'stock_quantity' => $this->stock_quantity,
                'threshold_quantity' => $this->threshold_quantity,
            ]
        );

        session()->flash('message', 'Product successfully created.');
        $this->reset();
        $this->redirectRoute('admin.products.index');
    }
    public function render()
    {
        return view('livewire.admin.product.create');
    }
}
