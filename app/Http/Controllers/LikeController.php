<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    public function like (Request $request) {
        $validated = Validator::make($request->all(),[
            "post_id" => "required|integer"
        ]);
        if($validated->fails()){
            return response()->json([
                "success" => false,
                "message" => "invalid credentials"
            ],400);
        }
        $validatedData = $validated->validated();
        try{
            $userLikedPostBefore = Like::where("user_id",auth()->user()->id)
            ->where("post_id",$validatedData["post_id"])->first();
            if($userLikedPostBefore){
                return response()->json([
                    "success" => false,
                    "message" => "you cannot like a post twice"
                ],400);
            }
            $like = new Like();
            $like->post_id = $validatedData["post_id"];
            $like->user_id = auth()->user()->id;
            $like->save();
            return response()->json([
                "success" => true,
                "message" => "like added successfully"
            ]);
        }catch(\Exception $exception){
            return response()->json([
                "success" => false,
                "message" => "internal server error",
                "error" => $exception->getMessage()
            ],500);
        }
    }
}
