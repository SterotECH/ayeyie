<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

final class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Broiler Feeds
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
                'name' => 'Premium Broiler Pre-Starter',
                'image' => 'broiler-prestarter.jpg',
                'description' => 'An ultra-fine crumble feed specifically designed for broiler chicks in their first 10 days of life. This highly digestible and nutrient-dense feed is formulated to maximize early growth, reduce mortality, and support the development of a strong immune system. Contains probiotics and prebiotics to promote gut health. Recommended feeding: Ad libitum.',
                'price' => 220.00,
                'stock_quantity' => 150,
                'threshold_quantity' => 35,
            ],
            [
                'name' => 'High-Energy Broiler Finisher',
                'image' => 'high-energy-broiler.jpg',
                'description' => 'A high-energy finisher feed specifically designed for broilers in the final weeks before slaughter. This feed maximizes weight gain, improves feed conversion ratio, and ensures optimal carcass quality. It contains added fat and essential amino acids to support rapid and efficient growth. This feed is formulated for commercial broiler production. Recommended feeding: Ad libitum.',
                'price' => 190.00,
                'stock_quantity' => 480,
                'threshold_quantity' => 90,
            ],
            [
                'name' => 'Broiler Withdrawal Feed',
                'image' => 'broiler-withdrawal.jpg',
                'description' => 'A specialized feed formulated for broilers in the withdrawal period before slaughter. This feed is free of medications and is designed to ensure meat quality and safety. It follows recommended withdrawal times for various medications to comply with regulations and ensure consumer safety. Strict adherence to feeding guidelines is necessary. Recommended feeding: As directed by veterinarian.',
                'price' => 185.00,
                'stock_quantity' => 200,
                'threshold_quantity' => 45,
            ],
            [
                'name' => 'Broiler Grower Crumble (10-21 Days)',
                'image' => 'broiler-grower-crumble.jpg',
                'description' => 'A specially formulated crumble feed for broiler chicks aged 10-21 days. This transition feed bridges the gap between starter and finisher feeds, providing optimal nutrition during the critical growth phase. Contains balanced protein and energy levels to support steady weight gain and skeletal development.',
                'price' => 178.00,
                'stock_quantity' => 320,
                'threshold_quantity' => 75,
            ],

            // Layer Feeds
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
                'name' => 'Organic Layer Blend',
                'image' => 'organic-layer.jpg',
                'description' => 'A certified organic blend of grains and seeds, formulated to provide complete nutrition for laying hens. This feed uses only organically sourced ingredients and supports both egg production and hen health. It is designed for farmers who adhere to organic farming practices. A variety of grains are used to ensure palatability. Recommended feeding: 110-120g per bird per day.',
                'price' => 240.00,
                'stock_quantity' => 120,
                'threshold_quantity' => 30,
            ],
            [
                'name' => 'Layer Pre-Lay Mash (16-20 Weeks)',
                'image' => 'layer-prelay-mash.jpg',
                'description' => 'A specialized mash formulated for pullets in the pre-laying period (16-20 weeks). This feed provides increased calcium levels to prepare the reproductive system for egg production. Contains essential nutrients to support the transition from growing to laying phase.',
                'price' => 165.00,
                'stock_quantity' => 290,
                'threshold_quantity' => 65,
            ],
            [
                'name' => 'Layer Breeder Feed',
                'image' => 'layer-breeder-feed.jpg',
                'description' => 'A premium feed formulated for layer breeder birds to maximize fertility, hatchability, and chick quality. Contains enhanced vitamin E, selenium, and other nutrients essential for reproductive performance. Suitable for both male and female breeding stock.',
                'price' => 195.00,
                'stock_quantity' => 180,
                'threshold_quantity' => 40,
            ],

            // Duck Feeds
            [
                'name' => 'Duck Starter Crumble',
                'image' => 'duck-starter.jpg',
                'description' => 'A crumble feed specifically formulated for ducklings from day one to 2 weeks of age. This starter crumble provides the high levels of niacin that ducklings need for healthy growth and development, along with other essential nutrients. It is designed to be easily digestible and support rapid growth in young ducks. Contains all essential nutrients for ducklings. Recommended feeding: Ad libitum.',
                'price' => 195.00,
                'stock_quantity' => 170,
                'threshold_quantity' => 40,
            ],
            [
                'name' => 'Grower Finisher Mash (Ducks)',
                'image' => 'grower-finisher-duck.jpg',
                'description' => 'A specialized mash formulated for growing and finishing ducks. This feed provides balanced nutrition to support healthy growth, proper feathering, and overall well-being. It is suitable for ducks from 3 weeks of age until they reach market weight, ensuring they receive the necessary nutrients for optimal development. Contains the correct balance of protein and energy. Recommended feeding: As per duckling age and breed.',
                'price' => 165.00,
                'stock_quantity' => 250,
                'threshold_quantity' => 60,
            ],
            [
                'name' => 'Waterfowl Maintenance Feed',
                'image' => 'waterfowl-maintenance.jpg',
                'description' => 'A complete maintenance feed for mature ducks and geese. Formulated with lower protein levels suitable for adult waterfowl not in production. Contains balanced nutrients to maintain optimal health and condition in ornamental and breeding waterfowl.',
                'price' => 145.00,
                'stock_quantity' => 200,
                'threshold_quantity' => 50,
            ],

            // Turkey Feeds
            [
                'name' => 'Turkey Starter Pellets',
                'image' => 'turkey-starter-pellets.jpg',
                'description' => 'A high-protein starter pellet feed specifically designed for young turkeys (poults). This feed supports rapid growth and skeletal development during the early stages of life. It contains essential amino acids and vitamins necessary for healthy development and to ensure a strong start for turkeys. Proper levels of protein and energy are included. Recommended feeding: Ad libitum.',
                'price' => 210.00,
                'stock_quantity' => 180,
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
                'name' => 'Turkey Breeder Feed',
                'image' => 'turkey-breeder-feed.jpg',
                'description' => 'A specialized feed for breeding turkeys designed to optimize reproductive performance. Contains enhanced levels of vitamins A, D, E, and essential fatty acids to support fertility and egg quality. Formulated to meet the nutritional needs of both male and female breeding turkeys.',
                'price' => 225.00,
                'stock_quantity' => 90,
                'threshold_quantity' => 20,
            ],

            // Specialty Feeds
            [
                'name' => 'Guinea Fowl Grower Mash',
                'image' => 'guinea-fowl-grower.jpg',
                'description' => 'A balanced grower mash formulated specifically for guinea fowl, designed to support healthy growth and development. This feed provides the necessary nutrients, vitamins, and minerals for optimal health and ensures that guinea fowl reach their full potential. Feeding recommendations vary based on age.',
                'price' => 150.00,
                'stock_quantity' => 220,
                'threshold_quantity' => 55,
            ],
            [
                'name' => 'Gamebird Starter Crumble',
                'image' => 'gamebird-starter-crumble.jpg',
                'description' => 'A high-protein crumble feed formulated for gamebird chicks including pheasants, quail, and partridges. Contains 28% protein to support rapid early growth and development. Includes essential vitamins and minerals specific to gamebird requirements.',
                'price' => 235.00,
                'stock_quantity' => 140,
                'threshold_quantity' => 30,
            ],
            [
                'name' => 'Gamebird Finisher Pellets',
                'image' => 'gamebird-finisher-pellets.jpg',
                'description' => 'A complete pelleted feed for finishing gamebirds. Formulated with optimal protein and energy levels to promote excellent feather development and meat quality. Suitable for pheasants, quail, and other gamebird species from 6 weeks to maturity.',
                'price' => 220.00,
                'stock_quantity' => 110,
                'threshold_quantity' => 25,
            ],
            [
                'name' => 'Quail Breeder Crumble',
                'image' => 'quail-breeder-crumble.jpg',
                'description' => 'A specialized crumble feed for breeding quail with enhanced protein and vitamin levels. Formulated to maximize egg production, fertility, and hatchability. Contains optimal calcium levels for strong eggshell formation.',
                'price' => 250.00,
                'stock_quantity' => 85,
                'threshold_quantity' => 20,
            ],

            // Medicated Feeds
            [
                'name' => 'Chick Starter Crumble (Medicated)',
                'image' => 'chick-starter-medicated.jpg',
                'description' => 'A medicated crumble feed specifically designed for chicks, formulated to prevent common poultry diseases during the early stages of life. This feed supports healthy growth and provides essential nutrients. Use as directed by a veterinarian. Medication: Coccidiostat. This feed should be used preventatively.',
                'price' => 200.00,
                'stock_quantity' => 200,
                'threshold_quantity' => 50,
            ],
            [
                'name' => 'Medicated Turkey Starter',
                'image' => 'medicated-turkey-starter.jpg',
                'description' => 'A medicated starter feed for turkey poults containing coccidiostat to prevent coccidiosis. High in protein (28%) to support rapid early growth. Essential for turkey poults in their first few weeks of life to prevent disease outbreaks.',
                'price' => 240.00,
                'stock_quantity' => 120,
                'threshold_quantity' => 30,
            ],
            [
                'name' => 'Medicated Waterfowl Starter',
                'image' => 'medicated-waterfowl-starter.jpg',
                'description' => 'A medicated starter feed specifically formulated for ducklings and goslings. Contains medication to prevent common waterfowl diseases while providing high niacin levels essential for proper leg development in young waterfowl.',
                'price' => 215.00,
                'stock_quantity' => 95,
                'threshold_quantity' => 25,
            ],

            // Rabbit Feeds
            [
                'name' => 'Rabbit Starter Pellets',
                'image' => 'rabbit-starter-pellets.jpg',
                'description' => 'A complete and balanced pellet feed specifically formulated for young rabbits. This feed is high in fiber and protein to support healthy growth and the development of the digestive system. It provides all the necessary nutrients for young rabbits in their early stages of life. Ensures proper development. Recommended feeding: As per rabbit age and breed.',
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
                'name' => 'Rabbit Maintenance Pellets',
                'image' => 'rabbit-maintenance-pellets.jpg',
                'description' => 'A maintenance pellet feed for adult rabbits not in production. Contains optimal fiber levels (18-22%) to support digestive health and prevent GI stasis. Formulated with timothy hay and other quality ingredients for long-term health.',
                'price' => 125.00,
                'stock_quantity' => 350,
                'threshold_quantity' => 80,
            ],
            [
                'name' => 'Rabbit Breeder Pellets',
                'image' => 'rabbit-breeder-pellets.jpg',
                'description' => 'A high-nutrition pellet feed for breeding does and growing litters. Contains 18% protein to support lactation and rapid kit growth. Enhanced with vitamins and minerals essential for reproductive performance and kit development.',
                'price' => 155.00,
                'stock_quantity' => 190,
                'threshold_quantity' => 45,
            ],

            // Supplements and Additives
            [
                'name' => 'Layer Supplement Mix',
                'image' => 'layer-supplement.jpg',
                'description' => 'A supplementary feed mix for laying hens, containing additional vitamins, minerals, and protein. This mix is designed to boost egg production and support hen health, particularly during periods of stress or high production. It can be added to their regular feed. Improves overall flock health. Feeding: As needed.',
                'price' => 100.00,
                'stock_quantity' => 300,
                'threshold_quantity' => 70,
            ],
            [
                'name' => 'Poultry Electrolyte Supplement',
                'image' => 'poultry-electrolyte.jpg',
                'description' => 'A water-soluble electrolyte and vitamin supplement specifically formulated for poultry. This supplement helps to maintain hydration and electrolyte balance during periods of stress, such as heat, transportation, or disease. It supports overall flock health and reduces the negative impacts of stress. Administer via drinking water. Dosage instructions provided.',
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
            [
                'name' => 'Calcium Carbonate Supplement',
                'image' => 'calcium-carbonate-supplement.jpg',
                'description' => 'Pure calcium carbonate supplement for laying hens. Provides additional calcium to support strong eggshell formation. Can be offered free-choice in a separate feeder or mixed with regular feed. Essential for high-producing laying hens.',
                'price' => 45.00,
                'stock_quantity' => 600,
                'threshold_quantity' => 120,
            ],
            [
                'name' => 'Oyster Shell Grit',
                'image' => 'oyster-shell-grit.jpg',
                'description' => 'Natural oyster shell grit providing soluble calcium for laying hens. Helps with eggshell formation and provides digestive grit. Should be offered free-choice in a separate container. Essential supplement for backyard and commercial laying operations.',
                'price' => 35.00,
                'stock_quantity' => 800,
                'threshold_quantity' => 150,
            ],
            [
                'name' => 'Poultry Vitamin Premix',
                'image' => 'poultry-vitamin-premix.jpg',
                'description' => 'A concentrated vitamin and mineral premix for poultry feeds. Contains essential vitamins A, D, E, K, and B-complex vitamins plus trace minerals. Used for custom feed mixing or as a supplement during stress periods.',
                'price' => 120.00,
                'stock_quantity' => 200,
                'threshold_quantity' => 40,
            ],

            // Scratch Grains and Treats
            [
                'name' => 'Premium Scratch Grain Mix',
                'image' => 'premium-scratch-grain.jpg',
                'description' => 'A premium blend of cracked corn, wheat, and sunflower seeds. Perfect as a treat for chickens and other poultry. Encourages natural foraging behavior and provides entertainment. Should not exceed 10% of total daily feed intake.',
                'price' => 75.00,
                'stock_quantity' => 500,
                'threshold_quantity' => 100,
            ],
            [
                'name' => 'Mealworm Treats',
                'image' => 'mealworm-treats.jpg',
                'description' => 'Dried mealworms rich in protein and perfect as a treat for chickens, ducks, and other poultry. Provides entertainment and natural foraging experience. Excellent source of protein and calcium. Feed in moderation as a supplement to regular feed.',
                'price' => 180.00,
                'stock_quantity' => 150,
                'threshold_quantity' => 30,
            ],
            [
                'name' => 'Cracked Corn',
                'image' => 'cracked-corn.jpg',
                'description' => 'High-quality cracked corn suitable for all poultry species. Provides energy and is an excellent winter feed to help birds maintain body heat. Can be fed alone or mixed with other grains. Should be part of a balanced diet.',
                'price' => 50.00,
                'stock_quantity' => 750,
                'threshold_quantity' => 150,
            ],
            [
                'name' => 'Mixed Poultry Scratch',
                'image' => 'mixed-poultry-scratch.jpg',
                'description' => 'A traditional scratch feed mixture containing cracked corn, wheat, and barley. Encourages natural pecking and foraging behaviors. Ideal for free-range birds and as an afternoon treat. Should complement, not replace, complete feeds.',
                'price' => 65.00,
                'stock_quantity' => 400,
                'threshold_quantity' => 80,
            ],

            // Specialty and Organic Feeds
            [
                'name' => 'Organic Broiler Starter',
                'image' => 'organic-broiler-starter.jpg',
                'description' => 'Certified organic starter feed for broiler chicks. Made from organically grown grains without synthetic pesticides, herbicides, or GMOs. Provides complete nutrition while meeting organic certification requirements.',
                'price' => 280.00,
                'stock_quantity' => 80,
                'threshold_quantity' => 20,
            ],
            [
                'name' => 'Non-GMO Layer Feed',
                'image' => 'non-gmo-layer-feed.jpg',
                'description' => 'A complete layer feed made from non-GMO ingredients. Provides all essential nutrients for optimal egg production without genetically modified components. Perfect for producers seeking non-GMO certification.',
                'price' => 200.00,
                'stock_quantity' => 160,
                'threshold_quantity' => 35,
            ],
            [
                'name' => 'Herbal Poultry Supplement',
                'image' => 'herbal-poultry-supplement.jpg',
                'description' => 'A natural herbal blend containing oregano, thyme, and other beneficial herbs. Supports immune function and digestive health naturally. Can be mixed with regular feed or offered separately. No withdrawal period required.',
                'price' => 95.00,
                'stock_quantity' => 250,
                'threshold_quantity' => 50,
            ],
            [
                'name' => 'Fermented Feed Starter',
                'image' => 'fermented-feed-starter.jpg',
                'description' => 'A probiotic starter culture for fermenting poultry feeds. Improves feed digestibility, enhances nutrient absorption, and promotes beneficial gut bacteria. Instructions included for proper fermentation process.',
                'price' => 55.00,
                'stock_quantity' => 180,
                'threshold_quantity' => 40,
            ],

            // Waterfowl Specific
            [
                'name' => 'Goose Grower Pellets',
                'image' => 'goose-grower-pellets.jpg',
                'description' => 'Specialized pellets formulated for growing geese. Lower protein content (16%) suitable for slower-growing waterfowl. Contains proper calcium and phosphorus ratios to prevent leg problems. Formulated for geese from 3 weeks to maturity.',
                'price' => 155.00,
                'stock_quantity' => 140,
                'threshold_quantity' => 30,
            ],
            [
                'name' => 'Swan and Ornamental Waterfowl Feed',
                'image' => 'swan-ornamental-feed.jpg',
                'description' => 'A premium floating pellet feed for swans and ornamental waterfowl. Formulated to meet the nutritional needs of various waterfowl species. Pellets float to reduce waste and water contamination. Perfect for park and ornamental ponds.',
                'price' => 175.00,
                'stock_quantity' => 60,
                'threshold_quantity' => 15,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->command->info('Successfully seeded ' . count($products) . ' poultry feed products with categories.');
    }
}
