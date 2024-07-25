<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifications;
use DB;

class NotificationController extends Controller
{

    private $count=0;

    public function showAllNotifications(Request $request){
        $user_id = $request->user_id;
        $notifications = DB::table('notifications')->where('user_id',$user_id)->get();
        $res['error']=FALSE;
        $res['msg']="ShowAllNotifications";
        $res['records']=$notifications;
        return json_encode($res);

    }
    public function updateNotificationToken(Request $request) {

        $email=$request->email;
        $token=$request->token;

        if(!isset( $email)){
            $res['error'] = true;
            $res['msg'] = "Email is not set";
            return json_encode($res);
        }
        if(!isset( $token)){
            $res['error'] = true;
            $res['msg'] = "Token is not set";
            return json_encode($res);
        }

        $data=[];
        $data['token']=$token;
        DB::table('users')->where('email',$email)->update($data);

        // check for successful store
        $res['error'] = false;
        $res['msg'] = "Successfully Updated";
        return json_encode($res);
    }




    public function sendNotificationToAll(Request $request) {
        $message=$request->message;

        $count = $this->count + 1;
        if ($count != 1) {
            return;
        }

        $users=DB::table('users')->whereNotNull('token')->get();

        foreach ($users as $user) {
            $token=$user->token;

            //save message into db

            $notification = new Notifications();
            $notification->user_id=$user->id;
            $notification->text=$message;
            $notification->save();

            $fields = array(
                'to' => trim($token),
                "priority" => "high",
                "notification" => array(
                    'body' => $message,
                    'title' => "Travel Tour & Guide System",
                    "priority" => "high"
                ),
                'data' => array(
                    'body' => $message,
                    'title' => "Travel Tour & Guide System",
                    "priority" => "high"
                )
            );
            $this->firebaseNotification($fields);
        }
        echo " Notification sent to " . sizeof($users) . " users";
    }

   

    private function firebaseNotification($fields) {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = array(
            'Authorization:key=AAAAsfMtM80:APA91bGi4bPki13Ve07YMLPo2fVHc50vv6pHpdgUmrnIp3Zz4wMaxcS33MQn7m6vS3XWYrUZlPreGNoRRkyZhwtOkFww-2KxD_Jt6-0_UBunOHTnkWpJRKhb-1urk1iKPggURh0doDHW',
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);

//        var_dump($result);
        return $result;
    }

    public function sendConfirmNotification(Request $request) {
        $message="Your payment has been received";
        $user_id = $request -> $user_id;

        $count = $this->count + 1;
        if ($count != 1) {
            return;
        }

        $users=DB::table('users')->where('id', $user_id)->first();

        foreach ($users as $user) {
            $token=$user->token;

            //save message into db

            $notification = new Notifications();
            $notification->user_id=$user->id;
            $notification->text=$message;
            $notification->save();

            $fields = array(
                'to' => trim($token),
                "priority" => "high",
                "notification" => array(
                    'body' => $message,
                    'title' => "Travel Tour & Guide System",
                    "priority" => "high"
                ),
                'data' => array(
                    'body' => $message,
                    'title' => "Travel Tour & Guide System",
                    "priority" => "high"
                )
            );
            $this->firebaseNotification($fields);
        }
        echo " Notification sent to " . sizeof($users) . " users";
    }
}
