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

    function brands () {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    function warranties () {
        return $this->belongsTo(Warranty::class, 'warranty_id');
    }

    function item_units () {
        return $this->belongsTo(ItemUnit::class, 'item_unit_id');
    }

    function categories () {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
