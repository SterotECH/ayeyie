<?php

declare(strict_types=1);

namespace App\Livewire\Welcome\Products;

use App\Models\Product;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class Index extends Component
{
    public bool $showRecommendationsModal = false;

    public string $farmType = '';

    public int $birdCount = 0;

    public string $contactMethod = 'email';

    public string $contactValue = '';

    public function openRecommendations(): void
    {
        $this->showRecommendationsModal = true;
    }

    public function closeRecommendations(): void
    {
        $this->showRecommendationsModal = false;
        $this->reset(['farmType', 'birdCount', 'contactMethod', 'contactValue']);
    }

    public function submitRecommendationRequest(): void
    {
        $this->validate([
            'farmType' => 'required|string',
            'birdCount' => 'required|integer|min:1',
            'contactMethod' => 'required|in:email,phone,whatsapp',
            'contactValue' => 'required|string',
        ]);

        // Here you would typically save the request to database or send an email
        // For now, we'll just show a success message

        Flux::toast(
            text: "Thank you! We'll send personalized feed recommendations to your {$this->contactMethod} within 24 hours.",
            variant: 'success',
        );

        $this->closeRecommendations();
    }

    public function render(): View
    {
        $products = Product::query()->inRandomOrder()->take(8)->get(); // Show 8 featured products

        return view('livewire.welcome.products.index', [
            'products' => $products,
        ]);
    }
}
