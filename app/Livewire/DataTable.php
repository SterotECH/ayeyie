<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DataTable extends Component
{
    use WithPagination;

    public ?Model $model = null;
    public array $columns = [];
    public array $searchColumns = [];
    public bool $useCustomRowTemplate = false;
    public string $customRowView = '';
    public bool $useCardViewMobile = true;
    public string $sortColumn = 'id';
    public string $sortDirection = 'asc';
    public string $searchTerm = '';
    public int $perPage = 10;
    public array $perPageOptions = [10, 25, 50, 100];
    public bool $selectAll = false;
    public array $selected = [];
    public array $bulkActions = [];
    public string|null $selectedBulkAction = null;
    public array $filters = [];

    protected $listeners = ['refreshTable' => '$refresh'];

    public function mount($model, $columns, $searchColumns = null, $bulkActions = [], $customRowView = null, $useCardViewMobile = true)
    {
        $this->model = $model;
        $this->columns = $columns;
        $this->searchColumns = $searchColumns ?? array_keys($columns);
        $this->bulkActions = $bulkActions;

        if ($customRowView) {
            $this->useCustomRowTemplate = true;
            $this->customRowView = $customRowView;
        }

        $this->useCardViewMobile = $useCardViewMobile;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->rows->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected()
    {
        $this->selectAll = count($this->selected) === $this->rows->count();
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function executeBulkAction()
    {
        if (!$this->selectedBulkAction || empty($this->selected)) {
            return;
        }

        $action = $this->bulkActions[$this->selectedBulkAction];

        if (is_callable($action)) {
            $action($this->selected);
        }

        $this->selected = [];
        $this->selectAll = false;
        $this->selectedBulkAction = null;

        $this->dispatchBrowserEvent('bulk-action-executed');
    }

    public function applyFilter($name, $value)
    {
        $this->filters[$name] = $value;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->filters = [];
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->searchTerm = '';
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedPerPage($value)
    {
        $this->resetPage();
    }

    public function getRowsProperty()
    {
        $query = $this->model::query();

        if ($this->searchTerm) {
            $query->where(function (Builder $subQuery) {
                foreach ($this->searchColumns as $column) {
                    $subQuery->orWhereLike($column, '%' . $this->searchTerm . '%');
                }
            });
        }

        foreach ($this->filters as $name => $value) {
            if ($value !== null && $value !== '') {
                $query->where($name, $value);
            }
        }

        // Apply sorting
        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.data-table', [
            'rows' => $this->rows,
        ]);
    }
}
