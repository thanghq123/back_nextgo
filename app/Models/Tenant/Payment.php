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
        "payment_for",
        "payment_for_type",
        "amount",
        "amount_in",
        "amount_refund",
        "method",
        "payment_at",
        "reference_code",
        "note",
        "created_by"
    ];
    protected $casts = [
        'payment_at' => 'timestamp',
    ];
    public function paymentFor()
    {
        return $this->morphTo();
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
