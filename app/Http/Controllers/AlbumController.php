<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoryAlbums;

class AlbumController extends Controller
{
    public function uploadPhotos(Request $request){

        $story_albums=new StoryAlbums;  //model name
        
        //taking data from request and save into $story_albums

        $story_albums->place_name = $request->place_name;
        $story_albums->caption = $request->caption;
        $story_albums->user_id = $request->user_id;

    try {

        //saving data into database

        $story_albums->save();
        $res['error']=false;
        $res['msg']="Message Has Been Successfull";
        return json_encode($res);
   }catch(Exception $ex){
       $res['error']=TRUE;
       $res['msg']="Error While Saving Message";
       return json_encode($res);
   }
}
}
