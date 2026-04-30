<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@fonefitness.test'],
            [
                'name'     => 'Fone Fitness Admin',
                'password' => Hash::make('password'),
            ],
        );

        $this->call([
            CatalogSeeder::class,
            RepairSeeder::class,
            CmsSeeder::class,
            PagesSeeder::class,
        ]);
    }
}
