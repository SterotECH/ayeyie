<?php

declare(strict_types=1);

namespace App\Livewire\Welcome\Products;

use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class All extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $sortBy = 'name';

    #[Url]
    public string $sortDirection = 'asc';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->sortBy = 'name';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, fn ($query) => $query->whereLike('name', '%' . $this->search . '%')
                ->orWhereLike('description', '%' . $this->search . '%'),
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);


        return view('livewire.welcome.products.all', [
            'products' => $products,
        ])->layout('components.layouts.welcome');
    }
}
