<?php

namespace App\Http\Controllers;

use App\Events\SendLocation;
use App\Http\Traits\GeneralTrait;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class LocationController extends Controller
{
    use GeneralTrait;

    public function getLocation(){
     $data = User::all();
     $loc = User::select('x_location','y_location')->get();

        return response()->json(
            [
                'loc'=>$loc,

               'message'=>'success']
               );

    }
    public function test (){
            $id_U = Auth::user()->id;
            return $id_U;
    }

    public function setLoc(Request $request){

        $id = Auth::user()->id;
        $lat = $request['lat'];
        $long = $request['long'];
        $location = ["lat"=>$lat, "long"=>$long,"id"=> $id ];
        DB::table('users')
                        ->where('id', $id )
                        ->update(['x_location' =>  $lat,
                                  'y_location' =>  $long
                                 ]);
        try{
          event(new SendLocation ($location));
            return $this->returnSucc('200','The Message is added');

        }
        catch (\Exception $e) {

          return $this->returnErrormsg('503','Error in send the message');

        }

    }





       public function getauth(){
        $id_U = Auth::user()->id;
		return response()->json(['status'=>'success','data'=>   $id_U]);

     }
}
