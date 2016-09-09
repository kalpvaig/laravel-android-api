<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
     /*
        code 8001 : Email Already Exist;
        code 8002 : Email is wrong;
        code 8003 : Email Password Combo is wrong;
    */

    private $clientId='laravelAuthRandomNumberId';
    private $clientSecret = 'laravelAe23443iiousloen(90%$dkvhYtB#99jdcjjdf_77*8554nNmmjeciuew';
   
    public function register(Requests\RegisterRequest $request){
        if($this->checkClientIdAndSecret($request->client,$request->secret))
            return response()->json([],403);

        if ($this->checkIfEmailExist($request->email)) {
             return response()->json([
                    'error'=>'duplicate',
                    'code'=>'8001'
                ],200);
        }
        //If user doesn't exist then create a new user;

        $user = new \App\Userapi;
        $user->name = $request->name;
        $user->token = rand(1000, 300000)."se".rand(100000000,99999999999)."a".rand(999,9888887633323)."U".rand(100,999999999999)."iN".rand(20000999,76678383990); 
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
                'token'=>$user->token
            ],200);


    }


    /** login logic 
     * @param 
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Requests\LoginRequest $request){

        if($this->checkClientIdAndSecret($request->client,$request->secret))
            return response()->json([],403);

        if (!$this->checkIfEmailExist($request->email,$request->password)) {
             return response()->json([
                    'error'=>'auth failed',
                    'code'=>'8002'
                ],200);
        }


        if ($this->checkIfEmailPasswordExist($request->email,$request->password)) {
             return response()->json([
                    'error'=>'auth failed',
                    'code'=>'8003'
                ],200);
        }

        $user = \App\Userapi::where(['email' => $request->email])->first();
        //comment below line if you want to login to multiple device at the same time//

        $user->token = rand(1000, 300000)."se".rand(100000000,99999999999)."a".rand(999,9888887633323)."U".rand(100,999999999999)."iN".rand(20000999,76678383990);
        $user->update();
        return response()->json([
            'token'=> $user->token,
            'name' => $user->name,
            'email' => $user->email
        ],200);
    }


    /**Checking for google token , Currently no backend checking for google sign in
     * @param $token
     * @return bool
     */


    public function checkIfEmailExist($email){
        $user = \App\Userapi::where('email',$email)->first();
        if($user==null){
            return false;
        }
        else
            return true;
    }



    public function checkIfEmailPasswordExist($email,$password){
        $user = \App\Userapi::where('email',$email)->first();
        if (Hash::check($password,$user->password)) {
           return false;
        }
        else
            return true;
    }

    
    private function checkClientIdAndSecret($client,$secret)
    {
        if ($this->clientId==$client && $this->clientSecret == $secret) {
            return false;
        }
        else
            return true;
    }
    
}
