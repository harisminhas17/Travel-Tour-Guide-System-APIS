<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function bookingTour(Request $request) {

        $booking=new Booking;
        
        //taking data from request and save into $booking

        $booking->name = $request->name;
        $booking->phone_number = $request->phone_number;
        $booking->address = $request->address;
        $booking->city = $request->city;
        $booking->arrival_date = $request->arrival_date;
        $booking->departure_date = $request->departure_date;
        $booking->province = $request->province;

        $booking->room_type = $request->room_type;
        $booking->no_of_rooms = $request->no_of_rooms;
        $booking->bed_type = $request->bed_type;
        $booking->no_of_guests = $request->no_of_guests;
        $booking->special_request = $request->special_request;

    

        $booking->user_id = $request->user_id;
 try {
             //saving data into database
             $booking->save();
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
