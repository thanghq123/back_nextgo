<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Location extends Model
{
    use HasFactory,UsesTenantConnection;
    public $table = 'locations';
    protected $fillable=[
        "name",
        "image",
        "description",
        "tel",
        "email",
        "province_code",
        "district_code",
        "ward_code",
        "address_detail",
        "status",
        "is_main",
        "created_by"
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
        'is_main' => 'boolean',
    ];
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function inventories(){
        return $this->hasMany(Inventory::class,'location_id');
    }
}
