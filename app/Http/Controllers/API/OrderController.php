<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        // Validate the request data as needed
        $request->validate([
            'user_id' => 'required|integer',
            'total_amount' => 'required|numeric|min:0',
        ]);

        // Create the order
        $order = Order::create($request->all());

        return response()->json(['id' => $order->id], 201);
    }

    public function addOrderItems(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $request->validate([
                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.quantity' => 'distinct', // Ensure each product is added only once
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return response()->json(['message' => 'Order items added successfully'], 201);
        } catch (\Exception $e) {
            \Log::error('Error adding order items: ' . $e->getMessage());

            return response()->json(['error' => 'Unable to add order items'], 422);
        }
    }

}
