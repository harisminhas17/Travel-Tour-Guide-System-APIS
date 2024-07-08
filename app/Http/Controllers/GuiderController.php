<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TourGuides;
use App\Models\Guider_Images;
use App\Models\getGuiderReviews;

class GuiderController extends Controller
{
    public function findGuiderByCityid(Request $request){

        $city_id= $request->city_id;

        $guider = TourGuides::where("city_id", $city_id)->get();
        $guider_return=[];
        foreach ($guider as $p){
            $guider_return[]=$p;
        }
        $res['error']=FALSE;
        $res['msg']="Showing All Tour Guider";
        $res['records']=$guider_return;
        return json_encode($res);

    }
    public function getGuiderReviews(Request $request){

        $tour_guide_id= $request->tour_guide_id;

        $guider = GuiderReviews::where("tour_guide_id", $tour_guide_id)->get();
        $guider_return=[];
        foreach ($guider as $p){
            $guider_return[]=$p;
        }
        $res['error']=FALSE;
        $res['msg']="Showing All Guider Reviews";
        $res['records']=$guider_return;
        return json_encode($res);

    }
}
