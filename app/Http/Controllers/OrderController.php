<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function placeOrder(OrderRequest $request, OrderService $service) 
    {
        $service->placeOrder($request->validated());
    }
}
