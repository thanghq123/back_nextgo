<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class OrderDetail extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'order_details';
    protected $fillable=[
        "order_id",
       "variant_id",
        "batch_id",
        "discount",
        "discount_type",
        "tax",
        "quantity",
        "total_price",
    ];
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }
    public function variant()
    {
        return $this->belongsTo(Variant::class,'variant_id','id');
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class,'batch_id','id');
    }

}
