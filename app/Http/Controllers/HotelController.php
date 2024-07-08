<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotels;
use App\Models\HotelsImages;
use Illuminate\Support\Facades\Storage;
use App\Models\HotelsReviews;

class HotelController extends Controller
{
    public function showAllHotels(Request $request){
        $hotels = Hotels::all();
        $hotels_return=[];
        foreach ($hotels as $p){
            $images = HotelsImages::where("hotel_id",$p['id'])->get();
            $images_arr=[];
            foreach ($images as $i) {
               
                // Assuming 'images' is a directory in 'storage/app/public'


                $image_path = Storage::url("images/" . $i['image_name']);
              
                // $i["path"] = $image_path;

                $i["is_exists"] = Storage::disk('public')->exists("images/" . $i['image_name']);
                $images_arr[] = $i; // This should be an array of images, not a single image
            }
            
            $p['images']=$images;
            $hotels_return[]=$p;
        }
        $res['error']=FALSE;
        $res['msg']="Showing All Hotels";
        $res['records']=$hotels_return;
        return json_encode($res);

    }
    public function getHotelReviews(Request $request){

        $hotel_id= $request->hotel_id;

        $guider = HotelsReviews::where("hotel_id", $hotel_id)->get();
        $guider_return=[];
        foreach ($guider as $p){
            $guider_return[]=$p;
        }
        $res['error']=FALSE;
        $res['msg']="Showing All Hotel Reviews";
        $res['records']=$guider_return;
        return json_encode($res);

    }
}
