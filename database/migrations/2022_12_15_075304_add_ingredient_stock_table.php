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
        Schema::create('ingredient_stock', function (Blueprint $table) {
            $table->id();
            $table->integer('ingredient_id')->unique();
            $table->integer('total_stock');
            $table->integer('remaining_stock');
            $table->enum('unit', ['g', 'kg']);
            $table->boolean('alert_email_sent')->default(false);
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
        Schema::dropIfExists('ingredient_stock');

    }
};
