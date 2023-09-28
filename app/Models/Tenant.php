<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Actions\MigrateTenantAction;
use Spatie\Multitenancy\Commands\TenantsArtisanCommand;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;

class Tenant extends SpatieTenant
{
    protected $table = 'tenants';

    protected static function booted()
    {
        static::created(fn(Tenant $model) => $model->createDatabase());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createDatabase()
    {
        // add logic to create database
        try {

            $this->makeCurrent();
            $status = DB::statement("CREATE DATABASE IF NOT EXISTS `{$this->database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            if ($status) {
                Artisan::call(TenantsArtisanCommand::class, [

                    'artisanCommand' => 'migrate --path=database/migrations/tenant --database=tenant',

                    '--tenant' => $this->id,

                ]);
                $user = $this->user;
                \App\Models\Tenant\User::query()->create([
                    'name' => $user->name,
                    'email' => $user->email,
                ]);

            }

        } catch (\Throwable $th) {

            throw $th;

        }
    }
}
