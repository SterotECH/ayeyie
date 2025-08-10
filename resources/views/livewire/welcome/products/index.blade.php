<!-- Products Showcase Section -->
<section class="py-20 bg-muted">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center rounded-full bg-primary/10 px-4 py-2 text-sm font-medium text-primary mb-4">
                <flux:icon.sparkles class="mr-2 h-4 w-4" />
                Featured Products
            </div>
            <h2 class="text-3xl font-bold text-text-primary sm:text-4xl mb-4">
                Premium Poultry Feed Products
            </h2>
            <p class="text-lg text-text-secondary max-w-2xl mx-auto">
                Discover our carefully curated selection of high-quality poultry feeds,
                designed to maximize your birds' health and productivity.
            </p>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            @forelse ($products as $product)
                <x-product-card :product="$product" />
            @empty
                <!-- Empty State -->
                <div class="col-span-full text-center py-16">
                    <div class="w-24 h-24 bg-muted rounded-full flex items-center justify-center mx-auto mb-6">
                        <flux:icon.cube class="w-12 h-12 text-text-secondary" />
                    </div>
                    <h3 class="text-xl font-semibold text-text-primary mb-2">No Products Available</h3>
                    <p class="text-text-secondary mb-8">We're working hard to stock our shelves with quality feed products.</p>
                    <button class="bg-primary hover:bg-primary-hover text-background px-6 py-3 rounded-xl font-semibold transition-colors">
                        Check Back Soon
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Call to Action -->
        <div class="text-center">
            <div class="bg-card border border-border rounded-2xl p-8 inline-block">
                <h3 class="text-xl font-semibold text-text-primary mb-2">Need Something Specific?</h3>
                <p class="text-text-secondary mb-6">Browse our complete catalog or get personalized recommendations for your poultry operation.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <flux:button variant="primary" icon="squares-2x2" href="{{ route('welcome.products.index') }}" class="bg-primary hover:bg-primary-hover text-background px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:scale-105 inline-flex items-center">
                        Browse All Products
                    </flux:button>
                    <flux:modal.trigger name="recommendations-modal">
                        <flux:button icon="chat-bubble-left-ellipsis" variant="primary">
                            Get Recommendations
                        </flux:button>
                    </flux:modal.trigger>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations Modal -->
        <flux:modal name="recommendations-modal" class="max-w-2xl">
            <div class="space-y-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <flux:heading>Get Personalized Recommendations</flux:heading>
                        <flux:text class="mt-1">Tell us about your farm and we'll recommend the perfect feed for your birds</flux:text>
                    </div>
                </div>

                <form wire:submit="submitRecommendationRequest" class="space-y-6">
                    <!-- Farm Type -->
                    <div>
                        <flux:field>
                            <flux:label>What type of poultry do you raise?</flux:label>
                            <flux:select wire:model="farmType" placeholder="Select your poultry type">
                                <flux:select.option value="broiler">Broiler Chickens (Meat)</flux:select.option>
                                <flux:select.option value="layer">Layer Chickens (Eggs)</flux:select.option>
                                <flux:select.option value="turkey">Turkey</flux:select.option>
                                <flux:select.option value="duck">Duck</flux:select.option>
                                <flux:select.option value="rabbit">Rabbit</flux:select.option>
                                <flux:select.option value="mixed">Mixed Farm</flux:select.option>
                            </flux:select>
                            @error('farmType') <flux:error>{{ $message }}</flux:error> @enderror
                        </flux:field>
                    </div>

                    <!-- Bird Count -->
                    <div>
                        <flux:field>
                            <flux:label>How many birds do you have?</flux:label>
                            <flux:input
                                type="number"
                                wire:model="birdCount"
                                placeholder="e.g., 500"
                                min="1"
                                icon="calculator"
                            />
                            @error('birdCount') <flux:error>{{ $message }}</flux:error> @enderror
                        </flux:field>
                    </div>

                    <!-- Contact Method -->
                    <div>
                        <flux:field>
                            <flux:label>How would you like to receive recommendations?</flux:label>
                            <flux:select wire:model.live="contactMethod">
                                <flux:select.option value="email">Email</flux:select.option>
                                <flux:select.option value="phone">Phone Call</flux:select.option>
                                <flux:select.option value="whatsapp">WhatsApp</flux:select.option>
                            </flux:select>
                        </flux:field>
                    </div>

                    <!-- Contact Value -->
                    <div>
                        <flux:field>
                            <flux:label>
                                Your {{ $contactMethod === 'email' ? 'Email Address' : 'Phone Number' }}
                            </flux:label>
                            <flux:input
                                type="{{ $contactMethod === 'email' ? 'email' : 'tel' }}"
                                wire:model="contactValue"
                                placeholder="{{ $contactMethod === 'email' ? 'your@email.com' : '+233 XX XXX XXXX' }}"
                                icon="{{ $contactMethod === 'email' ? 'envelope' : 'phone' }}"
                            />
                            @error('contactValue') <flux:error>{{ $message }}</flux:error> @enderror
                        </flux:field>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-border">
                        <flux:button variant="ghost" wire:click="closeRecommendations" type="button">
                            Cancel
                        </flux:button>
                        <flux:button variant="primary" type="submit" class="px-8">
                            <flux:icon.paper-airplane class="w-4 h-4 mr-2" />
                            Get My Recommendations
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
</section>
