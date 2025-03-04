<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Validation\Validator;

class OrderController extends Controller
{
    public function listOrders(): JsonResponse
    {
        try {
            $orders = Order::all();
            return response()->json($orders, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve orders'], 500);
        }
    }

    public function createOrder(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'customer_id' => 'required|exists:users,id',
                'driver_id' => 'nullable|exists:users,id',
                'status' => 'nullable|string|in:pending,accepted,shipped,delivered,cancelled',
                'total' => 'required|numeric|min:0',
            ]);

            $order = Order::create($validatedData);
            return response()->json($order, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create order'], 500);
        }
    }

    public function singleOrder(Request $request, string $id): JsonResponse
    {
        try {
            $order = Order::findOrFail($id);
            return response()->json($order, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Order not found'], 404);
        }
    }

    public function updateOrder(Request $request, string $id): JsonResponse
    {
        try {
            $order = Order::findOrFail($id);
            $order->update($request->all());
            return response()->json($order, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update order'], 500);
        }
    }

    public function deleteOrder(string $id): JsonResponse
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();
            return response()->json(['message' => 'Order deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete order'], 500);
        }
    }

    public function getOrdersByUserId(string $userId): JsonResponse
    {
        try {
            $orders = Order::where('user_id', $userId)->get();
            return response()->json($orders, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve orders for user'], 500);
        }
    }
}
