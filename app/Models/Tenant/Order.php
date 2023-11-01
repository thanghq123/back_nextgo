<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Order extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'orders';
    protected $fillable=[
        "location_id",
        "customer_id",
        "created_by",
        "discount",
        "discount_type",
        "tax",
        "service_charge",
        "total_product",
        "total_price",
        "status",
        "payment_status"
    ];
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class,'order_id','id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class,'location_id','id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function orderReturns()
    {
        return $this->hasMany(OrderReturn::class,'order_id','id');
    }
   public function payments(){
        return $this->morphMany(Payment::class, 'paymentFor');
   }
}
