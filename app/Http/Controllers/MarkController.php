<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MarkAsFavorite;


class MarkController extends Controller
{
    use App\Models\MarkAsFavorite;
use Illuminate\Http\Request;

public function markAsFavorite(Request $request)
{
    $userId = $request->input('user_id');
    $itemId = $request->input('item_id');
    $type = $request->input('type');
    
    try {
        $favorite = MarkAsFavorite::where('user_id', $userId)
            ->where('item_id', $itemId)
            ->first();

        if (!$favorite) {
            // Insert favorite item
            $favorite = MarkAsFavorite::create([
                'user_id' => $userId,
                'item_id' => $itemId,
                'type' => $type
            ]);

            return response()->json(['message' => 'Item marked as favorite', 'favorite' => $favorite], 200);
        } else {
            // Delete favorite item
            $favorite->delete();
            return response()->json(['message' => 'Item unmarked as favorite'], 200);
        }
    } catch (\Exception $e) {
        // If an exception occurs, attempt to delete the existing favorite record
        MarkAsFavorite::where('user_id', $userId)
            ->where('item_id', $itemId)
            ->delete();

        return response()->json(['message' => 'Error marking/unmarking item as favorite, record deleted', 'error' => $e->getMessage()], 400);
    }
}

    
    
}

