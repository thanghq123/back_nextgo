<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class VariationQuantity extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'variation_quantities';
    protected $fillable=[
        "variation_id",
        "inventory_id",
        "batch_id",
        "price_import",
        "quantity"
    ];
}
