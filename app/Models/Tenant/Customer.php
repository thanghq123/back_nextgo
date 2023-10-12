<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Customer extends Model
{
    use HasFactory, UsesTenantConnection;

    public $table = "customers";

    protected $fillable = [
        "group_customer_id",
        "type",
        "name",
        "gender",
        "dob",
        "email",
        "tel",
        "status",
        "province_code",
        "district_code",
        "ward_code",
        "address_detail",
        "note"
    ];
}
