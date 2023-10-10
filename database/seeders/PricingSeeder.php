<?php

namespace Database\Seeders;

use App\Models\Pricing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pricing::create([
            'name' => 'Basic',
            'max_locations' => 1,
            'max_users' => 1,
            'price_per_month' => 0,
        ]);
        Pricing::create([
            'name' => 'Standard',
            'max_locations' => 3,
            'max_users' => 3,
            'price_per_month' => 10,
        ]);
        Pricing::create([
            'name' => 'Premium',
            'max_locations' => 10,
            'max_users' => 10,
            'price_per_month' => 20,
        ]);
    }
}
