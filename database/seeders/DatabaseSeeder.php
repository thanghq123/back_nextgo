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
        $this->call([
//            PricingSeeder::class,
//            BusinessFieldSeeder::class,
        ]);
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

        \App\Models\Tenant\Category::query()->create([
            "name" => "Quần"
        ]);

        \App\Models\Tenant\ItemUnit::query()->create([
            "name" => fake()->name()
        ]);

        \App\Models\Tenant\Brand::query()->create([
            "name" => "Test model brand"
        ]);

        \App\Models\Tenant\Warranty::query()->create([
            "name" => "Bảo hành áo da",
            "unit" => 1,
            "period" => 2
        ]);

        $groupCustomer = \App\Models\Tenant\GroupCustomer::query()->create([
            "name" => "Bán lẻ",
            "description" => "Bán ở cửa hàng cá nhân"
        ]);

        \App\Models\Tenant\Customer::query()->create([
            "group_customer_id" => $groupCustomer->id,
            "type" => 0,
            "name" => "Đặng Văn Hậu",
            "gender" => 1,
            "dob" => "2023/10/10",
            "email" => "a@gmail.com",
            "tel" => "0985658741",
            "status" => 1,
            "province_code" => "100000",
            "district_code" => "29",
            "ward_code" => "10222",
            "address_detail" => "Nhà cách mặt đất 1m, xung quanh toàn đất là đất và đất",
            "note" => "Đang tuyển vợ"
        ]);

        $groupSupplier = \App\Models\Tenant\GroupSupplier::query()->create([
            "name" => "Nhà cung cấp bia Hậu 02",
            "description" => "Nhà có vườn bia"
        ]);

        \App\Models\Tenant\Supplier::query()->create([
            "group_supplier_id" => $groupSupplier->id,
            "type" => 0,
            "name" => "Đặng Văn Hậu",
            "email" => "haudz@gmail.com",
            "tel" => "0958658748",
            "status" => 1,
            "province_code" => 522,
            "district_code" => 33,
            "ward_code" => 22,
            "address_detail" => "Vườn bia Đặng Hậu",
            "note" => "Siêu uy tín NRO",
        ]);
        \App\Models\Tenant\Config::query()->create([
            "business_name" => "Cửa hàng bán quần áo",
            "tel" => "0985658741",
            "email" => "tenant_test@gmail.com"
        ]);
    }

    public function runLandlordSpecificSeeders()
    {
        // run landlord specific seeders
        $user = User::query()->create([
            'name' => 'tenant_test',
            'email' => 'tenant_test@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        DB::statement("DROP DATABASE IF EXISTS `tenant1`;");
        Tenant::query()->create([
            'name' => 'tenant1',
            'database' => 'tenant1',
            'user_id' => $user->id,
        ]);
    }
}
