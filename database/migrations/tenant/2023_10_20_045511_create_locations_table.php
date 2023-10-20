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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('tel');
            $table->string('email')->nullable();
            $table->bigInteger('province_code')->nullable();
            $table->bigInteger('district_code')->nullable();
            $table->bigInteger('ward_code')->nullable();
            $table->string('address_detail');
            $table->tinyInteger('status');
            $table->tinyInteger('is_main');
            $table->bigInteger('created_by')->nullable();
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
        Schema::dropIfExists('locations');
    }
};
