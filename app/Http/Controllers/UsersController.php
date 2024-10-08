<?php

namespace App\Http\Controllers;
use App\Models\Users;
use App\Models\UserAppReview;
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

    public function register(Request $request) {
        
        // Validate and sanitize request data
        $data = $request->only(['name', 'email', 'password', 'image']);
        $data['password'] = md5($data['password']); // Use md5 for password hashing
    
        // Check if the email already exists in the database
        $response = Users::where('email', $data['email'])->first();
    
        if ($response != null) {
            // Check if the user is verified
            if ($response->is_verify == 1) {
                $res['error'] = true;
                $res['msg'] = "This email already exists";
                return response()->json($res);
            } else {
                // Delete the unverified user with the same email
                Users::where('email', $data['email'])->delete();
            }
        }
    
        try {
            // Create new user data in the database
            Users::create($data);
            $res['error'] = false;
            $res['msg'] = "Successfully Registered";
        } catch (\Exception $exception) {
            // Handle duplicate email error
            if ($exception->getCode() == '23000') {
                $res['error'] = true;
                $res['msg'] = "This email already exists";
            } else {
                $res['error'] = true;
                $res['msg'] = "An error occurred " .$exception;
            }
        }
    
        return response()->json($res);
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
         $users['address'] = $request->address;

         if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $users['image'] = 'images/' . $imageName;
        }
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
                $message->from('traveltourandguidesystem@outlook.com','Travel Tour & Guide System');
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
    
    public function appReview(Request $request){

        $app_review=new UserAppReview;  //model name
        
        //taking data from request and save into $app_review

        $app_review->user_id = $request->user_id;
        $app_review->app_review = $request->app_review;
        $app_review->rating = $request->rating;

    try {

        //saving data into database

        $app_review->save();
        $res['error']=false;
        $res['msg']="Review Message Has Been Successfull";
        return json_encode($res);
   }catch(Exception $ex){
       $res['error']=TRUE;
       $res['msg']="Error While Saving Message";
       return json_encode($res);
   }
}
}
