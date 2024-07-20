<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotels;
use App\Models\HotelsImages;
use Illuminate\Support\Facades\Storage;
use App\Models\HotelsReviews;
use App\Models\Transportation;

class HotelController extends Controller
{
    
    public function getHotelbyCityid(Request $request){

        $city_id= $request->city_id;
      

        $hotels = Hotels::where("city_id", $city_id)->get();
        $hotel_return=[];

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
            $hotel_return[]=$p;
        }
        $res['error']=FALSE;
        $res['msg']="Showing All Hotels By Cities Only";
        $res['records']=$hotel_return;
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
    public function findTransportationbyCityid(Request $request){

        $city_id= $request->city_id;

        $transport = Transportation::where("city_id", $city_id)->get();
        $transport_return=[];
        foreach ($transport as $p){
            $transport_return[]=$p;
        }
        $res['error']=FALSE;
        $res['msg']="Showing All Transport's";
        $res['records']=$transport_return;
        return json_encode($res);
    }
}
