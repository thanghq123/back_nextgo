<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Supplier extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = "suppliers";

    protected $fillable = [
        "group_supplier_id",
        "type",
        "name",
        "email",
        "tel",
        "status",
        "province_code",
        "district_code",
        "ward_code",
        "address_detail",
        "note"
    ];

    public function groupSupplier()
    {
        return $this->belongsTo(GroupSupplier::class);
    }
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class,'partner_id','group_supplier_id');
    }
}
