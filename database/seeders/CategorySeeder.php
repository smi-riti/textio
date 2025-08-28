<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Parent Categories for Custom Printing Business
        $parentCategories = [
            [
                'title' => 'T-Shirts',
                'slug' => 't-shirts',
                'description' => 'Custom printed t-shirts for all occasions',
                'is_active' => true,
                'meta_title' => 'Custom T-Shirts | Print Your Design',
                'meta_description' => 'Create custom t-shirts with your own designs. High-quality printing on premium fabrics.',
            ],
            [
                'title' => 'Hoodies & Sweatshirts',
                'slug' => 'hoodies-sweatshirts',
                'description' => 'Cozy hoodies and sweatshirts with custom prints',
                'is_active' => true,
                'meta_title' => 'Custom Hoodies & Sweatshirts | TextIO',
                'meta_description' => 'Premium custom hoodies and sweatshirts. Perfect for teams, events, and personal style.',
            ],
            [
                'title' => 'Bags & Accessories',
                'slug' => 'bags-accessories',
                'description' => 'Custom printed bags, totes, and accessories',
                'is_active' => true,
                'meta_title' => 'Custom Bags & Accessories | Print On Demand',
                'meta_description' => 'Personalized bags, totes, backpacks and accessories with your custom designs.',
            ],
            [
                'title' => 'Mugs & Drinkware',
                'slug' => 'mugs-drinkware',
                'description' => 'Custom printed mugs, tumblers, and drinkware',
                'is_active' => true,
                'meta_title' => 'Custom Mugs & Drinkware | Personalized Gifts',
                'meta_description' => 'Create personalized mugs and drinkware with your photos and designs. Perfect gifts!',
            ],
            [
                'title' => 'Posters & Prints',
                'slug' => 'posters-prints',
                'description' => 'Custom posters, prints, and wall art',
                'is_active' => true,
                'meta_title' => 'Custom Posters & Prints | Wall Art',
                'meta_description' => 'High-quality custom posters and prints. Transform your photos into stunning wall art.',
            ],
        ];

        // Create parent categories and store their IDs
        $createdParents = [];
        foreach ($parentCategories as $category) {
            $existingCategory = Category::where('slug', $category['slug'])->first();
            if ($existingCategory) {
                $createdParents[$category['slug']] = $existingCategory->id;
            } else {
                $createdCategory = Category::create($category);
                $createdParents[$category['slug']] = $createdCategory->id;
            }
        }

        // Sub-categories
        $subCategories = [
            // T-Shirts subcategories
            [
                'parent_category_id' => $createdParents['t-shirts'],
                'title' => 'Men\'s T-Shirts',
                'slug' => 'mens-t-shirts',
                'description' => 'Custom printed t-shirts for men',
                'is_active' => true,
                'meta_title' => 'Men\'s Custom T-Shirts | TextIO',
                'meta_description' => 'Premium men\'s t-shirts with custom prints. Various sizes and colors available.',
            ],
            [
                'parent_category_id' => $createdParents['t-shirts'],
                'title' => 'Women\'s T-Shirts',
                'slug' => 'womens-t-shirts',
                'description' => 'Custom printed t-shirts for women',
                'is_active' => true,
                'meta_title' => 'Women\'s Custom T-Shirts | TextIO',
                'meta_description' => 'Stylish women\'s t-shirts with custom designs. Perfect fit and comfort.',
            ],
            [
                'parent_category_id' => $createdParents['t-shirts'],
                'title' => 'Kids T-Shirts',
                'slug' => 'kids-t-shirts',
                'description' => 'Custom printed t-shirts for children',
                'is_active' => true,
                'meta_title' => 'Kids Custom T-Shirts | Children\'s Apparel',
                'meta_description' => 'Fun and colorful custom t-shirts for kids. Safe, comfortable, and durable.',
            ],
            
            // Hoodies subcategories
            [
                'parent_category_id' => $createdParents['hoodies-sweatshirts'],
                'title' => 'Pullover Hoodies',
                'slug' => 'pullover-hoodies',
                'description' => 'Custom printed pullover hoodies',
                'is_active' => true,
                'meta_title' => 'Custom Pullover Hoodies | TextIO',
                'meta_description' => 'Cozy pullover hoodies with custom prints. Perfect for casual wear and teams.',
            ],
            [
                'parent_category_id' => $createdParents['hoodies-sweatshirts'],
                'title' => 'Zip-up Hoodies',
                'slug' => 'zip-up-hoodies',
                'description' => 'Custom printed zip-up hoodies and jackets',
                'is_active' => true,
                'meta_title' => 'Custom Zip-up Hoodies | TextIO',
                'meta_description' => 'Versatile zip-up hoodies with custom designs. Great for layering and style.',
            ],

            // Bags subcategories
            [
                'parent_category_id' => $createdParents['bags-accessories'],
                'title' => 'Tote Bags',
                'slug' => 'tote-bags',
                'description' => 'Custom printed tote bags for shopping and daily use',
                'is_active' => true,
                'meta_title' => 'Custom Tote Bags | Eco-Friendly Shopping Bags',
                'meta_description' => 'Durable custom tote bags perfect for shopping, work, and everyday use.',
            ],
            [
                'parent_category_id' => $createdParents['bags-accessories'],
                'title' => 'Backpacks',
                'slug' => 'backpacks',
                'description' => 'Custom printed backpacks for school and travel',
                'is_active' => true,
                'meta_title' => 'Custom Backpacks | Personalized School & Travel Bags',
                'meta_description' => 'High-quality custom backpacks for students and travelers. Durable and stylish.',
            ],

            // Mugs subcategories
            [
                'parent_category_id' => $createdParents['mugs-drinkware'],
                'title' => 'Ceramic Mugs',
                'slug' => 'ceramic-mugs',
                'description' => 'Custom printed ceramic mugs',
                'is_active' => true,
                'meta_title' => 'Custom Ceramic Mugs | Personalized Coffee Mugs',
                'meta_description' => 'Premium ceramic mugs with your custom designs. Dishwasher and microwave safe.',
            ],
            [
                'parent_category_id' => $createdParents['mugs-drinkware'],
                'title' => 'Travel Tumblers',
                'slug' => 'travel-tumblers',
                'description' => 'Custom printed travel tumblers and thermoses',
                'is_active' => true,
                'meta_title' => 'Custom Travel Tumblers | Insulated Drinkware',
                'meta_description' => 'Insulated travel tumblers with custom prints. Keep drinks hot or cold for hours.',
            ],

            // Posters subcategories
            [
                'parent_category_id' => $createdParents['posters-prints'],
                'title' => 'Photo Prints',
                'slug' => 'photo-prints',
                'description' => 'High-quality photo prints in various sizes',
                'is_active' => true,
                'meta_title' => 'Custom Photo Prints | Professional Quality',
                'meta_description' => 'Professional quality photo prints. Various sizes and finishes available.',
            ],
            [
                'parent_category_id' => $createdParents['posters-prints'],
                'title' => 'Canvas Prints',
                'slug' => 'canvas-prints',
                'description' => 'Custom canvas prints and wall art',
                'is_active' => true,
                'meta_title' => 'Custom Canvas Prints | Gallery Quality Wall Art',
                'meta_description' => 'Transform your photos into stunning canvas prints. Gallery quality and ready to hang.',
            ],
        ];

        // Create subcategories
        foreach ($subCategories as $category) {
            // Check if subcategory already exists
            if (!Category::where('slug', $category['slug'])->exists()) {
                Category::create($category);
            }
        }

        $this->command->info('Categories seeded successfully!');
    }
}
