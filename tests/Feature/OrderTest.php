<?php

namespace Tests\Feature;

use App\Mail\StockLevelAlert;
use App\Models\IngredientStock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_is_placed_and_stock_is_correctly_updated()
    {
        $this->generate_data();

        $response = $this->post('/api/order', [
            'products' => [
                [
                    'product_id' => 1,
                    'quantity' => 5
                ]
            ]
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Order placed successfully'
            ]);

        $this->assertDatabaseHas('ingredient_stock', [
            'id' => 1,
            'remaining_stock' => 4.1
        ]);

        $this->assertDatabaseHas('ingredient_stock', [
            'id' => 2,
            'remaining_stock' => 1.95
        ]);

        $this->assertDatabaseHas('ingredient_stock', [
            'id' => 3,
            'remaining_stock' => 17.5
        ]);
    }

    public function test_order_is_stored_correctly()
    {
        $this->generate_data();

        $this->post('/api/order', [
            'products' => [
                [
                    'product_id' => 1,
                    'quantity' => 5
                ]
            ]
        ]);

        $this->assertDatabaseCount('orders', 1);

        $this->assertDatabaseHas('order_item', [
            'product_id' => 1,
            'qty' => 5
        ]);
    }

    public function test_product_out_of_stock_exception()
    {
        $this->generate_data();

        $response = $this->post('/api/order', [
            'products' => [
                [
                    'product_id' => 1,
                    'quantity' => 30
                ]
            ]
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => false,
                'message' => 'Product is out of stock'
            ]);
    }

    public function test_stock_level_alert_email_is_not_sent()
    {
        $this->generate_data();

        Mail::fake();

        $this->post('/api/order', [
            'products' => [
                [
                    'product_id' => 1,
                    'quantity' => 5
                ]
            ]
        ]);

        Mail::assertNothingSent();
    }

    public function test_stock_level_alert_email_is_sent_with_correct_ingredients()
    {
        $this->generate_data();

        Mail::fake();

        $this->post('/api/order', [
            'products' => [
                [
                    'product_id' => 1,
                    'quantity' => 20
                ]
            ]
        ]);

        Mail::assertSent(function (StockLevelAlert $mail) {
             return array_keys($mail->ingredients_list) == [1, 3];
        });
    }

    public function test_stock_level_alert_email_is_sent_only_once()
    {
        $this->generate_data();

        IngredientStock::whereIn('id', [1, 3])->update([
            'alert_email_sent' => 1
        ]);

        Mail::fake();

        $this->post('/api/order', [
            'products' => [
                [
                    'product_id' => 1,
                    'quantity' => 20
                ]
            ]
        ]);

        Mail::assertNothingSent();
    }
}
