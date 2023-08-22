<?php

namespace App\Http\Controllers\Api;

use App\Base\BaseFormRequest;
use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    use GeneralTrait;
    public function register (Request $request)

    {
      $fields = $request->validate([
      'name'  => 'required|string',
    //   'phone'  => 'required|string',
      'email'  =>      'string',
      'trans_Type'  => 'string',
    //   'code_car'  =>   'integer',
      'x_location'  => 'string',
      'y_location' =>  'string',
       'password' =>  'required|string|confirmed'

      ]);



    $user = User::create([
        'name' => $fields['name'],
        // 'phone' => $fields['phone'],
          'email' => $fields['email'],
          'trans_Type' => $fields['trans_Type'],
        //   'code_car' => $fields['code_car'],
          'x_location' => $fields['x_location'],
          'y_location' => $fields['y_location'],
          'password' =>bcrypt($fields['password'])

      ]);

      $token = $user->CreateToken('clientToken')->plainTextToken;
      $responce = [
                     'message' =>'the user has register',
                     'user'=> $user,
                     'token' =>$token
      ];
        return response($responce ,200);

       }


       public function login (Request $request) {


                $attr = $request->validate([
                    'name' => 'required|string',
                    'password' => 'required|string|min:6'
                ]);
                 $user =User::where('name', $attr['name'])->first();

                //if(!user || !Hash::check($attr['password'], $user->password))

                if (!Auth::attempt($attr)) {
                    //  $data =  $this->error(401,'Credentials not match');
                     $d = Auth::attempt($attr);
                     return $this->error('Credentials not match', 401);

                }

    //         $validator = Validator::make($request->all(), [
    //             'name' => ['required', 'string', 'max:255'],
    //             'email' => ['string', 'email', 'max:255', 'unique:users', 'required_without_all:phone'],
    //             'password' => [ 'required']
    //             'phone' => ['string', 'max:13', 'unique:users', 'required_without_all:email'],
    // ]);
    // if ($validator->fails()) {
    //     return response()->json($validator->errors());
    // }



             /** 2  */


             $token =  $request->user()->CreateToken('API Token');
             return response()->json(
                [
                   'message'=>'Logged in ',
                   'data'=>[
                           'user'=> $request->user(),
                           'token'=>$token->plainTextToken,
                             'new' => $user
                                     ]
                ]
                   );
        }

        public function logout(Request $request)
            {

                $request->user()->tokens()->delete();

                return response()->json(
                    [
                        'message' => 'Logged out'
                    ]
                );

            }



}
