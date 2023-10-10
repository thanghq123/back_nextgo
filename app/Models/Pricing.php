<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    use HasFactory;
    protected $table = 'pricings';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'max_locations', 'max_users', 'price_per_month'];

    public function users()
    {
        return $this->hasMany(User::class, 'pricing_id', 'id');
    }
}
