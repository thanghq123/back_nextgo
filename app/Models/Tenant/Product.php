<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Product extends Model
{
    use HasFactory, UsesTenantConnection;
    public $table = 'products';

    protected $fillable = [
        'name',
        'image',
        'weight',
        'description',
        'manage_type',
        'brand_id',
        'warranty_id',
        'item_unit_id',
        'category_id',
        'status'
    ];

    public function attributes() {
        return $this->hasMany(Attribute::class);
    }

    public function variations() {
        return $this->hasMany(Variation::class);
    }
}
