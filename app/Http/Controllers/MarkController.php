<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MarkAsFavorite;


class MarkController extends Controller
{
    public function markAsFavorite(Request $request){
        $request->validate([
            'user_id' => 'required|integer',
            'item_id' => 'required|integer',
            'action' => 'required|integer|in:0,1',
        ]);

        $userId = $request->input('user_id');
        $itemId = $request->input('item_id');
        $action = $request->input('action');

        if ($action == 1) {
            // Insert favorite item
            $favorite = Favorite::firstOrCreate([
                'user_id' => $userId,
                'item_id' => $itemId,
            ], [
                'type' => 'favorite',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['message' => 'Item marked as favorite', 'favorite' => $favorite], 200);
        } else if ($action == 0) {
            // Delete favorite item
            $favorite = Favorite::where('user_id', $userId)
                                ->where('item_id', $itemId)
                                ->first();

            if ($favorite) {
                $favorite->delete();
                return response()->json(['message' => 'Item unmarked as favorite'], 200);
            } else {
                return response()->json(['message' => 'Favorite item not found'], 404);
            }
        }

        return response()->json(['message' => 'Invalid action'], 400);
}
}

