<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MarkAsVisited;


class VisitedController extends Controller
{
    public function markAsVistied(Request $request){
       

        $userId = $request->input('user_id');
        $itemId = $request->input('item_id');
        $type = $request->input('type');

       
       $visited = MarkAsVisited::where('user_id', $userId)->where('item_id', $itemId)->first();

        if (!$visited) {

            $visited = MarkAsVisited::firstOrCreate(
                ['user_id' => $userId, 'item_id' => $itemId],
                ['type' => $type]
            );
    
            return response()->json(['message' => 'Item marked as visited', 'visited' => $visited], 200);
        } else {
 
            $visited->delete();
            return response()->json(['message' => 'Item unmarked as visited'], 200);
        }

        return response()->json(['message' => 'Invalid action'], 400);
    }
}
