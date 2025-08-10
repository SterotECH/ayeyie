<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const CART_SESSION_KEY = 'shopping_cart';
    
    public function addToCart(Product $product, int $quantity): void
    {
        $cart = $this->getCart();
        $productId = $product->product_id;
        
        if (isset($cart[$productId])) {
            // Update existing item quantity
            $cart[$productId]['quantity'] += (int) $quantity;
        } else {
            // Add new item to cart
            $cart[$productId] = [
                'product_id' => $product->product_id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => (float) $product->price,
                'quantity' => (int) $quantity,
                'stock_quantity' => $product->stock_quantity,
                'threshold_quantity' => $product->threshold_quantity,
            ];
        }
        
        // Ensure we don't exceed stock quantity
        if ($cart[$productId]['quantity'] > $product->stock_quantity) {
            $cart[$productId]['quantity'] = $product->stock_quantity;
        }
        
        $this->saveCart($cart);
    }
    
    public function updateQuantity(int $productId, int $quantity): void
    {
        $cart = $this->getCart();
        
        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                $this->removeFromCart($productId);
            } else {
                // Ensure we don't exceed stock quantity
                $cart[$productId]['quantity'] = min((int) $quantity, $cart[$productId]['stock_quantity']);
                $this->saveCart($cart);
            }
        }
    }
    
    public function removeFromCart(int $productId): void
    {
        $cart = $this->getCart();
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $this->saveCart($cart);
        }
    }
    
    public function clearCart(): void
    {
        Session::forget(self::CART_SESSION_KEY);
    }
    
    public function getCart(): array
    {
        return Session::get(self::CART_SESSION_KEY, []);
    }
    
    public function getCartItems(): array
    {
        return array_values($this->getCart());
    }
    
    public function getItemCount(): int
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'quantity'));
    }
    
    public function getTotalAmount(): float
    {
        $cart = $this->getCart();
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }
    
    public function hasItems(): bool
    {
        return !empty($this->getCart());
    }
    
    public function validateCartItems(): array
    {
        $cart = $this->getCart();
        $validItems = [];
        $invalidItems = [];
        
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            
            if (!$product) {
                $invalidItems[] = $item['name'] . ' (Product no longer exists)';
                $this->removeFromCart($productId);
                continue;
            }
            
            if ($product->stock_quantity == 0) {
                $invalidItems[] = $item['name'] . ' (Out of stock)';
                $this->removeFromCart($productId);
                continue;
            }
            
            if ($item['quantity'] > $product->stock_quantity) {
                $cart[$productId]['quantity'] = $product->stock_quantity;
                $invalidItems[] = $item['name'] . ' (Quantity reduced to available stock: ' . $product->stock_quantity . ')';
            }
            
            // Update product info in case prices changed
            $cart[$productId]['price'] = (float) $product->price;
            $cart[$productId]['quantity'] = (int) $cart[$productId]['quantity'];
            $cart[$productId]['stock_quantity'] = $product->stock_quantity;
            
            $validItems[] = $cart[$productId];
        }
        
        // Always save cart to ensure updated prices and quantities are persisted
        $this->saveCart($cart);
        
        return [
            'valid_items' => $validItems,
            'invalid_items' => $invalidItems,
        ];
    }
    
    private function saveCart(array $cart): void
    {
        Session::put(self::CART_SESSION_KEY, $cart);
    }
}