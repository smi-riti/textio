<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductHighlist;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories and brands for relationships
        $categories = Category::where('parent_category_id', '!=', null)->get(); // Only subcategories
        $brands = Brand::all();

        if ($categories->isEmpty() || $brands->isEmpty()) {
            $this->command->error('Please run CategorySeeder and BrandSeeder first!');
            return;
        }

        $products = [
            // T-Shirt Products
            [
                'name' => 'Classic Cotton T-Shirt',
                'description' => 'Premium 100% cotton t-shirt perfect for custom printing. Soft, comfortable, and durable with excellent print quality. Available in multiple colors and sizes. Perfect for personal designs, team uniforms, or promotional merchandise.',
                'price' => 599.00,
                'discount_price' => 449.00,
                'quantity' => 150,
                'sku' => 'TSHRT-CC-001',
                'category_id' => $categories->where('slug', 'mens-t-shirts')->first()->id ?? $categories->random()->id,
                'brand_id' => $brands->where('slug', 'textio-premium')->first()->id ?? $brands->random()->id,
                'status' => true,
                'is_customizable' => true,
                'featured' => true,
                'meta_title' => 'Classic Cotton T-Shirt | Custom Print Ready',
                'meta_description' => 'High-quality cotton t-shirt perfect for custom printing. Comfortable, durable, and available in multiple colors.',
                'highlights' => [
                    '100% Premium Cotton',
                    'Pre-shrunk fabric',
                    'Excellent print adhesion',
                    'Machine washable',
                    'Available in 15+ colors'
                ]
            ],
            [
                'name' => 'Women\'s Fitted Tee',
                'description' => 'Stylish fitted t-shirt designed specifically for women. Made from soft cotton blend with a flattering cut that maintains its shape after multiple washes. Ideal for custom designs, personal artwork, or business branding.',
                'price' => 649.00,
                'discount_price' => 499.00,
                'quantity' => 120,
                'sku' => 'TSHRT-WF-002',
                'category_id' => $categories->where('slug', 'womens-t-shirts')->first()->id ?? $categories->random()->id,
                'brand_id' => $brands->where('slug', 'artisticedge')->first()->id ?? $brands->random()->id,
                'status' => true,
                'is_customizable' => true,
                'featured' => true,
                'meta_title' => 'Women\'s Fitted Custom T-Shirt | Flattering Fit',
                'meta_description' => 'Perfectly fitted women\'s t-shirt for custom printing. Soft cotton blend with flattering cut.',
                'highlights' => [
                    'Flattering fitted design',
                    'Soft cotton blend',
                    'Retains shape after washing',
                    'Premium print quality',
                    'Sizes XS to 3XL'
                ]
            ],
            [
                'name' => 'Kids Fun Character Tee',
                'description' => 'Adorable t-shirt designed for children with fun and colorful designs. Made from hypoallergenic, soft cotton that\'s gentle on sensitive skin. Perfect for custom prints, cartoon characters, or educational designs.',
                'price' => 399.00,
                'discount_price' => 299.00,
                'quantity' => 200,
                'sku' => 'TSHRT-KF-003',
                'category_id' => $categories->where('slug', 'kids-t-shirts')->first()->id ?? $categories->random()->id,
                'brand_id' => $brands->where('slug', 'ecofriendly-prints')->first()->id ?? $brands->random()->id,
                'status' => true,
                'is_customizable' => true,
                'featured' => false,
                'meta_title' => 'Kids Custom T-Shirt | Fun & Safe',
                'meta_description' => 'Safe, comfortable kids t-shirt perfect for custom prints. Hypoallergenic and fun designs.',
                'highlights' => [
                    'Hypoallergenic fabric',
                    'Child-safe materials',
                    'Vibrant print colors',
                    'Easy care instructions',
                    'Sizes 2T to 14'
                ]
            ],

            // Hoodie Products
            [
                'name' => 'Premium Pullover Hoodie',
                'description' => 'Cozy and warm pullover hoodie made from premium cotton-polyester blend. Features a spacious front pocket and adjustable drawstring hood. Perfect for custom prints, team logos, or personal designs. Ideal for casual wear and team merchandise.',
                'price' => 1299.00,
                'discount_price' => 999.00,
                'quantity' => 80,
                'sku' => 'HOOD-PP-004',
                'category_id' => $categories->where('slug', 'pullover-hoodies')->first()->id ?? $categories->random()->id,
                'brand_id' => $brands->where('slug', 'sportsteam-gear')->first()->id ?? $brands->random()->id,
                'status' => true,
                'is_customizable' => true,
                'featured' => true,
                'meta_title' => 'Premium Pullover Hoodie | Custom Team Wear',
                'meta_description' => 'Cozy premium hoodie perfect for custom team logos and designs. High-quality cotton blend.',
                'highlights' => [
                    'Cotton-polyester blend',
                    'Spacious front pocket',
                    'Adjustable drawstring',
                    'Large print area',
                    'Machine washable'
                ]
            ],
            [
                'name' => 'Zip-Up Hoodie Jacket',
                'description' => 'Versatile zip-up hoodie that doubles as a light jacket. Made from durable materials with reinforced seams. Features two side pockets and a full-length zipper. Excellent for custom branding, corporate merchandise, or personal style.',
                'price' => 1499.00,
                'discount_price' => 1199.00,
                'quantity' => 60,
                'sku' => 'HOOD-ZU-005',
                'category_id' => $categories->where('slug', 'zip-up-hoodies')->first()->id ?? $categories->random()->id,
                'brand_id' => $brands->where('slug', 'corporatebrand')->first()->id ?? $brands->random()->id,
                'status' => true,
                'is_customizable' => true,
                'featured' => false,
                'meta_title' => 'Zip-Up Hoodie Jacket | Corporate Branding',
                'meta_description' => 'Professional zip-up hoodie perfect for corporate branding and custom designs.',
                'highlights' => [
                    'Full-length YKK zipper',
                    'Two side pockets',
                    'Reinforced seams',
                    'Corporate quality',
                    'Sizes S to 3XL'
                ]
            ],

            // Bag Products
            [
                'name' => 'Eco-Friendly Tote Bag',
                'description' => 'Sustainable tote bag made from 100% organic cotton. Perfect for grocery shopping, daily use, or promotional giveaways. Large print area for custom designs, logos, or environmental messages. Durable handles and reinforced stitching.',
                'price' => 299.00,
                'discount_price' => 199.00,
                'quantity' => 300,
                'sku' => 'BAG-ECO-006',
                'category_id' => $categories->where('slug', 'tote-bags')->first()->id ?? $categories->random()->id,
                'brand_id' => $brands->where('slug', 'ecofriendly-prints')->first()->id ?? $brands->random()->id,
                'status' => true,
                'is_customizable' => true,
                'featured' => true,
                'meta_title' => 'Eco-Friendly Custom Tote Bag | Sustainable Shopping',
                'meta_description' => 'Organic cotton tote bag perfect for custom prints and eco-conscious shopping.',
                'highlights' => [
                    '100% Organic cotton',
                    'Eco-friendly materials',
                    'Large print area',
                    'Reinforced handles',
                    'Machine washable'
                ]
            ],
            [
                'name' => 'Student Backpack',
                'description' => 'Durable backpack designed for students and daily commuters. Multiple compartments including laptop sleeve and organization pockets. Water-resistant material with comfortable padded straps. Great for school logos, team designs, or personal customization.',
                'price' => 1999.00,
                'discount_price' => 1599.00,
                'quantity' => 50,
                'sku' => 'BAG-STU-007',
                'category_id' => $categories->where('slug', 'backpacks')->first()->id ?? $categories->random()->id,
                'brand_id' => $brands->where('slug', 'techgeek')->first()->id ?? $brands->random()->id,
                'status' => true,
                'is_customizable' => true,
                'featured' => false,
                'meta_title' => 'Custom Student Backpack | School & Tech Ready',
                'meta_description' => 'Durable student backpack with laptop sleeve. Perfect for school logos and custom designs.',
                'highlights' => [
                    'Laptop compartment (15")',
                    'Water-resistant material',
                    'Padded shoulder straps',
                    'Multiple pockets',
                    'Durable zippers'
                ]
            ],

            // Mug Products
            [
                'name' => 'Classic White Ceramic Mug',
                'description' => 'Premium ceramic mug perfect for photo prints, logos, and custom designs. Dishwasher and microwave safe with excellent print quality. Standard 11oz capacity ideal for coffee, tea, or hot chocolate. Professional grade ceramic construction.',
                'price' => 249.00,
                'discount_price' => 179.00,
                'quantity' => 500,
                'sku' => 'MUG-CER-008',
                'category_id' => $categories->where('slug', 'ceramic-mugs')->first()->id ?? $categories->random()->id,
                'brand_id' => $brands->where('slug', 'customprint-pro')->first()->id ?? $brands->random()->id,
                'status' => true,
                'is_customizable' => true,
                'featured' => true,
                'meta_title' => 'Custom Ceramic Mug | Photo Prints & Logos',
                'meta_description' => 'Premium ceramic mug perfect for custom photo prints and business logos. Dishwasher safe.',
                'highlights' => [
                    '11oz capacity',
                    'Dishwasher safe',
                    'Microwave safe',
                    'Premium ceramic',
                    'Excellent print quality'
                ]
            ],
            [
                'name' => 'Insulated Travel Tumbler',
                'description' => 'Double-wall insulated travel tumbler that keeps drinks hot for 6 hours or cold for 12 hours. Leak-proof lid with sliding closure. Perfect for custom designs, corporate gifts, or personal use. Premium stainless steel construction.',
                'price' => 799.00,
                'discount_price' => 599.00,
                'quantity' => 100,
                'sku' => 'TUM-INS-009',
                'category_id' => $categories->where('slug', 'travel-tumblers')->first()->id ?? $categories->random()->id,
                'brand_id' => $brands->where('slug', 'corporatebrand')->first()->id ?? $brands->random()->id,
                'status' => true,
                'is_customizable' => true,
                'featured' => false,
                'meta_title' => 'Insulated Travel Tumbler | Custom Corporate Gifts',
                'meta_description' => 'Premium insulated tumbler perfect for corporate branding. Keeps drinks hot or cold.',
                'highlights' => [
                    'Double-wall insulation',
                    'Leak-proof lid',
                    'Hot 6hrs / Cold 12hrs',
                    'Stainless steel',
                    '20oz capacity'
                ]
            ],

            // Poster/Print Products
            [
                'name' => 'Premium Photo Print',
                'description' => 'Professional quality photo print on premium paper. Available in multiple sizes from 4x6 to 24x36. Perfect for personal photos, artwork reproduction, or professional photography. Fade-resistant inks ensure long-lasting vibrant colors.',
                'price' => 199.00,
                'discount_price' => 149.00,
                'quantity' => 1000,
                'sku' => 'PRT-PHO-010',
                'category_id' => $categories->where('slug', 'photo-prints')->first()->id ?? $categories->random()->id,
                'brand_id' => $brands->where('slug', 'artisticedge')->first()->id ?? $brands->random()->id,
                'status' => true,
                'is_customizable' => true,
                'featured' => true,
                'meta_title' => 'Premium Photo Prints | Professional Quality',
                'meta_description' => 'Professional photo prints with fade-resistant inks. Multiple sizes available.',
                'highlights' => [
                    'Premium photo paper',
                    'Fade-resistant inks',
                    'Multiple sizes available',
                    'Professional quality',
                    'Quick turnaround'
                ]
            ],
            [
                'name' => 'Gallery Canvas Print',
                'description' => 'Museum-quality canvas print stretched on wooden frame. Ready to hang with included hardware. Perfect for transforming photos into wall art. High-resolution printing on premium canvas material. Available in various sizes for any space.',
                'price' => 1299.00,
                'discount_price' => 999.00,
                'quantity' => 75,
                'sku' => 'CVS-GAL-011',
                'category_id' => $categories->where('slug', 'canvas-prints')->first()->id ?? $categories->random()->id,
                'brand_id' => $brands->where('slug', 'artisticedge')->first()->id ?? $brands->random()->id,
                'status' => true,
                'is_customizable' => true,
                'featured' => true,
                'meta_title' => 'Gallery Canvas Print | Museum Quality Wall Art',
                'meta_description' => 'Transform photos into stunning canvas art. Museum quality with wooden frame.',
                'highlights' => [
                    'Museum-quality canvas',
                    'Wooden frame included',
                    'Ready to hang',
                    'High-resolution printing',
                    'Multiple sizes available'
                ]
            ],

            // Additional Products for variety
            [
                'name' => 'Vintage Style Poster',
                'description' => 'Retro-inspired poster perfect for vintage designs and classic artwork. High-quality paper with matte finish. Ideal for vintage advertisements, retro designs, or nostalgic artwork. Available in standard poster sizes.',
                'price' => 399.00,
                'discount_price' => null,
                'quantity' => 200,
                'sku' => 'PST-VIN-012',
                'category_id' => $categories->where('slug', 'posters-prints')->first()->id ?? $categories->random()->id,
                'brand_id' => $brands->where('slug', 'retrovintage')->first()->id ?? $brands->random()->id,
                'status' => true,
                'is_customizable' => true,
                'featured' => false,
                'meta_title' => 'Vintage Style Custom Poster | Retro Designs',
                'meta_description' => 'Create vintage-style posters with retro appeal. Perfect for classic and nostalgic designs.',
                'highlights' => [
                    'Vintage paper texture',
                    'Matte finish',
                    'Retro aesthetic',
                    'Standard poster sizes',
                    'Quick printing'
                ]
            ],
        ];

        // Create products with highlights
        foreach ($products as $productData) {
            // Check if product already exists
            if (Product::where('sku', $productData['sku'])->exists()) {
                continue; // Skip if already exists
            }

            // Extract highlights before creating product
            $highlights = $productData['highlights'] ?? [];
            unset($productData['highlights']);

            // Create the product
            $product = Product::create($productData);

            // Add highlights if they exist
            if (!empty($highlights)) {
                foreach ($highlights as $highlight) {
                    ProductHighlist::create([
                        'product_id' => $product->id,
                        'highlights' => $highlight,
                    ]);
                }
            }

            // Add sample images with ImageKit URLs
            $imageUrls = [
                'https://ik.imagekit.io/saloni/textio/category/category-t-shirts-1756724949_GhFp375Cx.webp',
                'https://ik.imagekit.io/saloni/textio/category/category-t-shirts-1756724949_GhFp375Cx.webp',
                'https://ik.imagekit.io/saloni/textio/category/category-t-shirts-1756724949_GhFp375Cx.webp',
            ];

            foreach ($imageUrls as $index => $imageUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imageUrl,
                    'is_primary' => $index === 0, // First image is primary
                    'image_file_id' => 'demo_file_' . $product->id . '_' . $index,
                ]);
            }

            // Add sample variants for customizable products
            if ($product->is_customizable) {
                $this->createSampleVariants($product);
            }
        }

        $this->command->info('Products seeded successfully with ' . count($products) . ' products!');
    }

    /**
     * Create sample variants for a product
     */
    private function createSampleVariants($product)
    {
        $variants = [];

        // Different variant patterns based on product type
        if (str_contains($product->name, 'T-Shirt') || str_contains($product->name, 'Tee')) {
            // T-Shirt variants: Size and Color combinations
            $sizes = ['Small', 'Medium', 'Large', 'X-Large'];
            $colors = ['Black', 'White', 'Navy Blue', 'Red'];

            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    $variants[] = [
                        'product_id' => $product->id,
                        'variant_type' => 'Size & Color',
                        'variant_name' => $size . ' - ' . $color,
                        'price' => $product->discount_price + rand(0, 100),
                        'stock' => rand(5, 25),
                        'sku' => $product->sku . '-' . strtoupper(substr($size, 0, 1)) . strtoupper(substr($color, 0, 1)),
                        'variant_image' => 'https://ik.imagekit.io/saloni/textio/category/category-t-shirts-1756724949_GhFp375Cx.webp',
                    ];
                }
            }
        } elseif (str_contains($product->name, 'Hoodie')) {
            // Hoodie variants: Size variations
            $sizes = ['Small', 'Medium', 'Large', 'X-Large', 'XX-Large'];
            
            foreach ($sizes as $size) {
                $variants[] = [
                    'product_id' => $product->id,
                    'variant_type' => 'Size',
                    'variant_name' => $size,
                    'price' => $product->discount_price + rand(0, 200),
                    'stock' => rand(3, 15),
                    'sku' => $product->sku . '-' . strtoupper(substr($size, 0, 1)),
                    'variant_image' => 'https://ik.imagekit.io/saloni/textio/category/category-t-shirts-1756724949_GhFp375Cx.webp',
                ];
            }
        } elseif (str_contains($product->name, 'Mug')) {
            // Mug variants: Different designs/themes
            $designs = ['Classic Design', 'Modern Design', 'Vintage Design', 'Minimalist Design'];
            
            foreach ($designs as $design) {
                $variants[] = [
                    'product_id' => $product->id,
                    'variant_type' => 'Design',
                    'variant_name' => $design,
                    'price' => $product->discount_price + rand(-20, 50),
                    'stock' => rand(10, 50),
                    'sku' => $product->sku . '-' . strtoupper(substr($design, 0, 3)),
                    'variant_image' => 'https://ik.imagekit.io/saloni/textio/category/category-t-shirts-1756724949_GhFp375Cx.webp',
                ];
            }
        } elseif (str_contains($product->name, 'Bag')) {
            // Bag variants: Color variations
            $colors = ['Black', 'Brown', 'Navy', 'Gray', 'Beige'];
            
            foreach ($colors as $color) {
                $variants[] = [
                    'product_id' => $product->id,
                    'variant_type' => 'Color',
                    'variant_name' => $color,
                    'price' => $product->discount_price + rand(-50, 100),
                    'stock' => rand(5, 20),
                    'sku' => $product->sku . '-' . strtoupper(substr($color, 0, 3)),
                    'variant_image' => 'https://ik.imagekit.io/saloni/textio/category/category-t-shirts-1756724949_GhFp375Cx.webp',
                ];
            }
        } elseif (str_contains($product->name, 'Print') || str_contains($product->name, 'Poster')) {
            // Print variants: Size options
            $sizes = ['8x10', '11x14', '16x20', '20x24', '24x36'];
            
            foreach ($sizes as $size) {
                $variants[] = [
                    'product_id' => $product->id,
                    'variant_type' => 'Size',
                    'variant_name' => $size . ' inches',
                    'price' => $product->discount_price + (intval(substr($size, 0, 2)) * 5),
                    'stock' => rand(20, 100),
                    'sku' => $product->sku . '-' . str_replace('x', 'X', $size),
                    'variant_image' => 'https://ik.imagekit.io/saloni/textio/category/category-t-shirts-1756724949_GhFp375Cx.webp',
                ];
            }
        } else {
            // Default variants for other products
            $options = ['Option 1', 'Option 2', 'Option 3'];
            
            foreach ($options as $option) {
                $variants[] = [
                    'product_id' => $product->id,
                    'variant_type' => 'Option',
                    'variant_name' => $option,
                    'price' => $product->discount_price + rand(0, 100),
                    'stock' => rand(5, 30),
                    'sku' => $product->sku . '-OPT' . substr($option, -1),
                    'variant_image' => 'https://ik.imagekit.io/saloni/textio/category/category-t-shirts-1756724949_GhFp375Cx.webp',
                ];
            }
        }

        // Limit variants to prevent too many (max 8 per product for demo)
        $variants = array_slice($variants, 0, 8);

        // Create variants
        foreach ($variants as $variantData) {
            ProductVariant::create($variantData);
        }
    }
}
