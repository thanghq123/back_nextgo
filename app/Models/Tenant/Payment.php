<?php

namespace App\Models\Tenant;

use Carbon\Carbon;
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

    public function scopeWhereMethod($query, int $payment_method, array $option = [], ?int $locationId = 0){
        $query->when($locationId != 0, function ($query) use ($locationId){
            return $query->whereHas('paymentable', function($query) use ($locationId){
                return $query->where('location_id', $locationId);
            });
        });

        $query->where('payment_method', $payment_method);

        switch ($option[0]){
            case 'today':
                return $query->whereDate('payment_at', Carbon::today())->sum('amount');
            case 'yesterday':
                return $query->whereDate('payment_at', Carbon::yesterday())->sum('amount');
            case 'sevenDays':
                return $query->whereDate('payment_at', '>=', Carbon::now()->subDays(7))->sum('amount');
            case 'thirtyDays':
                return $query->whereDate('payment_at', '>=', Carbon::now()->subDays(30))->sum('amount');
            case 'fromTo':
                return $query->whereBetween('payment_at', [$option[1], $option[2]])->sum('amount');
            default:
                return responseApi("Lỗi", false);
        }
    }
}
