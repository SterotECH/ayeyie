<?php

namespace App\Livewire\Admin\StockAlert;

use App\Models\Product;
use App\Models\StockAlert;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Show extends Component
{
    public ?StockAlert $alert = null;

    public ?Product $product = null;


    /**
     * Mount the component with the provided alert ID
     *
     * @param int $alertId The ID of the alert to display
     * @return void
     */
    public function mount(StockAlert $stockAlert): void
    {
        $this->alert = $stockAlert;
        $this->product = $stockAlert->product;
    }

    /**
     * Render the component
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.admin.stock-alert.show');
    }
}
