<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessField extends Model
{
    use HasFactory;

    protected $table = 'business_fields';
    protected $fillable = [
        'name',
        'code',
        'detail'
    ];
}
