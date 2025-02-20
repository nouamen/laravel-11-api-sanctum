<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class commentController extends Controller
{
    public function create(Request $request){
        $validated = Validator::make($request->all(),[
            "post_id" => "required|integer",
            "content" => "required|string"
        ]);
        if($validated->fails()){
            return response()->json([
                "success" => false,
                "message" => "invalid credentials"
            ],400);
        }
        $validatedComment = $validated->validated();
        try{
            $comment = new Comment();
            $comment->post_id = $validatedComment["post_id"];
            $comment->content = $validatedComment["content"];
            $comment->user_id = auth()->user()->id;
            $comment->save();
            return response()->json([
                "success" => true,
                "message" => "comment created successfully"
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
