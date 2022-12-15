<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class IngredientStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ingredient_stock')->insert([
            [
                'ingredient_id' => 1,
                'total_stock' => 20,
                'remaining_stock' => 20,
                'unit' => 'kg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ingredient_id' => 2,
                'total_stock' => 5,
                'remaining_stock' => 5,
                'unit' => 'kg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ingredient_id' => 3,
                'total_stock' => 1,
                'remaining_stock' => 1,
                'unit' => 'kg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ingredient_id' => 4,
                'total_stock' => 2,
                'remaining_stock' => 2,
                'unit' => 'kg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
