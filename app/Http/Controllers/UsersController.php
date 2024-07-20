<?php

namespace App\Http\Controllers;
use App\Models\Users;
use Illuminate\Http\Request;
use Exception;
use Mail;
use DB;

class UsersController extends Controller
{
    public function index(){
        $users = Users::all();
        return response()->json($users);
    }

    public function register(Request $request){

       //declaring new variable $users
        $users = new Users;
        
        //taking data from request and save into $users
        $users->name = $request->name;
        $users->email = $request->email;
        $users->password = md5($request->password);
        $users->image = $request->image;
        $users->location = $request->location;

        
        //getting data from database against this email
        $response= Users::where('email',$users->email)->first();
        
        //if user is new and has no data in the database then it will be null otherwise not null
        if($response!=null){
            
            //check stored user is verify or not if verified then return already exists otherwise delete data

            if($response['is_verify']==1){
                $res['error']=true;
                $res['msg']="this email is already exists";
                return json_encode($res);
            }else{
                DB::table('users')->where('email',$users->email)->delete();
            }
        }
       
        try {
           
            //saving data into database

            $users->save();
        }
        catch(Exception $exception){
            $errorCode = $exception->errorInfo[1];
            if($errorCode == 1062){
                $res['error']=true;
                $res['msg']="this email is already exists";
                return json_encode($res);
            }
        }
        $res['error']=false;
        $res['msg']="Successfully Registered";
        return json_encode($res);
    }

    public function login(Request $request){
        $email = $request->email;
        $password = md5($request->password);

        $user= Users::whereEmail($email)->first();
        if($user==null){
            $res['error']=true;
            $res['msg']="User Not Exists";
        }else{
            $user= Users::where("email",$email)->where("is_verify",1)->first();
            if($user==null){
                $res['error']=true;
                $res['msg']="Please verify your email first";
            }
            else{
                if($user->password===$password){
                $res['error']=false;
                $res['msg']="Login Success";
                $res['user']=$user;
            }else{
                $res['error']=true;
                $res['msg']="Password Wrong";
            } 
            }
           
        }
        return json_encode($res);
    }
    public function updateProfile(Request $request){

        //declaring new variable $users
         $users = [];
         
         //taking data from request and save into $users
         $users['name'] = $request->name;
         $users['password'] = md5($request->password);
         $users['image'] = $request->image;
         $users['address'] = $request->address;
 
         try {
             //update data into database
             Users::where('email', $request->email)->update($users);
         }
         catch(Exception $exception){
            echo $exception;
            $res['error']=true;
            $res['msg']="No Profile Exits";
            return json_encode($res);
         }
         $res['error']=false;
         $res['msg']="Successfully Updated Your Profile";
         return json_encode($res);
     }

    public function sendOTP(Request $request){
        $email=$request->email;

        $response=DB::select("select * from users where email='".$email."'");
      
        if(sizeof($response)>0){

            
             $six_digit_random_number = random_int(100000, 999999);
            Users::where('email', $email)->update(['email_otp' => $six_digit_random_number,"otp_time" => now()]);
            $data['otp'] = $six_digit_random_number;
            $user['to'] = $email;

            Mail::send('otpmail', $data, function($message) use ($user){
                $message->to($user['to'])->subject('OTP for TTGS');
                $message->from('traveltourandguidesystem@outlook.com','Travel Tour');
            });
    
            $res['error']=false;
            $res['msg']="OTP send to your Email";
        }
        else{
            $res['error']=TRUE;
            $res['msg']="Email is not registered";
        }

        return json_encode($res);
    }

    public function verifyOTP(Request $request){
        $email=$request->email;
        $otp=$request->otp;

        $response=DB::select("select * from users where email='".$email."' and email_otp='".$otp."'");
      
        if(sizeof($response)>0){

            Users::where('email', $email)->update(['is_verify' => 1]);
            $res['error']=false;
            $res['msg']="OTP is Verify";
        }
        else{
            $res['error']=TRUE;
            $res['msg']="OTP is not Valid";
        }
        return json_encode($res);
    }

    public function setPassword(Request $request){
        $email=$request->email;
        $newpassword=md5($request->password);
        Users::where('email', $email)->update(['password' => $newpassword]);

            $res['error']=false;
            $res['msg']="New Password Has Been Set";
        return json_encode($res);
    }


    public function deleteUser(Request $request) {
       
        $id = $request->id;
        $password = md5($request->password);
    
        // Find the user by id
        $user= Users::whereId($id)->first();
        echo $id;
        if ($user == null) {
            $res['error'] = true;
            $res['msg'] = "User Not Exists";

        } else {

            // Check if the user is verified
            if ($password == $user->password) {

                // Delete the user

                $user->delete();
                $res['error'] = false;
                $res['msg'] = "Account deleted successfully";

            } else {
                $res['error'] = true;
                $res['msg'] = "Password Wrong";
            }
        }
        return response()->json($res);
    }    
}
