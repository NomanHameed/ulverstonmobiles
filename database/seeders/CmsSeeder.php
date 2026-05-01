<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\HeroSlide;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'eyebrow'             => 'New · iPhone 16 Pro',
                'headline'            => 'Titanium. So light. So pro.',
                'subheadline'         => 'A camera built for cinema. A chip built for the future of intelligence.',
                'image_path'          => 'hero/iphone-16-pro.jpg',
                'primary_cta_label'   => 'Buy now',
                'primary_cta_url'     => '/products/iphone-16-pro',
                'secondary_cta_label' => 'Learn more',
                'secondary_cta_url'   => '/products/iphone-16-pro#features',
                'theme'               => 'dark',
                'sort_order'          => 1,
            ],
            [
                'eyebrow'             => 'Repair · Same-day',
                'headline'            => 'Precision repairs. Pristine results.',
                'subheadline'         => 'Certified technicians. OEM-grade parts. Most repairs in under 60 minutes.',
                'image_path'          => 'hero/repair-bench.jpg',
                'primary_cta_label'   => 'Book a repair',
                'primary_cta_url'     => '/repair',
                'secondary_cta_label' => 'View pricing',
                'secondary_cta_url'   => '/repair#pricing',
                'theme'               => 'light',
                'sort_order'          => 2,
            ],
            [
                'eyebrow'             => 'Galaxy AI',
                'headline'            => 'Intelligence, redefined.',
                'subheadline'         => 'The Galaxy S24 Ultra brings on-device AI to your everyday.',
                'image_path'          => 'hero/galaxy-s24.jpg',
                'primary_cta_label'   => 'Discover Galaxy',
                'primary_cta_url'     => '/products/galaxy-s24-ultra',
                'theme'               => 'dark',
                'sort_order'          => 3,
            ],
        ];

        foreach ($slides as $s) {
            HeroSlide::updateOrCreate(
                ['headline' => $s['headline']],
                $s + ['is_active' => true],
            );
        }

        Announcement::updateOrCreate(
            ['message' => 'Free contactless delivery on orders over $99'],
            [
                'message'        => 'Free contactless delivery on orders over $99',
                'link_url'       => '/shipping',
                'link_label'     => 'Learn more',
                'is_active'      => true,
                'is_dismissible' => true,
            ],
        );

        $settings = [
            ['key' => 'company.email',        'group' => 'contact', 'type' => 'string', 'value' => 'hello@ulverstonmobile.co.uk', 'label' => 'Contact email'],
            ['key' => 'company.phone',        'group' => 'contact', 'type' => 'string', 'value' => '+1 (555) 010-0199',     'label' => 'Phone'],
            ['key' => 'company.whatsapp',     'group' => 'contact', 'type' => 'string', 'value' => '+15550100199',          'label' => 'WhatsApp number (digits only)'],
            ['key' => 'company.address',      'group' => 'contact', 'type' => 'string', 'value' => '142 Pine Street, Suite 4', 'label' => 'Storefront address'],
            ['key' => 'social.instagram',     'group' => 'social',  'type' => 'string', 'value' => 'https://instagram.com/ulverstonmobile'],
            ['key' => 'social.tiktok',        'group' => 'social',  'type' => 'string', 'value' => 'https://tiktok.com/@ulverstonmobile'],
            ['key' => 'seo.site_title',       'group' => 'seo',     'type' => 'string', 'value' => 'Ulverston Mobile — Repair, restore, replace'],
            ['key' => 'seo.site_description', 'group' => 'seo',     'type' => 'string', 'value' => 'Precision phone repair from certified technicians, plus authentic devices for your next upgrade. Same-day service. Genuine parts. 12-month warranty.'],
        ];

        foreach ($settings as $s) {
            SiteSetting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}
