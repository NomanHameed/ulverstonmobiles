<?php

namespace Database\Seeders;

use App\Models\RepairBrand;
use App\Models\RepairIssue;
use App\Models\RepairModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RepairSeeder extends Seeder
{
    public function run(): void
    {
        $brandModels = [
            'Apple' => [
                'iPhone 16 Pro', 'iPhone 16', 'iPhone 15 Pro', 'iPhone 15',
                'iPhone 14 Pro', 'iPhone 14', 'iPhone 13', 'iPhone 12',
            ],
            'Samsung' => [
                'Galaxy S24 Ultra', 'Galaxy S24', 'Galaxy S23 Ultra', 'Galaxy S23',
                'Galaxy Z Fold5', 'Galaxy Z Flip5', 'Galaxy Note 20',
            ],
            'Google' => [
                'Pixel 9 Pro', 'Pixel 9', 'Pixel 8 Pro', 'Pixel 8', 'Pixel 7 Pro',
            ],
            'OnePlus' => [
                'OnePlus 12', 'OnePlus 11', 'OnePlus 10 Pro',
            ],
            'Xiaomi' => [
                'Xiaomi 14 Ultra', 'Xiaomi 14', 'Xiaomi 13 Pro',
            ],
        ];

        $sortBrand = 1;
        foreach ($brandModels as $brandName => $models) {
            $brand = RepairBrand::updateOrCreate(
                ['slug' => Str::slug($brandName)],
                ['name' => $brandName, 'sort_order' => $sortBrand++, 'is_active' => true],
            );

            $sortModel = 1;
            foreach ($models as $modelName) {
                RepairModel::updateOrCreate(
                    ['repair_brand_id' => $brand->id, 'slug' => Str::slug($modelName)],
                    ['name' => $modelName, 'sort_order' => $sortModel++, 'is_active' => true],
                );
            }
        }

        $issues = [
            ['name' => 'Screen Replacement',   'icon' => 'screen',   'base_price' =>  149, 'estimated_minutes' =>  60, 'description' => 'Cracked or unresponsive display? We replace with OEM-grade panels.'],
            ['name' => 'Battery Replacement',  'icon' => 'battery',  'base_price' =>   79, 'estimated_minutes' =>  45, 'description' => 'Restore all-day battery life with a genuine-grade cell.'],
            ['name' => 'Charging Port',        'icon' => 'plug',     'base_price' =>   69, 'estimated_minutes' =>  60, 'description' => 'Won’t charge or detect cable? We re-flow or replace the port.'],
            ['name' => 'Back Glass',           'icon' => 'glass',    'base_price' =>   89, 'estimated_minutes' =>  75, 'description' => 'Restore the rear panel with precision laser-cut glass.'],
            ['name' => 'Camera Repair',        'icon' => 'camera',   'base_price' =>   99, 'estimated_minutes' =>  60, 'description' => 'Front or rear camera replacement with calibration.'],
            ['name' => 'Speaker / Microphone', 'icon' => 'speaker',  'base_price' =>   59, 'estimated_minutes' =>  45, 'description' => 'Muffled audio or no mic input — module-level fix.'],
            ['name' => 'Water Damage',         'icon' => 'droplet',  'base_price' =>  120, 'estimated_minutes' => 180, 'description' => 'Diagnostic, ultrasonic clean and component-level repair.'],
            ['name' => 'Software / Boot Loop', 'icon' => 'code',     'base_price' =>   49, 'estimated_minutes' =>  30, 'description' => 'OS recovery, firmware reflash and data preservation.'],
        ];

        $sort = 1;
        foreach ($issues as $i) {
            RepairIssue::updateOrCreate(
                ['slug' => Str::slug($i['name'])],
                $i + ['slug' => Str::slug($i['name']), 'sort_order' => $sort++, 'is_active' => true],
            );
        }
    }
}
