<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_supplier_id')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->string('name')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('tel')->unique();
            $table->tinyInteger('status')->nullable();
            $table->unsignedBigInteger('province_code')->nullable();
            $table->unsignedBigInteger('district_code')->nullable();
            $table->unsignedBigInteger('ward_code')->nullable();
            $table->text('address_detail')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};
