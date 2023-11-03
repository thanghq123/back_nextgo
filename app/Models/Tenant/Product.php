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

    public function brands() {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function warranties() {
        return $this->belongsTo(Warranty::class, 'warranty_id');
    }

    public function itemUnits() {
        return $this->belongsTo(ItemUnit::class, 'item_unit_id');
    }

    public function categories() {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
