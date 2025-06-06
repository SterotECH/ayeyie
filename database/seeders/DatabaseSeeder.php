<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            try {
                $products = [
                    [
                        'name' => 'Broiler Starter Mash (0-3 Weeks)',
                        'image' => 'broiler-starter-mash.jpg',
                        'description' => 'A high-protein, easily digestible mash formulated specifically for broiler chicks during their first 3 weeks of life. This starter mash is designed to support rapid growth, promote strong immune system development, and ensure optimal health. It contains essential vitamins, minerals, and amino acids necessary for early development. Medicated options are available upon request to aid in the prevention of common poultry diseases. Recommended feeding: Ad libitum.',
                        'price' => 180.00,
                        'stock_quantity' => 450,
                        'threshold_quantity' => 100,
                    ],
                    [
                        'name' => 'Broiler Finisher Pellets (3+ Weeks)',
                        'image' => 'broiler-finisher-pellets.jpg',
                        'description' => 'A high-energy pellet feed designed to maximize weight gain and feed conversion efficiency in broiler chickens from 3 weeks of age until they reach market weight. This finisher feed ensures optimal carcass quality and contains essential amino acids and growth promoters to support efficient growth and muscle development. It is formulated to meet the specific nutritional needs of broilers in their final growth phase. Recommended feeding: Ad libitum.',
                        'price' => 175.00,
                        'stock_quantity' => 600,
                        'threshold_quantity' => 120,
                    ],
                    [
                        'name' => 'Layer Starter Mash (0-8 Weeks)',
                        'image' => 'layer-starter-mash.jpg',
                        'description' => 'A balanced mash formulated for layer chicks from day-old to 8 weeks. This starter mash promotes healthy growth, supports skeletal development, and prepares them for future egg production. It contains essential nutrients, including calcium and phosphorus, which are crucial for bone development and overall health. This feed ensures a strong foundation for laying hens. Recommended feeding: As per breed guidelines.',
                        'price' => 160.00,
                        'stock_quantity' => 380,
                        'threshold_quantity' => 80,
                    ],
                    [
                        'name' => 'Layer Grower Pellets (8-20 Weeks)',
                        'image' => 'layer-grower-pellets.jpg',
                        'description' => 'A grower pellet feed specifically designed for pullets aged 8-20 weeks. This feed supports continued growth, promotes the development of the reproductive system, and ensures a smooth transition to laying. It contains essential vitamins and minerals necessary for healthy growth and the development of strong, productive hens. This feed helps pullets reach their full potential. Recommended feeding: Gradually increase from 70-100g per bird per day.',
                        'price' => 155.00,
                        'stock_quantity' => 520,
                        'threshold_quantity' => 110,
                    ],
                    [
                        'name' => 'Layer Production Mash (20+ Weeks)',
                        'image' => 'layer-production-mash.jpg',
                        'description' => 'A complete mash feed formulated for laying hens in their active production phase (20+ weeks). This feed is high in calcium to support strong eggshells and contains all the necessary nutrients for consistent egg output and hen health. It is designed to meet the increased nutritional demands of laying hens and ensure optimal egg production. Recommended feeding: 110-120g per bird per day.',
                        'price' => 170.00,
                        'stock_quantity' => 700,
                        'threshold_quantity' => 150,
                    ],
                    [
                        'name' => 'Chick Starter Crumble (Medicated)',
                        'image' => 'chick-starter-medicated.jpg',
                        'description' => 'A medicated crumble feed specifically designed for chicks, formulated to prevent common poultry diseases during the early stages of life. This feed supports healthy growth and provides essential nutrients. Use as directed by a veterinarian. Medication: Coccidiostat.  This feed should be used preventatively.',
                        'price' => 200.00,
                        'stock_quantity' => 200,
                        'threshold_quantity' => 50,
                    ],
                    [
                        'name' => 'Grower Finisher Mash (Ducks)',
                        'image' => 'grower-finisher-duck.jpg',
                        'description' => 'A specialized mash formulated for growing and finishing ducks. This feed provides balanced nutrition to support healthy growth, proper feathering, and overall well-being. It is suitable for ducks from 3 weeks of age until they reach market weight, ensuring they receive the necessary nutrients for optimal development.  Contains the correct balance of protein and energy. Recommended feeding: As per duckling age and breed.',
                        'price' => 165.00,
                        'stock_quantity' => 250,
                        'threshold_quantity' => 60,
                    ],
                    [
                        'name' => 'Turkey Starter Pellets',
                        'image' => 'turkey-starter-pellets.jpg',
                        'description' => 'A high-protein starter pellet feed specifically designed for young turkeys (poults). This feed supports rapid growth and skeletal development during the early stages of life. It contains essential amino acids and vitamins necessary for healthy development and to ensure a strong start for turkeys.  Proper levels of protein and energy are included. Recommended feeding: Ad libitum.',
                        'price' => 210.00,
                        'stock_quantity' => 180,
                        'threshold_quantity' => 40,
                    ],
                    [
                        'name' => 'Guinea Fowl Grower Mash',
                        'image' => 'guinea-fowl-grower.jpg',
                        'description' => 'A balanced grower mash formulated specifically for guinea fowl, designed to support healthy growth and development. This feed provides the necessary nutrients, vitamins, and minerals for optimal health and ensures that guinea fowl reach their full potential.  Feeding recommendations vary based on age.',
                        'price' => 150.00,
                        'stock_quantity' => 220,
                        'threshold_quantity' => 55,
                    ],
                    [
                        'name' => 'Premium Broiler Pre-Starter',
                        'image' => 'broiler-prestarter.jpg',
                        'description' => 'An ultra-fine crumble feed specifically designed for broiler chicks in their first 10 days of life. This highly digestible and nutrient-dense feed is formulated to maximize early growth, reduce mortality, and support the development of a strong immune system. Contains probiotics and prebiotics to promote gut health. Recommended feeding: Ad libitum.',
                        'price' => 220.00,
                        'stock_quantity' => 150,
                        'threshold_quantity' => 35,
                    ],
                    [
                        'name' => 'Organic Layer Blend',
                        'image' => 'organic-layer.jpg',
                        'description' => 'A certified organic blend of grains and seeds, formulated to provide complete nutrition for laying hens. This feed uses only organically sourced ingredients and supports both egg production and hen health. It is designed for farmers who adhere to organic farming practices.  A variety of grains are used to ensure palatability. Recommended feeding: 110-120g per bird per day.',
                        'price' => 240.00,
                        'stock_quantity' => 120,
                        'threshold_quantity' => 30,
                    ],
                    [
                        'name' => 'High-Energy Broiler Finisher',
                        'image' => 'high-energy-broiler.jpg',
                        'description' => 'A high-energy finisher feed specifically designed for broilers in the final weeks before slaughter. This feed maximizes weight gain, improves feed conversion ratio, and ensures optimal carcass quality. It contains added fat and essential amino acids to support rapid and efficient growth.  This feed is formulated for commercial broiler production. Recommended feeding: Ad libitum.',
                        'price' => 190.00,
                        'stock_quantity' => 480,
                        'threshold_quantity' => 90,
                    ],
                    [
                        'name' => 'Layer Supplement Mix',
                        'image' => 'layer-supplement.jpg',
                        'description' => 'A supplementary feed mix for laying hens, containing additional vitamins, minerals, and protein. This mix is designed to boost egg production and support hen health, particularly during periods of stress or high production. It can be added to their regular feed.  Improves overall flock health. Feeding: As needed.',
                        'price' => 100.00,
                        'stock_quantity' => 300,
                        'threshold_quantity' => 70,
                    ],
                    [
                        'name' => 'Broiler Withdrawal Feed',
                        'image' => 'broiler-withdrawal.jpg',
                        'description' => 'A specialized feed formulated for broilers in the withdrawal period before slaughter. This feed is free of medications and is designed to ensure meat quality and safety. It follows recommended withdrawal times for various medications to comply with regulations and ensure consumer safety.  Strict adherence to feeding guidelines is necessary. Recommended feeding: As directed by veterinarian.',
                        'price' => 185.00,
                        'stock_quantity' => 200,
                        'threshold_quantity' => 45,
                    ],
                    [
                        'name' => 'Duck Starter Crumble',
                        'image' => 'duck-starter.jpg',
                        'description' => 'A crumble feed specifically formulated for ducklings from day one to 2 weeks of age. This starter crumble provides the high levels of niacin that ducklings need for healthy growth and development, along with other essential nutrients. It is designed to be easily digestible and support rapid growth in young ducks.  Contains all essential nutrients for ducklings. Recommended feeding: Ad libitum.',
                        'price' => 195.00,
                        'stock_quantity' => 170,
                        'threshold_quantity' => 40,
                    ],
                    [
                        'name' => 'Turkey Grower Finisher',
                        'image' => 'turkey-grower-finisher.jpg',
                        'description' => 'A pellet feed specifically designed for growing and finishing turkeys. This feed is formulated to support muscle development and weight gain, and it contains the balanced protein and energy that turkeys require for optimal growth and health. It ensures that turkeys reach their market weight efficiently. Recommended feeding: As per age and breed guidelines.',
                        'price' => 205.00,
                        'stock_quantity' => 130,
                        'threshold_quantity' => 30,
                    ],
                    [
                        'name' => 'Rabbit Starter Pellets',
                        'image' => 'rabbit-starter-pellets.jpg',
                        'description' => 'A complete and balanced pellet feed specifically formulated for starting rabbits. This feed is high in fiber and protein to support healthy growth and the development of the digestive system. It provides all the necessary nutrients for young rabbits in their early stages of life.  Ensures proper development. Recommended feeding: As per rabbit age and breed.',
                        'price' => 140.00,
                        'stock_quantity' => 280,
                        'threshold_quantity' => 65,
                    ],
                    [
                        'name' => 'Rabbit Grower Pellets',
                        'image' => 'rabbit-grower-pellets.jpg',
                        'description' => 'A complete and balanced pellet feed specifically designed for growing rabbits. This feed is high in fiber and protein to support healthy growth and the development of the digestive system. It provides the necessary nutrients for growing rabbits to reach their full potential. Recommended feeding: As per rabbit age and breed.',
                        'price' => 130.00,
                        'stock_quantity' => 300,
                        'threshold_quantity' => 75,
                    ],
                    [
                        'name' => 'Poultry Electrolyte Supplement',
                        'image' => 'poultry-electrolyte.jpg',
                        'description' => 'A water-soluble electrolyte and vitamin supplement specifically formulated for poultry. This supplement helps to maintain hydration and electrolyte balance during periods of stress, such as heat, transportation, or disease. It supports overall flock health and reduces the negative impacts of stress.  Administer via drinking water. Dosage instructions provided.',
                        'price' => 60.00,
                        'stock_quantity' => 1000,
                        'threshold_quantity' => 200,
                    ],
                    [
                        'name' => 'Poultry Probiotic Supplement',
                        'image' => 'poultry-probiotic.jpg',
                        'description' => 'A water-soluble probiotic supplement specifically designed for poultry. This supplement promotes a healthy gut flora, improves digestion, and enhances the immune system. It contributes to overall flock health, reduces the risk of disease, and improves feed efficiency. Administer via drinking water. Dosage instructions provided.',
                        'price' => 80.00,
                        'stock_quantity' => 800,
                        'threshold_quantity' => 150,
                    ],
                ];

                foreach ($products as $productData) {
                    Product::create($productData);
                }

                $this->command->info('Poultry feed data seeded successfully.');
            } catch (\Exception $e) {
                $this->command->error("Error seeding poultry feed data: {$e->getMessage()}");
                DB::rollBack();
            }
        });
    }
}
