<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Support;

class SupportController extends Controller{

    public function addSupportMessage(Request $request){

         $support=new Support;

         //taking data from request and save into $support
        $support->name = $request->name;
        $support->email = $request->email;
        $support->message = $request->message;
        $support->user_id = $request->user_id;
        $support->image_file = $request->image_file;
     

        $res['error']=false;

        try {
             //saving data into database
             $support->save();
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
