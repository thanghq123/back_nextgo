<?php

namespace Database\Seeders;

use App\Models\BusinessField;
use App\Models\Tenant;
use App\Models\Tenant\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tenant = Tenant::query()->first();

        $tenant->makeCurrent();

        $user = $tenant->user;

        $businessFieldId = $tenant->business_field_id;

        $businessField = BusinessField::query()->find($businessFieldId);

        $config = [
            'business_name' => $tenant->business_name,
            'tel' => $user->tel,
            'email' => $user->email,
            'address_detail' => $tenant->address,
            'business_field_code' => $businessField->code,
        ];

        Config::query()->create($config);
    }
}
