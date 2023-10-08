<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'districts';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'province_id'];
    protected $hidden = ['created_at', 'updated_at'];
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }
    public function communes()
    {
        return $this->hasMany(Commune::class, 'district_id', 'id');
    }

}
