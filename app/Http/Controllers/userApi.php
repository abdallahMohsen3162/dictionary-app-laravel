<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validation;

class userApi extends Controller
{

    public function getusers(): JsonResponse
    {
        return response()->json((new User)->getAll());
    }

    public function usersInfo(Request $req) : JsonResponse
    {
        return response()->json(["user" => $req->user()]);
    }

    public function chechPassword(Request $req): JsonResponse
    {
        $user = User::where('email', $req->email)->first();
        if(Hash::check($req->password, $user->password)){
            return response()->json(["status" => 200]);
        }
        return response()->json(["status" => 505]);
    }

    public function createuser(Request $req): JsonResponse
    {
        $new_mail = $req->email;
        $new_password = $req->password;
        $new_name = $req->name;

        $users = User::where('name', $req->name)->orWhere('email', $req->email)->get();
        $valid = $new_mail && $new_name && $new_password && !$users;
        dd(sizeof($users));
        if(!$valid) return response()->json(["status" => 505,"message"=>"error"]);
        $new_user = new User();
        $new_user->email = $new_mail;
        $new_user->password = Hash::make($new_password);
        $new_user->name = $new_name;
        $new_user->save();

        return response()->json(["status" => 200]);
    }

    public function register(Request $req): JsonResponse
    {
        $validated = $req->validate([
            'email' => 'required|unique:users|max:191|email',
            'password' => 'required',
        ]);

        $new_mail = $req->email;
        $new_password = $req->password;
        $new_name = $req->name;

        try{
            $new_user = new User();
            $new_user->email = $new_mail;
            $new_user->password = Hash::make($new_password);
            $new_user->name = $new_name;
            $new_user->save();
            // return response()->json(["message"=>"success"], 200);
            return response()->json(["message" => "registered","token" => $new_user->createToken("authToken")->plainTextToken],200);
        }catch(err){
            return response()->json(["message"=>"network error"],20);
        }
    }


    public function login(Request $req): JsonResponse
    {
        $validated = $req->validate([
            'email' => 'required|max:191|email',
            'password' => 'required',
        ]);

        try{
            $auth_email = $req->email;
            $auth_password = $req->password;
            $user = User::where('email', $auth_email)->orWhere("name", $auth_email)->first();
            if ($user) {
                if (Hash::check($auth_password, $user->password)) {
                    return response()->json(["message" => "authorized", "user_data" => $user, "token" => $user->createToken("authToken")->plainTextToken],200);
                } else {
                    return response()->json(["message" => "unauthorized"],401);
                }
            } else {
                return response()->json(["message" => "unauthorized"],404);
            }
        }catch(err){
            return response()->json(["message" => "network error"],20);
        }
    }
}
