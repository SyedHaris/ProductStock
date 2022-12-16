<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function generate_data()
    {
        DB::table('products')->insert([
            [
                'name' => 'Chicken',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);

        DB::table('ingredients')->insert([
            [
                'name' => 'Chicken',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Salt',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Oil',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);

        DB::table('product_ingredient')->insert([
            [
                'product_id' => 1,
                'ingredient_id' => 1,
                'qty' => 180,
                'unit' => 'g',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'product_id' => 1,
                'ingredient_id' => 2,
                'qty' => 10,
                'unit' => 'g',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'product_id' => 1,
                'ingredient_id' => 3,
                'qty' => 500,
                'unit' => 'g',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);

        DB::table('ingredient_stock')->insert([
            [
                'ingredient_id' => 1,
                'total_stock' => 5,
                'remaining_stock' => 5,
                'unit' => 'kg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ingredient_id' => 2,
                'total_stock' => 2,
                'remaining_stock' => 2,
                'unit' => 'kg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ingredient_id' => 3,
                'total_stock' => 20,
                'remaining_stock' => 20,
                'unit' => 'kg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
