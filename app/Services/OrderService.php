<?php

namespace App\Services;


use App\Exceptions\OutOfStockException;
use App\Mail\StockLevelAlert;
use App\Models\IngredientStock;
use App\Models\Order;
use DB;
use Illuminate\Support\Facades\Mail;

class OrderService
{
    public function placeOrder($order)
    {
        $product_qty = [];
        if(!empty($order) && isset($order['products'])) {
            foreach ($order['products'] as $item) {
                if(isset($item['product_id']) && isset($item['quantity'])) {
                    if (array_key_exists($item['product_id'], $product_qty)) {
                        $product_qty[$item['product_id']] += $item['quantity'];
                    } else {
                        $product_qty[$item['product_id']] = $item['quantity'];
                    }
                }
            }
        }

        $products = DB::table('products as pr')
                        ->join('product_ingredient as pi', 'pr.id', 'pi.product_id')
                        ->join('ingredients as ing', 'ing.id', 'pi.ingredient_id')
                        ->join('ingredient_stock as  ins', 'ing.id', 'ins.ingredient_id')
                        ->whereIn('pr.id', array_keys($product_qty))
                        ->select(['pr.id as product_id', 'pr.name as product_name', 'ing.id as ingredient_id', 'ing.name as ingredient_name',
                                    'ins.id as stock_id', 'ins.alert_email_sent', 'ins.unit as stock_unit',
                                    DB::raw("(CASE WHEN pi.unit = 'kg' THEN pi.qty*1000 ELSE pi.qty END) as product_ing_qty"),
                                    DB::raw("(CASE WHEN ins.unit = 'kg' THEN ins.total_stock*1000 ELSE ins.total_stock END) as total_stock"),
                                    DB::raw("(CASE WHEN ins.unit = 'kg' THEN ins.remaining_stock*1000 ELSE ins.remaining_stock END) as remaining_stock")])
                        ->get();

        $ingredients_list_for_email = [];
        $updated_ingredient_stock = [];
        foreach ($products as $product) {
            $quantity = $product_qty[$product->product_id] * $product->product_ing_qty;

            if ($quantity <= $product->remaining_stock) {
                    $product->remaining_stock -= $quantity;
                    $alert_email_sent = 0;
                    if((($product->remaining_stock/$product->total_stock) * 100) <= 50 && !$product->alert_email_sent) {
                        // prepare list for email
                        $alert_email_sent = 1;
                        if(!array_key_exists($product->ingredient_id, $ingredients_list_for_email)) {
                            $ingredients_list_for_email[$product->ingredient_id] = [
                                'ingredient_id' => $product->ingredient_id,
                                'ingredient_name' => $product->ingredient_name,
                                'remaining_stock' => $product->stock_unit === 'kg' ? $product->remaining_stock/1000 : $product->remaining_stock,
                                'unit' => $product->stock_unit
                            ];
                        } else {
                            $ingredients_list_for_email[$product->ingredient_id]['remaining_stock'] = $product->stock_unit === 'kg' ? $product->remaining_stock/1000 : $product->remaining_stock;
                        }
                    }
                } else {
                    // throw out of stock option
                    throw new OutOfStockException();
                }

                $updated_ingredient_stock[] = [
                    'stock_id' => $product->stock_id,
                    'remaining_stock' => $product->stock_unit === 'kg' ? $product->remaining_stock/1000 : $product->remaining_stock,
                    'alert_email_sent' => $product->alert_email_sent === 1 ? $product->alert_email_sent : $alert_email_sent
                ];
        }

        DB::transaction(function () use ($updated_ingredient_stock, $product_qty, $ingredients_list_for_email) {
            foreach ($updated_ingredient_stock as $item) {
                $id = $item['stock_id'];
                unset($item['stock_id']);
                IngredientStock::where('id', $id)->update($item);
            }

            $order = new Order();
            $order->order_num = uniqid();
            $order->save();

            $order_items = [];
            foreach ($product_qty as $product_id => $qty) {
                $order_items[] = [
                    'order_id' => $order->id,
                    'product_id' => $product_id,
                    'qty' => $qty,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
            DB::table('order_item')->insert($order_items);

            // send an email
            if(!empty($ingredients_list_for_email)) {
                logger("Sending email with data: ", $ingredients_list_for_email);
                Mail::to(env('MAIL_ADMIN'))->send(new StockLevelAlert($ingredients_list_for_email));
            }
        });
    }
}
