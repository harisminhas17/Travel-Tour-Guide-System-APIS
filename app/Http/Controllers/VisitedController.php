<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MarkAsVisited;


class VisitedController extends Controller
{
    public function markAsVistied(Request $request){
        $request->validate([
            'user_id' => 'required|integer',
            'item_id' => 'required|integer',
            'action' => 'required|integer|in:0,1',
        ]);

        $userId = $request->input('user_id');
        $itemId = $request->input('item_id');
        $action = $request->input('action');

        if ($action == 1) {

            // Insert visited item

            $visited = Visited::firstOrCreate([
                'user_id' => $userId,
                'item_id' => $itemId,
            ], [
                'type' => 'visited',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['message' => 'Item marked as visited', 'visited' => $visited], 200);
        } else if ($action == 0) {

            // Delete visited item

            $visited = Visited::where('user_id', $userId)->where('item_id', $itemId)->first();

            if ($visited) {
                $visited->delete();
                return response()->json(['message' => 'Item unmarked as visited'], 200);
            } else {
                return response()->json(['message' => 'Visited item not found'], 404);
            }
        }

        return response()->json(['message' => 'Invalid action'], 400);
}

}
