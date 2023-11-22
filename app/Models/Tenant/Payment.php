<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Payment extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'payments';
    protected $fillable=[
        "paymentable_type",
        "paymentable_id",
        "amount",
        "amount_in",
        "amount_refund",
        "payment_method",
        "payment_at",
        "reference_code",
        "note",
        "created_by"
    ];
    public function paymentable()
    {
        return $this->morphTo();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
