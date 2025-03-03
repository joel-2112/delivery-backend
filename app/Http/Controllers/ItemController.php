<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function listItems()
    {
        try {
            $items = Item::all();
            return response()->json(['success' => true, 'data' => $items], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to retrieve items'], 500);
        }
    }



    public function createNewItem(Request $request)
    {
        // Validate request
        $validatedItem = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        try {
            $item = Item::create($validatedItem);
            return response()->json(['message' => 'The item created successfully', 'data' => $item], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create item'], 500);
        }
    }

    public function showSingleItem(string $id)
    {
        try {
            $item = Item::findOrFail($id);
            return response()->json(['success' => true, 'data' => $item], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }
    }

    public function updateItem(Request $request, string $id)
    {
        // Validate request
        $validatedItem = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        try {
            $item = Item::findOrFail($id);
            $item->update($validatedItem);
            return response()->json(['success' => true, 'data' => $item], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update item'], 500);
        }
    }

    public function deleteItem(string $id)
    {
        try {
            $item = Item::findOrFail($id);
            $item->delete();
            return response()->json(['success' => true, 'message' => 'Item deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete item'], 500);
        }
    }
}
