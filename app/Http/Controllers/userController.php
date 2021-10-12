<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    public function userDetails(){
       $data = request()->user();
       if(!$data){
            return response()->json(["message"=>"0"]);
       }
       return response()->json(['message'=>$data]);
    }
    public function updateImage(){
        $user_id = request()->user()->id;
        $user = User::find($user_id);
        $image = request()->file('avatar');
        if($user->image != ""){
            cloudinary()->destroy($user->image_public_id);
            //delete the image from cloudinary
        }
        $saved_img = cloudinary()->upload($image->path(),["folder"=>"a_note"]);
        $user->image =$saved_img->getPath();
        $user->image_public_id = $saved_img->getPublicId();
        $user->save();
        //now add the new image to cloudinary 
        //and then update the database of the user
        return response()->json(['message'=>"ok updateImage","image"=>$user->image]);
    }
    public function editUserData(){
        $user_id = request()->user()->id;
        $user = User::find($user_id);
        $name = request()->input("username");
        $email = request()->input("email");
        if($user->name == $name && $user->email == $email){
            return response()->json(["message"=>"data not changed at all"])->setStatusCode(400);
        }
        $user->name = $name;
        $user->email =$email;
        $user->save();
         return response()->json(['status'=>"success","user"=>$user,"message"=>"data successfully changed"]);
    }

    public function editPassword(){
        $user_id = request()->user()->id;
        $user = User::find($user_id);
        $password = request()->input("password");
        $new_password = request()->input("new_password");
        if(Hash::check($password,$user->password)){
            $user->password = bcrypt($new_password);
            $user->save();
            return response()->json(['status'=>"success","user"=>$user,'message'=>"data successfully changed"]);
        }
        // if(Auth::attempt(["email"=>request()->user()->email,"password"=>$password])){
        //     return response()->json(['status'=>"success","user"=>$user,'message'=>"data successfully changed"]);
        // }
        return response()->json(["message"=>"current password wrong"])->setStatusCode(400);
    }
}
