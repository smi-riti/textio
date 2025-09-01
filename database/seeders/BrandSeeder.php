<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'TextIO Premium',
                'slug' => 'textio-premium',
                'description' => 'Our premium line of custom printed products with superior quality materials and printing technology.',
                'logo' => 'https://ik.imagekit.io/textio/brands/textio-premium-logo.png',
                'is_active' => true,
                'meta_title' => 'TextIO Premium | High-Quality Custom Prints',
                'meta_description' => 'Discover TextIO Premium collection - the finest quality custom printed products with exceptional materials and printing.',
            ],
            [
                'name' => 'CustomPrint Pro',
                'slug' => 'customprint-pro',
                'description' => 'Professional-grade custom printing solutions for businesses and enterprises.',
                'logo' => 'https://ik.imagekit.io/textio/brands/customprint-pro-logo.png',
                'is_active' => true,
                'meta_title' => 'CustomPrint Pro | Professional Printing Solutions',
                'meta_description' => 'Professional custom printing services for businesses. Bulk orders, corporate branding, and enterprise solutions.',
            ],
            [
                'name' => 'EcoFriendly Prints',
                'slug' => 'ecofriendly-prints',
                'description' => 'Sustainable and eco-friendly custom printing using environmentally conscious materials and processes.',
                'logo' => 'https://ik.imagekit.io/textio/brands/ecofriendly-prints-logo.png',
                'is_active' => true,
                'meta_title' => 'EcoFriendly Prints | Sustainable Custom Printing',
                'meta_description' => 'Eco-conscious custom printing with sustainable materials. Reduce your carbon footprint while creating amazing designs.',
            ],
            [
                'name' => 'QuickPrint Express',
                'slug' => 'quickprint-express',
                'description' => 'Fast turnaround custom printing for urgent orders and last-minute needs.',
                'logo' => 'https://ik.imagekit.io/textio/brands/quickprint-express-logo.png',
                'is_active' => true,
                'meta_title' => 'QuickPrint Express | Fast Custom Printing',
                'meta_description' => 'Need it fast? QuickPrint Express delivers high-quality custom prints with lightning-fast turnaround times.',
            ],
            [
                'name' => 'ArtisticEdge',
                'slug' => 'artisticedge',
                'description' => 'Specialty printing for artistic designs, creative projects, and unique custom artwork.',
                'logo' => 'https://ik.imagekit.io/textio/brands/artisticedge-logo.png',
                'is_active' => true,
                'meta_title' => 'ArtisticEdge | Creative Custom Printing',
                'meta_description' => 'Unleash your creativity with ArtisticEdge. Specialty printing for artists, designers, and creative professionals.',
            ],
            [
                'name' => 'SportsTeam Gear',
                'slug' => 'sportsteam-gear',
                'description' => 'Custom printing specialized for sports teams, athletic wear, and team merchandise.',
                'logo' => 'https://ik.imagekit.io/textio/brands/sportsteam-gear-logo.png',
                'is_active' => true,
                'meta_title' => 'SportsTeam Gear | Team Merchandise & Athletic Wear',
                'meta_description' => 'Custom sports team merchandise and athletic wear. Perfect for teams, clubs, and sports organizations.',
            ],
            [
                'name' => 'EventSpecial',
                'slug' => 'eventspecial',
                'description' => 'Custom printing solutions for special events, weddings, parties, and celebrations.',
                'logo' => 'https://ik.imagekit.io/textio/brands/eventspecial-logo.png',
                'is_active' => true,
                'meta_title' => 'EventSpecial | Custom Event Merchandise',
                'meta_description' => 'Make your special events memorable with custom printed merchandise. Perfect for weddings, parties, and celebrations.',
            ],
            [
                'name' => 'CorporateBrand',
                'slug' => 'corporatebrand',
                'description' => 'Professional corporate branding solutions and custom business merchandise.',
                'logo' => 'https://ik.imagekit.io/textio/brands/corporatebrand-logo.png',
                'is_active' => true,
                'meta_title' => 'CorporateBrand | Professional Business Merchandise',
                'meta_description' => 'Elevate your corporate image with professional custom branded merchandise for your business.',
            ],
            [
                'name' => 'RetroVintage',
                'slug' => 'retrovintage',
                'description' => 'Vintage and retro-style custom prints with classic designs and nostalgic appeal.',
                'logo' => 'https://ik.imagekit.io/textio/brands/retrovintage-logo.png',
                'is_active' => true,
                'meta_title' => 'RetroVintage | Classic & Nostalgic Custom Prints',
                'meta_description' => 'Bring back the classics with RetroVintage custom prints. Perfect for vintage lovers and nostalgic designs.',
            ],
            [
                'name' => 'TechGeek',
                'slug' => 'techgeek',
                'description' => 'Custom printing for technology enthusiasts, programmers, and digital culture designs.',
                'logo' => 'https://ik.imagekit.io/textio/brands/techgeek-logo.png',
                'is_active' => true,
                'meta_title' => 'TechGeek | Custom Tech & Programming Merchandise',
                'meta_description' => 'Show your love for technology with TechGeek custom prints. Perfect for developers, gamers, and tech enthusiasts.',
            ],
            [
                'name' => 'NaturalVibes',
                'slug' => 'naturalvibes',
                'description' => 'Nature-inspired custom prints using organic materials and earth-friendly processes.',
                'logo' => 'https://ik.imagekit.io/textio/brands/naturalvibes-logo.png',
                'is_active' => true,
                'meta_title' => 'NaturalVibes | Organic & Nature-Inspired Prints',
                'meta_description' => 'Connect with nature through NaturalVibes organic custom prints. Sustainable materials meet beautiful designs.',
            ],
            [
                'name' => 'UrbanStyle',
                'slug' => 'urbanstyle',
                'description' => 'Street-smart custom printing with urban culture designs and modern aesthetics.',
                'logo' => 'https://ik.imagekit.io/textio/brands/urbanstyle-logo.png',
                'is_active' => true,
                'meta_title' => 'UrbanStyle | Modern Street Culture Prints',
                'meta_description' => 'Express your urban style with modern custom prints. Perfect for street culture and contemporary fashion.',
            ],
        ];

        foreach ($brands as $brand) {
            // Check if brand already exists
            if (!Brand::where('slug', $brand['slug'])->exists()) {
                Brand::create($brand);
            }
        }

        $this->command->info('Brands seeded successfully!');
    }
}
