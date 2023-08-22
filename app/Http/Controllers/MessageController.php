<?php

namespace App\Http\Controllers;

use App\Events\eventMessage;
use App\Http\Traits\GeneralTrait;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
   use GeneralTrait;
    public function storeMessage(Request $request){

      $mess =  $request->validate([
        'message'=>'required',
        'to_user_id'=>'required',
        // 'from_user_id'=>'required'
        ]);
         $message =  new Message();
         $message->message = $request->message;
         $message->to_user_id = $request->to_user_id;
         $message->from_user_id = Auth::user()->id;

         $m = $message->message;

            //   if($m->isEmpty()){
            //     return $this->returnErrormsg('100','Enter the message');
            //   }

            try {

                $message->save();
                event(new  eventMessage($message));

                     return $this->returnSucc('200','The Message is added');


            }
            catch (\Exception $e) {
                return $this->returnErrormsg('500','Server Internal  ');

            }
    }

	public function getMessagesAuthToUser($id) {

		$user_id = auth()->user()->id;
		// $data =  Message::where(['to_user_id'=>$id,'from_user_id'=> $user_id])
        //->orWhere(['to_user_id'=>$user_id,'from_user_id'=>$id ] )->get(); give all message

        $data = Message::where(['to_user_id'=>$id,'from_user_id'=> $user_id])
        ->orWhere('from_user_id', $id)
        ->where('to_user_id',$user_id)->get();/// give messages just between tow user

         if($data->isEmpty()){
            return $this->returnSucc('500','There are not data found ');
         }
          return $this->returnData('messages','100' ,$data ,"succes");

	}


      /** send message id for update read_is */
	public function readMessage($id){
    	$msg = Message::find($id);
    	if (empty($msg)) {
            return $this->returnErrormsg('500','There are not message found ');
		}

    	$msg->is_read = 1;
    	$msg->update();

        return $this->returnSucc('500',' is_read field update ');
	}

    public function readMessages($id){
		$msg = message::where('from_user_id',$id);
		if (empty($msg)) {
            return $this->returnErrormsg('500','There are not message found ');
		}
		$msg->update(['is_read'=>1]);
        return $this->returnSucc('200','is_read field update for all messages');
	}

    /** this give data user 1 ,user2  and  message between user 1 and user 2 when the message not read */
    public function getMessagesAuth() {

    	$authUserId = auth()->user()->id;
    	// total and from user and to user
		$message_detail = Message::with(['fromUser','toUser'])
                                              ->select( DB::raw('count(*) as total'),'from_user_id','to_user_id')->where('to_user_id',$authUserId)
                                             ->where('is_read',0)->groupBy('from_user_id','to_user_id')->orderBy('created_at')->get();

  foreach ($message_detail as $key => $message) {
			$ka =  Message::select('message','is_read')->where(['to_user_id'=>$authUserId,'from_user_id'=>$message->from_user_id])->where('is_read', 0)->get()->last();
			$message_detail[$key]->last_message = $ka->message;
			$message_detail[$key]->is_read = $ka->is_read;
		}

		return response()->json(['status'=>'success','data'=> $message_detail]);
	}


}
