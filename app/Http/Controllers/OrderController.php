<?php

namespace App\Http\Controllers;

use App\Exceptions\OutOfStockException;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function placeOrder(OrderRequest $request, OrderService $service)
    {
        try {
            $service->placeOrder($request->validated());
            return $this->getResponse("Order placed successfully");
        } catch (OutOfStockException $exception) {
            return $this->getResponse("Product is out of stock", true);
        }
    }
}
