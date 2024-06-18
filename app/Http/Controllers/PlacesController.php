<?php

namespace App\Http\Controllers;
use App\Models\Places;
use App\Models\PlacesImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlacesController extends Controller
{
    public function showAllPlaces(Request $request){
        $places = Places::all();
        $places_return=[];
        foreach ($places as $p){
            $images = PlacesImages::where("place_id",$p['id'])->get();
            $images_arr=[];
            foreach ($images as $i) {
               
                // Assuming 'images' is a directory in 'storage/app/public'


                $image_path = Storage::url("images/" . $i['image_name']);
              
                // $i["path"] = $image_path;

                $i["is_exists"] = Storage::disk('public')->exists("images/" . $i['image_name']);
                $images_arr[] = $i; // This should be an array of images, not a single image
            }
            
            $p['images']=$images;
            $places_return[]=$p;
        }
        $res['error']=FALSE;
        $res['msg']="Showing All Places";
        $res['records']=$places_return;
        return json_encode($res);

    }
}
