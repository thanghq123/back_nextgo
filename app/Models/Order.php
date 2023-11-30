<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class Order extends Model
{
    use HasFactory, UsesLandlordConnection;

    protected $table = 'orders';
    protected $fillable = [
        'tenant_change_history_id',
        'total',
        'payment_method',
        'reference_code',
        'created_by'
    ];

    public function tenantChangeHistory()
    {
        return $this->belongsTo(TenantChangeHistory::class, 'tenant_change_history_id', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
