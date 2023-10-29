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
        //
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id')->notNullable();
            $table->tinyInteger('partner_type')->nullable();
            $table->date('debit_at')->notNullable();
            $table->date('due_at')->notNullable();
            $table->tinyInteger('type')->notNullable();
            $table->string('name')->notNullable();
            $table->double('principal')->notNullable();
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(1);
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
        //
        Schema::dropIfExists('debts');
    }
};
