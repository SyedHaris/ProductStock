<?php

namespace App\Services;


use App\Models\Product;
use DB;

class OrderService
{
    public function placeOrder($order)
    {
        $product_qty = [];
        if(!empty($order) && isset($order['products'])) {
            foreach ($order['products'] as $item) {
                if(isset($item['product_id']) && isset($item['quantity'])) {
                    $product_qty[$item['product_id']] = $item['quantity'];
                }
            }
        }

        $products = DB::table('products as pr')
                        ->join('product_ingredient as pi', 'pr.id', 'pi.product_id')
                        ->join('ingredients as ing', 'ing.id', 'pi.ingredient_id')
                        ->join('ingredient_stock as  ins', 'ing.id', 'ins.ingredient_id')
                        ->whereIn('pr.id', array_keys($product_qty))
                        ->select(['pr.id as product_id', 'pr.name as product_name', 'pi.qty as prod_ing_qty', 'pi.unit as prod_ing_unit',
                                    'ing.name as ingredient_name', 'ins.id as sock_id', 'ins.total_stock', 'ins.remaining_stock',
                                    'ins.unit as stock_unit', 'ins.alert_email_sent'])
                        ->get();
    }
}
