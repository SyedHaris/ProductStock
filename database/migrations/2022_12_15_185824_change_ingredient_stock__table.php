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
        Schema::table('ingredient_stock', function (Blueprint $table) {
            $table->double('total_stock')->change();
            $table->double('remaining_stock')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ingredient_stock', function (Blueprint $table) {
            $table->integer('total_stock')->change();
            $table->integer('remaining_stock')->change();
        });
    }
};
