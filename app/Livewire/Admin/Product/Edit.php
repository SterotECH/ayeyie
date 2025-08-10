<?php

namespace App\Livewire\Admin\Product;

use App\Enums\AuditAction;
use App\Models\Product;
use App\Services\AuditLogService;
use App\Services\StockAlertService;
use App\Services\SuspiciousActivityService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Edit extends Component
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
        private readonly SuspiciousActivityService $suspiciousActivityService,
        private readonly StockAlertService $stockAlertService
    ) {}
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
        
        $originalData = [
            'name' => $this->product->name,
            'description' => $this->product->description,
            'price' => $this->product->price,
            'stock_quantity' => $this->product->stock_quantity,
            'threshold_quantity' => $this->product->threshold_quantity,
        ];
        
        $newData = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'threshold_quantity' => $this->threshold_quantity,
        ];

        $this->product->update($newData);

        $user = Auth::user();
        
        // Log the product update event
        $this->auditLogService->logProductManagement(
            AuditAction::PRODUCT_UPDATED,
            $this->product,
            $user,
            [
                'updated_fields' => array_diff_assoc($newData, $originalData),
                'original_values' => $originalData,
                'new_values' => $newData,
            ]
        );
        
        // Log specific events for important changes
        if ($originalData['price'] !== $newData['price']) {
            $this->auditLogService->logProductManagement(
                AuditAction::PRODUCT_PRICE_CHANGED,
                $this->product,
                $user,
                [
                    'old_price' => $originalData['price'],
                    'new_price' => $newData['price'],
                    'price_change' => $newData['price'] - $originalData['price'],
                ]
            );
        }
        
        if ($originalData['stock_quantity'] !== $newData['stock_quantity']) {
            $this->auditLogService->logInventory(
                AuditAction::PRODUCT_STOCK_UPDATED,
                $this->product,
                $user,
                [
                    'old_quantity' => $originalData['stock_quantity'],
                    'new_quantity' => $newData['stock_quantity'],
                    'quantity_change' => $newData['stock_quantity'] - $originalData['stock_quantity'],
                ]
            );
            
            // Check for suspicious inventory manipulation
            if ($user) {
                $this->suspiciousActivityService->detectInventoryManipulation(
                    $this->product,
                    $user,
                    $originalData['stock_quantity'],
                    $newData['stock_quantity']
                );
            }
            
            // Product observer will automatically handle stock alert creation/resolution
        }
        
        if ($originalData['threshold_quantity'] !== $newData['threshold_quantity']) {
            // Update threshold and check for alerts
            $this->stockAlertService->updateProductThreshold(
                $this->product,
                $newData['threshold_quantity'],
                $user
            );
        }

        session()->flash('message', 'Product successfully updated.');
    }

    public function render()
    {
        return view('livewire.admin.product.edit');
    }
}
