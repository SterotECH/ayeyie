<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public array $filters = [
        'min_price' => null,
        'max_price' => null,
        'in_stock' => false,
    ];

    #[Url(history: true)]
    public string $sortBy = 'name';

    #[Url(history: true)]
    public string $sortDirection = 'asc';

    public int $perPage = 10;

    /**
     * Build the product query with advanced filtering and searching
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function queryProducts()
    {
        $query = Product::query();

        if (!empty($this->search)) {
            $query->where(function (Builder $q) {
                $q->whereLike('name', "%{$this->search}%")
                  ->orWhereLike('description', "%{$this->search}%");
            });
        }

        if ($this->filters['min_price'] !== null) {
            $query->where('price', '>=', $this->filters['min_price']);
        }

        if ($this->filters['max_price'] !== null) {
            $query->where('price', '<=', $this->filters['max_price']);
        }

        if ($this->filters['in_stock']) {
            $query->where('stock_quantity', '>', 0);
        }

        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    /**
     * Reset pagination to first page when search or filters change
     *
     * @return void
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when filters are updated
     *
     * @return void
     */
    public function updatingFilters(): void
    {
        $this->resetPage();
    }

    /**
     * Set sorting column and direction
     *
     * @param string $column Column to sort by
     * @return void
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
     *
     * @return void
     */
    public function resetFilters(): void
    {
        $this->reset(['search', 'filters', 'sortBy', 'sortDirection']);
    }

    public function render(): View
    {
        $products = $this->queryProducts();

        return view('livewire.admin.product.index', [
            'products' => $products,
        ]);
    }
}
