<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Apple',   'is_featured' => true,  'sort_order' => 1],
            ['name' => 'Samsung', 'is_featured' => true,  'sort_order' => 2],
            ['name' => 'Google',  'is_featured' => true,  'sort_order' => 3],
            ['name' => 'Sony',    'is_featured' => false, 'sort_order' => 4],
            ['name' => 'Bose',    'is_featured' => false, 'sort_order' => 5],
        ];

        foreach ($brands as $b) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($b['name'])],
                $b + ['slug' => Str::slug($b['name'])],
            );
        }

        $categories = [
            ['name' => 'Smartphones',  'icon' => 'smartphone',  'is_featured' => true, 'sort_order' => 1],
            ['name' => 'Audio',        'icon' => 'headphones',  'is_featured' => true, 'sort_order' => 2],
            ['name' => 'Tablets',      'icon' => 'tablet',      'is_featured' => true, 'sort_order' => 3],
            ['name' => 'Wearables',    'icon' => 'watch',       'is_featured' => true, 'sort_order' => 4],
            ['name' => 'Accessories',  'icon' => 'cable',       'is_featured' => false, 'sort_order' => 5],
        ];

        foreach ($categories as $c) {
            Category::updateOrCreate(
                ['slug' => Str::slug($c['name'])],
                $c + ['slug' => Str::slug($c['name']), 'is_active' => true],
            );
        }

        $apple   = Brand::where('slug', 'apple')->first();
        $samsung = Brand::where('slug', 'samsung')->first();
        $google  = Brand::where('slug', 'google')->first();

        $phones    = Category::where('slug', 'smartphones')->first();
        $audio     = Category::where('slug', 'audio')->first();
        $wearables = Category::where('slug', 'wearables')->first();

        $products = [
            [
                'brand_id'     => $apple->id,
                'category_id'  => $phones->id,
                'name'         => 'iPhone 16 Pro',
                'short_description' => 'Titanium. A18 Pro. Camera Control. Built for Apple Intelligence.',
                'description'  => 'A pro-grade titanium body, the cinematic 48MP Fusion camera and the all-new Camera Control button — engineered for the way you create.',
                'base_price'   => 1099.00,
                'sale_price'   => null,
                'is_featured'  => true,
                'is_published' => true,
                'features'     => [
                    ['title' => 'Titanium design', 'description' => 'Aerospace-grade titanium with a refined micro-blasted finish.', 'icon' => 'shield'],
                    ['title' => 'A18 Pro chip',     'description' => 'A leap in performance for pro workflows and Apple Intelligence.',     'icon' => 'cpu'],
                    ['title' => 'Camera Control',   'description' => 'A new tactile button for instant capture and adjustment.',           'icon' => 'aperture'],
                ],
                'variants' => [
                    ['color' => 'Natural Titanium', 'color_hex' => '#a8a29e', 'storage' => '256GB', 'is_default' => true,  'stock' => 12],
                    ['color' => 'Natural Titanium', 'color_hex' => '#a8a29e', 'storage' => '512GB', 'price_modifier' => 200, 'stock' => 8],
                    ['color' => 'Black Titanium',   'color_hex' => '#1f2937', 'storage' => '256GB', 'stock' => 6],
                    ['color' => 'White Titanium',   'color_hex' => '#f5f5f4', 'storage' => '256GB', 'stock' => 4],
                ],
            ],
            [
                'brand_id'     => $samsung->id,
                'category_id'  => $phones->id,
                'name'         => 'Galaxy S24 Ultra',
                'short_description' => 'Galaxy AI is here. Built-in S Pen. 200MP Quad Tele.',
                'description'  => 'A new era of mobile intelligence with on-device AI, a 200MP Quad Tele camera system, and a Titanium frame for premium durability.',
                'base_price'   => 1299.00,
                'sale_price'   => 1199.00,
                'is_featured'  => true,
                'is_published' => true,
                'features'     => [
                    ['title' => 'Galaxy AI',     'description' => 'On-device intelligence for Live Translate, Note Assist and Circle to Search.', 'icon' => 'sparkles'],
                    ['title' => '200MP Quad Tele','description' => 'A pro-grade sensor system for low-light and long-range capture.',             'icon' => 'camera'],
                    ['title' => 'Built-in S Pen', 'description' => 'Ultra-low latency stylus for precision creation.',                              'icon' => 'pen'],
                ],
                'variants' => [
                    ['color' => 'Titanium Gray',   'color_hex' => '#52525b', 'storage' => '256GB', 'is_default' => true, 'stock' => 9],
                    ['color' => 'Titanium Black',  'color_hex' => '#0a0a0a', 'storage' => '512GB', 'price_modifier' => 120, 'stock' => 5],
                    ['color' => 'Titanium Violet', 'color_hex' => '#a78bfa', 'storage' => '256GB', 'stock' => 3],
                ],
            ],
            [
                'brand_id'     => $google->id,
                'category_id'  => $phones->id,
                'name'         => 'Pixel 9 Pro',
                'short_description' => 'Google AI. Pro-grade camera. Tensor G4.',
                'description'  => 'The smartest Pixel yet — Google AI built in, a triple camera system, and the Tensor G4 chip for next-level photography.',
                'base_price'   => 999.00,
                'is_featured'  => true,
                'is_published' => true,
                'features'     => [
                    ['title' => 'Tensor G4',     'description' => 'A custom Google chip purpose-built for on-device AI.', 'icon' => 'cpu'],
                    ['title' => 'Magic Editor',  'description' => 'Reimagine any photo with generative AI.',              'icon' => 'wand'],
                ],
                'variants' => [
                    ['color' => 'Obsidian',     'color_hex' => '#0a0a0a', 'storage' => '128GB', 'is_default' => true, 'stock' => 10],
                    ['color' => 'Porcelain',    'color_hex' => '#f5f5f4', 'storage' => '256GB', 'price_modifier' => 100, 'stock' => 7],
                    ['color' => 'Hazel',        'color_hex' => '#78716c', 'storage' => '256GB', 'price_modifier' => 100, 'stock' => 4],
                ],
            ],
            [
                'brand_id'     => $apple->id,
                'category_id'  => $audio->id,
                'name'         => 'AirPods Pro 2',
                'short_description' => 'Active Noise Cancellation. Adaptive Audio. USB-C.',
                'description'  => 'A breakthrough in personal audio with Adaptive Audio, Conversation Awareness, and a refined fit.',
                'base_price'   => 249.00,
                'is_featured'  => true,
                'is_published' => true,
                'features'     => [
                    ['title' => 'Adaptive Audio',  'description' => 'Seamlessly blends noise control modes around you.', 'icon' => 'waves'],
                    ['title' => 'USB-C charging',  'description' => 'A unified, faster charging case.',                  'icon' => 'plug'],
                ],
                'variants' => [
                    ['color' => 'White', 'color_hex' => '#ffffff', 'is_default' => true, 'stock' => 24],
                ],
            ],
            [
                'brand_id'     => $apple->id,
                'category_id'  => $wearables->id,
                'name'         => 'Apple Watch Ultra 2',
                'short_description' => 'Built for adventure. Brightest display ever.',
                'description'  => 'A rugged titanium case, 3000-nit display and dual-frequency GPS for the most demanding pursuits.',
                'base_price'   => 799.00,
                'is_featured'  => false,
                'is_published' => true,
                'features'     => [
                    ['title' => 'Titanium case',     'description' => '49mm aerospace titanium with sapphire crystal.', 'icon' => 'shield'],
                    ['title' => 'Action button',     'description' => 'Programmable button for one-press control.',     'icon' => 'circle-dot'],
                ],
                'variants' => [
                    ['color' => 'Natural Titanium', 'color_hex' => '#a8a29e', 'storage' => '49mm', 'is_default' => true, 'stock' => 6],
                ],
            ],
        ];

        foreach ($products as $data) {
            $features = $data['features'] ?? [];
            $variants = $data['variants'] ?? [];
            unset($data['features'], $data['variants']);

            $data['slug'] = Str::slug($data['name']);
            $data['published_at'] = now();

            $product = Product::updateOrCreate(['slug' => $data['slug']], $data);

            $product->features()->delete();
            foreach ($features as $i => $f) {
                ProductFeature::create($f + ['product_id' => $product->id, 'sort_order' => $i]);
            }

            $product->variants()->delete();
            foreach ($variants as $v) {
                ProductVariant::create($v + ['product_id' => $product->id]);
            }

            if ($product->images()->count() === 0) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path'       => "products/{$product->slug}.jpg",
                    'alt'        => $product->name,
                    'is_primary' => true,
                ]);
            }
        }
    }
}
