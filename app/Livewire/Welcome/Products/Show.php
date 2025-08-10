<?php

declare(strict_types=1);

namespace App\Livewire\Welcome\Products;

use App\Models\Product;
use App\Services\CartService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

final class Show extends Component
{
    public Product $product;

    #[Validate('required|integer|min:1')]
    public int $quantity = 1;

    public float $totalPrice;

    public int $cartItemCount = 0;

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->calculateTotal();
        $this->updateCartCount();
    }

    public function updatedQuantity(): void
    {
        $this->validateQuantity();
        $this->calculateTotal();
    }

    public function validateQuantity(): void
    {
        if ($this->quantity > $this->product->stock_quantity) {
            $this->quantity = $this->product->stock_quantity;
            Flux::toast(
                text: "Quantity adjusted to available stock ({$this->product->stock_quantity})",
                variant: 'warning',
            );
        }
    }

    public function addToCart(): void
    {
        $this->validate();
        $this->validateQuantity();

        try {
            app(CartService::class)->addToCart($this->product, $this->quantity);
            $this->updateCartCount();

            Flux::toast(
                text: "Added {$this->quantity} bag(s) of {$this->product->name} to cart!",
                variant: 'success',
            );

            // Reset quantity to 1 after adding to cart
            $this->quantity = 1;
            $this->calculateTotal();

        } catch (\Exception $e) {
            Flux::toast(
                text: 'Failed to add item to cart. Please try again.',
                variant: 'danger',
            );
        }
    }

    public function quickOrder(): void
    {
        if (! Auth::check()) {
            session(['intended_action' => 'quick_order', 'quick_order_data' => [
                'product_id' => $this->product->product_id,
                'quantity' => $this->quantity,
            ]]);

            $this->redirect(route('login'), navigate: true);
        }

        $this->validate();
        $this->validateQuantity();

        // Clear cart and add this single item for quick order
        $cartService = app(CartService::class);
        $cartService->clearCart();
        $cartService->addToCart($this->product, $this->quantity);

        $this->redirect(route('customers.orders.create'), navigate: true);
    }

    public function viewCart(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'), navigate: true);
        }

        $this->redirect(route('customers.orders.create'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.welcome.products.show')->layout('components.layouts.welcome');
    }

    private function updateCartCount(): void
    {
        $this->cartItemCount = app(CartService::class)->getItemCount();
    }

    private function calculateTotal(): void
    {
        $this->totalPrice = $this->product->price * $this->quantity;
    }
}
