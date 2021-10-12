<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Authcontroller extends Controller
{
    public function Logout(){
        request()->user()->currentAccessToken()->delete();
        return response()->json(['message'=>"ok"]);        
    }

    public function Login(){
       $email = request()->input('email');
       $password = request()->input("password");
       //$user = User::where(["email"=>$email,"password"=>bcrypt($password)])->first();
       if(Auth::attempt(["email"=>$email,"password"=>$password])){
           $user = User::where("email",$email)->first();
           $token = $user->createToken($user->id)->plainTextToken;
           $message="ok";
           return response()->json(["message"=>$message,"token"=>$token,"user"=>$user]);
       }
             return response()->json(["message"=>"email or password does not exist"])->setStatusCode(400);

    }
    
    public function Signup(){

        request()->validate([
            'username'=>'required|min:6|max:20',
            "email"=>'required|email',
            "password"=>'required'
        ]);

        $user = User::where("email",request()->input('email'))->orWhere("name",request()->input('username'))->first();
        if(!$user){
            $user = new User;
            $user->name= request()->input('username');
            $user->email = request()->input('email');
            $user->password = bcrypt(request()->input('password'));
            $user->save();
            $token = $user->createToken($user->id)->plainTextToken;
            $message = "ok";
            return response()->json(["message"=>$message,"token"=>$token,"user"=>$user]);
        }
        return response()->json(["message"=>"data already exist"])->setStatusCode(400);   
    }
  
}
