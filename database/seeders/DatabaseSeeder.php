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
        Tenant::checkCurrent()
            ? $this->runTenantSpecificSeeders()
            : $this->runLandlordSpecificSeeders();
    }

    public function runTenantSpecificSeeders()
    {
        // run tenant specific seeders
//        \App\Models\Tenant\User::query()->create([
//            'name' => 'tenant1',
//            'email' => 'tenant1@gmail.com',
//            'password' => Hash::make('12345678'),
//        ]);

        $category = \App\Models\Tenant\Category::query()->create([
            "name" => "Quần"
        ]);

        $itemUnit = \App\Models\Tenant\ItemUnit::query()->create([
            "name" => fake()->name()
        ]);

        $brand = \App\Models\Tenant\Brand::query()->create([
            "name" => "Test model brand"
        ]);

        $warranty = \App\Models\Tenant\Warranty::query()->create([
            "name" => "Bảo hành áo da",
            "unit" => 1,
            "period" => 2
        ]);

        $groupCustomer = \App\Models\Tenant\GroupCustomer::query()->create([
            "name" => fake()->name(),
            "description" => "Bán ở cửa hàng cá nhân"
        ]);

        \App\Models\Tenant\Customer::query()->create([
            "group_customer_id" => $groupCustomer->id,
            "type" => 0,
            "name" => fake()->name(),
            "gender" => 1,
            "dob" => "2023/10/10" ,
            "email" => fake()->email(),
            "tel" => fake()->phoneNumber(),
            "status" => 1,
            "province_code" => "100000",
            "district_code" => "29",
            "ward_code" => "10222",
            "address_detail" => "Nhà cách mặt đất 1m, xung quanh toàn đất là đất và đất",
            "note" => "Đang tuyển vợ"
        ]);

        $product = \App\Models\Tenant\Product::query()->create([
            'name' => 'Dầu gội Đặng Hậu',
            'image' => null,
            'weight' => '10',
            'description' => 'Siêu sạch',
            'manage_type' => '1',
            'brand_id' => $brand->id,
            'warranty_id' => $warranty->id,
            'item_unit_id' => $itemUnit->id,
            'category_id' => $category->id,
            'status' => 1
        ]);

        $attribute = \App\Models\Tenant\Attribute::query()->create([
            'product_id' => $product->id,
            'name' => 'Màu sắc'
        ]);

        $attributeValues = [
            [
                'attribute_id' => $attribute->id,
                'value' => 'Đỏ'
            ],
            [
                'attribute_id' => $attribute->id,
                'value' => 'Xanh'
            ]
        ];

        \App\Models\Tenant\AttributeValue::query()->insert($attributeValues);

        $variations = [
            [
                'product_id' => $product->id,
                'sku' => 'WW5K5174VN/SV',
                'barcode' => 'D9VT5UF',
                'variation_name' => 'Đỏ',
                'display_name' => 'Đỏ',
                'image' => null,
                'price_import' => 1200000,
                'price_export' => 800000,
                'status' => 1
            ],
            [
                'product_id' => $product->id,
                'sku' => 'WW10K5233YW/SV',
                'barcode' => '15_EOSF',
                'variation_name' => 'Xanh',
                'display_name' => 'Xanh',
                'image' => null,
                'price_import' => 700000,
                'price_export' => 500000,
                'status' => 1
            ]
        ];

        \App\Models\Tenant\Variation::query()->insert($variations);

        $variation_attributes = [
            [
                'variation_id' => 1,
                'attribute_value_id' => 1
            ],
            [
                'variation_id' => 1,
                'attribute_value_id' => 2
            ]
        ];
        \App\Models\Tenant\VariationAttribute::query()->insert($variation_attributes);
        Tenant\Inventory::create([
            'location_id' => 1,
            'name'=> 'Kho 1',
            'code' => 'KHO1',
            'status' => 1
        ]);
        \App\Models\Tenant\Config::query()->create([
            "business_name" => "Cửa hàng bán quần áo",
            "tel" => "0985658741",
            "email" => "tenant_test@gmail.com"
        ]);

        \App\Models\Tenant\Debt::query()->create([
            "partner_id" => 1,
            "partner_type"=> 0,
            "debit_at" => date('Y-m-d', strtotime('-1 day')),
            "due_at" => date('Y-m-d', strtotime('+1 day')),
            "type" => 0,
            "name" => 'Mua thiếu tiền',
            "amount_debt" => fake()->numberBetween(10000, 1000000),
            "amount_paid" => 0,
            "note" => 'khong text note',
            "status" => 1
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
        $this->call([
            PricingSeeder::class,
            BusinessFieldSeeder::class,
        ]);
        DB::statement("DROP DATABASE IF EXISTS `tenant1`;");
        Tenant::query()->create([
            'name' => 'tenant1',
            'database' => 'tenant1',
            'user_id' => $user->id,
        ]);
    }
}
