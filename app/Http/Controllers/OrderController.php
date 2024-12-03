<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    
    public function index()
    {
        return response()->json(Order::all(), 200);
    }

    
    public function store(StoreOrderRequest $request)
    {
        $order = Order::create([
            'user_id' => $request->user_id,
            'car_id' => $request->car_id,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Order created successfully.',
            'order' => $order,
        ], 201);
    }

    
    public function show(Order $order)
    {
        return response()->json($order, 200);
    }

    
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Order updated successfully.',
            'order' => $order,
        ], 200);
    }

    
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully.',
        ], 200);
    }
}
