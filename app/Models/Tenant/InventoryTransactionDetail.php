<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class InventoryTransactionDetail extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'inventory_transaction_details';
    protected $fillable=[
        "inventory_transaction_id",
        "variation_id",
        "batch_id",
        "price",
        "price_type",
        "quantity"
    ];

    public function inventoryTransaction()
    {
        return $this->belongsTo(InventoryTransaction::class);
    }

}
