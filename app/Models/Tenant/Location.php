<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Location extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = 'locations';
    protected $fillable = [
        'name',
        'image',
        'description',
        'tel',
        'email',
        'province_code',
        'district_code',
        'ward_code',
        'address_detail',
        'status',
        'is_main',
        'created_by'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
