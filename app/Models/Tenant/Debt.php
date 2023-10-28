<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Debt extends Model
{
    use HasFactory, UsesTenantConnection;

    public $table = "debts";

    protected $fillable = [
        "partner_id",
        "partner_type",
        "debit_at",
        "due_at",
        "type",
        "name",
        "principal",
        "note",
        "status"
    ];
}
