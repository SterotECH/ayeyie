<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Product;

use App\Enums\AuditAction;
use App\Models\Product;
use App\Services\AuditLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class Index extends Component
{
    use WithPagination;
    
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public array $filters = [
        'min_price' => null,
        'max_price' => null,
        'stock_status' => '',
    ];

    #[Url(history: true)]
    public string $sortBy = 'name';

    #[Url(history: true)]
    public string $sortDirection = 'asc';

    public int $perPage = 15;
    
    public bool $confirmingProductDeletion = false;
    public ?int $productIdBeingDeleted = null;

    /**
     * Reset pagination to first page when search changes
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when filters are updated
     */
    public function updatingFilters(): void
    {
        $this->resetPage();
    }

    /**
     * Set sorting column and direction
     *
     * @param  string  $column  Column to sort by
     */
    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Reset all filters to default state
     */
    public function resetFilters(): void
    {
        $this->reset(['search', 'filters', 'sortBy', 'sortDirection']);
        $this->sortBy = 'name';
        $this->sortDirection = 'asc';
    }
    
    /**
     * Confirm product deletion
     */
    public function confirmProductDeletion(int $productId): void
    {
        $this->confirmingProductDeletion = true;
        $this->productIdBeingDeleted = $productId;
    }
    
    /**
     * Delete the confirmed product
     */
    public function deleteProduct(): void
    {
        if ($this->productIdBeingDeleted) {
            $product = Product::find($this->productIdBeingDeleted);
            
            if ($product) {
                $actor = Auth::user();
                
                // Log the product deletion event before deletion
                $this->auditLogService->logProductManagement(
                    AuditAction::PRODUCT_DELETED,
                    $product,
                    $actor,
                    [
                        'deleted_product_name' => $product->name,
                        'deleted_product_price' => $product->price,
                        'deleted_product_stock' => $product->stock_quantity,
                        'deleted_product_id' => $product->product_id,
                    ]
                );
                
                $product->delete();
            }
        }
        
        $this->confirmingProductDeletion = false;
        $this->productIdBeingDeleted = null;
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Product deleted successfully!'
        ]);
    }

    public function render(): View
    {
        $products = $this->queryProducts();

        // Get statistics for dashboard cards
        $totalProducts = Product::count();
        $inStockProducts = Product::where('stock_quantity', '>', 0)->count();
        $lowStockProducts = Product::whereRaw('stock_quantity <= threshold_quantity AND stock_quantity > 0')->count();
        $outOfStockProducts = Product::where('stock_quantity', '=', 0)->count();

        return view('livewire.admin.product.index', [
            'products' => $products,
            'stats' => [
                'total' => $totalProducts,
                'in_stock' => $inStockProducts,
                'low_stock' => $lowStockProducts,
                'out_of_stock' => $outOfStockProducts,
            ],
        ]);
    }

    /**
     * Build the product query with advanced filtering and searching
     *
     * @return LengthAwarePaginator
     */
    protected function queryProducts(): LengthAwarePaginator
    {
        $query = Product::query();

        // Search functionality
        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->whereLike('name', "%$this->search%")
                    ->orWhereLike('description', "%$this->search%");
            });
        }

        // Price filters
        if ($this->filters['min_price'] !== null) {
            $query->where('price', '>=', $this->filters['min_price']);
        }

        if ($this->filters['max_price'] !== null) {
            $query->where('price', '<=', $this->filters['max_price']);
        }

        // Stock status filter
        if ($this->filters['stock_status'] === 'in_stock') {
            $query->where('stock_quantity', '>', 0);
        } elseif ($this->filters['stock_status'] === 'low_stock') {
            $query->whereRaw('stock_quantity <= threshold_quantity AND stock_quantity > 0');
        } elseif ($this->filters['stock_status'] === 'out_of_stock') {
            $query->where('stock_quantity', '=', 0);
        }

        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate($this->perPage);
    }
}
