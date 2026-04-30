<?php

namespace Database\Seeders;

use App\Models\DiscountProgram;
use App\Models\GalleryImage;
use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(
            ['slug' => 'about'],
            [
                'title'   => 'A premium destination for the modern phone enthusiast.',
                'eyebrow' => 'Our story',
                'intro'   => 'Founded with a single conviction: that buying or repairing a phone should feel as considered as the device itself.',
                'body'    => '<p>Fone Fitness began as a small workshop with one mandate — treat every device, every customer, and every repair with the precision of a flagship store. Years later, that conviction still shapes everything we do.</p><p>We curate authentic devices from the brands that define modern technology, and we service them with certified, manufacturer-grade parts. Same-day repairs, transparent pricing, and a 12-month warranty are standard, not exceptions.</p><p>Behind every transaction is a team of certified technicians who treat your device the way they treat their own. That commitment is why customers from across the region trust us with everything from upgrades to deep, board-level repairs.</p>',
                'meta_title'       => 'About Fone Fitness — Premium devices and certified repair',
                'meta_description' => 'Learn about Fone Fitness, the destination for authentic devices and precision repair, trusted across the region.',
                'is_published'     => true,
            ],
        );

        Page::updateOrCreate(
            ['slug' => 'contact'],
            [
                'title'            => 'Contact us.',
                'eyebrow'          => 'Reach the team',
                'intro'            => 'Questions about a device, an order, or a repair? Send a message and a real person will respond — usually within a few hours.',
                'meta_title'       => 'Contact Fone Fitness',
                'meta_description' => 'Get in touch with the Fone Fitness team for sales, support, or repair inquiries.',
                'is_published'     => true,
            ],
        );

        Page::updateOrCreate(
            ['slug' => 'discounts'],
            [
                'title'            => 'Special discounts.',
                'eyebrow'          => 'For our community',
                'intro'            => 'We value the dedication of those who serve our community. Show eligibility in store and we’ll take care of the rest.',
                'meta_title'       => 'Discounts at Fone Fitness',
                'meta_description' => 'Exclusive discount programs for NHS workers, key workers, school teachers, and Young Scot card holders.',
                'is_published'     => true,
            ],
        );

        $programs = [
            [
                'name'        => 'NHS workers',
                'eligibility' => 'Active NHS staff',
                'description' => 'In recognition of frontline service, NHS staff receive special pricing in store. Bring your NHS ID for verification.',
                'icon'        => 'heart',
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Key workers',
                'eligibility' => 'Police, fire, ambulance and council frontline staff',
                'description' => 'Dedicated savings for the people who keep our community moving. Government-issued key worker ID required.',
                'icon'        => 'shield',
                'sort_order'  => 2,
            ],
            [
                'name'        => 'School teachers',
                'eligibility' => 'Verified educators, primary through secondary',
                'description' => 'A small thank-you to the educators shaping the next generation. Show a school ID or pay-stub to redeem.',
                'icon'        => 'book',
                'sort_order'  => 3,
            ],
            [
                'name'        => 'Young Scot Card holders',
                'eligibility' => 'Young Scot members',
                'description' => 'Members of the Young Scot programme enjoy preferential pricing on repairs and accessories.',
                'icon'        => 'star',
                'sort_order'  => 4,
            ],
        ];

        foreach ($programs as $p) {
            DiscountProgram::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($p['name'])],
                $p + ['slug' => \Illuminate\Support\Str::slug($p['name']), 'is_active' => true],
            );
        }

        $gallery = [
            ['caption' => 'Storefront entrance', 'sort_order' => 1],
            ['caption' => 'Repair workshop',     'sort_order' => 2],
            ['caption' => 'Curated showcase',    'sort_order' => 3],
            ['caption' => 'Service desk',        'sort_order' => 4],
        ];

        foreach ($gallery as $g) {
            GalleryImage::updateOrCreate(
                ['group' => 'store', 'caption' => $g['caption']],
                $g + [
                    'group' => 'store',
                    'image_path' => 'gallery/store-'.$g['sort_order'].'.jpg',
                    'is_active' => true,
                ],
            );
        }

        $repair = [
            ['caption' => 'Board-level diagnostics',  'sort_order' => 1],
            ['caption' => 'Display calibration',       'sort_order' => 2],
            ['caption' => 'Battery replacement bench', 'sort_order' => 3],
            ['caption' => 'Component inspection',      'sort_order' => 4],
        ];

        foreach ($repair as $r) {
            GalleryImage::updateOrCreate(
                ['group' => 'repair', 'caption' => $r['caption']],
                $r + [
                    'group' => 'repair',
                    'image_path' => 'gallery/repair-'.$r['sort_order'].'.jpg',
                    'is_active' => true,
                ],
            );
        }

        SiteSetting::updateOrCreate(
            ['key' => 'company.hours'],
            [
                'group' => 'contact',
                'type'  => 'string',
                'value' => "Monday – Saturday\n9:00 am – 7:00 pm\n\nSunday: closed",
                'label' => 'Opening hours',
            ],
        );
    }
}
