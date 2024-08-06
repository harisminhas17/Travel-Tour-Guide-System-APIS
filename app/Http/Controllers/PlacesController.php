<?php

namespace App\Http\Controllers;
use App\Models\Places;
use App\Models\PlacesImages;
use App\Models\PlacesReviews;
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


    public function searchPlaces(Request $request){

        $query = $request['query'];
    
        $places = Places::where('name','like','%'.$query.'%')->get();
    
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
        $res['msg']="Showing All Search Places";
        $res['records']=$places_return;
        return json_encode($res);

    }

    public function sendUserPlaceReviews(Request $request){

        $place_reviews=new PlacesReviews; //model name
        
        //taking data from request and save into $place_reviews
        
        $place_reviews->user_id = $request->user_id;
        $place_reviews->review = $request->review;
        $place_reviews->rating = $request->rating;
        $place_reviews->item_id = $request->item_id;
        $place_reviews->item_type = $request->item_type;

    try {

        //saving data into database

        $place_reviews->save();
        $res['error']=false;
        $res['msg']="Place Review Message Has Been Successfull";
        return json_encode($res);
   }catch(Exception $ex){
       $res['error']=TRUE;
       $res['msg']="Error While Saving Message";
       return json_encode($res);
   }
}

    public function getPlacesVideo(Request $request){

        $place_id= $request->place_id;

        $place = HotelsReviews::where("place_id", $place_id)->get();
        $places_return=[];
        foreach ($guider as $p){
            $guider_return[]=$p;
        }
        $res['error']=FALSE;
        $res['msg']="Showing All Places Videos";
        $res['records']=$places_return;
        return json_encode($res);

    }
}
