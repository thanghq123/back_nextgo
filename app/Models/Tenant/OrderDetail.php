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
        "variation_id",
        "price",
        "discount",
        "discount_type",
        "tax",
        "total_price",
    ];
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }
    public function variant()
    {
        return $this->belongsTo(Variation::class,'variation_id','id');
    }
    public function orderDetailBatch()
    {
        return $this->hasMany(OrderDetailBatch::class,'order_detail_id','id');
    }
}
