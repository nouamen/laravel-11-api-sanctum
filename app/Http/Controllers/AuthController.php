<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register (Request $request) {
        // $validated = $request->validate([
        //     "name" => "required|string|max=255",
        //     "email" => "required|string|email|unique:users,email",
        //     "password" => "required|string|min:8|max:255|confirmed"
        // ]);
        $validated = Validator::make($request->all(),[
            "name" => "required|string",
            "email" => "required|string|email|unique:users,email",
            "password" => "required|string|min:8|max:255|confirmed"
        ]);

        if($validated->fails()){
            return response()->json([
                "success" => false,
                "errors" => $validated->errors()
            ],400);
        }
        $validatedData = $validated->validated();
        try{
            $user = User::create([
                "name" => $validatedData["name"],
                "email" => $validatedData["email"],
                "password" => Hash::make($validatedData["password"])
            ]);
            $token = $user->createToken("auth_token")->plainTextToken;
            return response()->json([
                "success" => true,
                "access_token" => $token,
                "user" => $user
            ],201);
        }catch(Exception $error){
            return response()->json([
                "success" => false,
                "error" => $error->getMessage()
            ],500);
        }
        
    }
    public function login (Request $request) {
        $validated = Validator::make($request->all(),[
            "email" => "required|string|email",
            "password" => "required|string|min:8|max:255"
        ]);
        if($validated->fails()){
            return response()->json([
                "success" => false,
                "errors" => $validated->errors()
            ],400);
        }
        $validatedData = $validated->validated();
        $credentials = ["email"=>$validatedData["email"],"password"=>$validatedData["password"]];
        try{
            if(!Auth::attempt($credentials)){
                return response()->json([
                    "success" => false,
                    "message" => "invalid credentials"
                ],400);
            }
            $user = User::where("email",$validatedData["email"])->firstOrFail();
            $token = $user->createToken("auth_token")->plainTextToken;
            return response()->json([
                "success" => true,
                "access_token" => $token,
                "user" => $user
            ],200);
        }catch(Exception $error){
            return response()->json([
                "success" => false,
                "error" => $error->getMessage()
            ],500);
        }
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "success" => true,
            "message" => "user has been logged out successfully"
        ],200);
    }
    
}
