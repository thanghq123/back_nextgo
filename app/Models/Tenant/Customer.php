<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Illuminate\Database\Eloquent\Builder;

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

    protected $casts = [
        'status' => 'boolean'
    ];

    function group_customer () {
        return $this->belongsTo(GroupCustomer::class, 'group_customer_id');
    }
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class,'partner_id','id');
    }
   public function debts()
    {
        return $this->hasMany(Debt::class,'partner_id','id');
    }


}
