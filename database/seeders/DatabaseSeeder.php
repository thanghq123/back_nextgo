<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        Tenant::checkCurrent()
            ? $this->runTenantSpecificSeeders()
            : $this->runLandlordSpecificSeeders();
    }

    public function runTenantSpecificSeeders()
    {
        // run tenant specific seeders
        \App\Models\Tenant\User::query()->create([
            'name' => 'tenant1',
            'email' => 'tenant1@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
    }

    public function runLandlordSpecificSeeders()
    {
        // run landlord specific seeders
        $user = User::query()->create([
            'name' => 'tenant_test',
            'email' => 'tenant_test@gmail.com',
            'password' => '12345678',
        ]);
        DB::statement("DROP DATABASE IF EXISTS `tenant1`;");
        Tenant::query()->create([
            'name' => 'tenant1',
            'database' => 'tenant1',
            'user_id' => $user->id,
        ]);
    }
}
