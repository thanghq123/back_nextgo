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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_customer_id');
            $table->tinyInteger('type');
            $table->string('name')->unique();
            $table->tinyInteger('gender');
            $table->date('dob');
            $table->string('email')->unique();
            $table->string('tel')->unique();
            $table->tinyInteger('status');
            $table->unsignedBigInteger('province_code');
            $table->unsignedBigInteger('district_code');
            $table->unsignedBigInteger('ward_code')->nullable();
            $table->text('address_detail');
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
        Schema::dropIfExists('customers');
    }
};
