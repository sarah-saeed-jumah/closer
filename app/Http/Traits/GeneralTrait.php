<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Validator;

trait GeneralTrait {

public function returnError($errNum ,$msg)
{
 return  response()->json([
     'status'=> false,
     'errNum'=> $errNum,
     'msg'   => $msg
 ]);
}
public function returnSucc($SucNum ,$msg)
{
 return  response()->json([
     'status'=> true,
     'succNum'=> $SucNum,
     'msg'   => $msg
 ]);
}
public function returnErrormsg($errNum ,$msg)
{
 return  response()->json([
     'status'=> false,
     'errNum'=> $errNum,
     'msg'   => $msg
 ]);
}

public function returnData($Key ,$errNum,$value ,$msg=" ")
{
 return  response()->json([
     'status'=> true,
     'errNum'=> $errNum,
     'msg'   => $msg,
      $Key=> $value

 ]);
}

public function returnErrorValidation($validator){
   $input =array_keys($validator->errors()->toArray());
   $code = $this->getErrorCode($input[0]);
   return $code;
}
public function getErrorCode($input){

    if($input == 'name'){
       return 'Enter the name';
    }


  elseif ($input == 'Type'){
        return 'Enter the Type client';
     }
     elseif($input == 'password'){
        return 'Enter the password ';
     }

}

public function returnValidationError($code , $validator){

    return  $this->returnError($code ,$validator->errors()->first());
}

}
