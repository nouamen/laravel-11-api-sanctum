<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function create(Request $request){
        $validated = Validator::make($request->all(),[
            "title" => "required|string",
            "body" => "required|string"
        ]);
        if($validated->fails()){
            return response()->json([
                "success" => false,
                "message" => "invalid credentials"
            ],400);
        }
        $validatedData = $validated->validated();
        try{
            $post = new Post();
            $post->title = $validatedData["title"];
            $post->body = $validatedData["body"];
            $post->user_id = auth()->user()->id;
            $post->save();
            return response()->json([
                "success" => true,
                "message" => "post created successfully"
            ],201);
        }catch(Exception $error){
            return response()->json([
                "success" => true,
                "message" => "internal server error",
                "error" => $error->getMessage()
            ],500);
        }
    }
    public function edit(Request $request,$post_id){
        $validated = Validator::make($request->all(),[
            "title" => "required|string",
            "body" => "required|string"
        ]);
        if($validated->fails()){
            return response()->json([
                "success" => false,
                "message" => "invalid credentials"
            ],400);
        }
        $validatedData = $validated->validated();
        try{
            $post = Post::find($post_id);
            $post->update([
                "title" => $validatedData["title"],
                "body" => $validatedData["body"] 
            ]);
            return response()->json([
                "success" => true,
                "message" => "post updated successfully"
            ],201);
        }catch(Exception $error){
            return response()->json([
                "success" => true,
                "message" => "internal server error",
                "error" => $error->getMessage()
            ],500);
        }
    }
    public function getPosts(){
        try{
            $posts = Post::all();
            return response()->json([
                "success" => true,
                "posts" => $posts
            ],200);
        }catch(\Exception $exception){
            return response()->json([
                "success" => false,
                "message" => "internal server error",
                "error" => $exception->getMessage()
            ],500);
        }
    }
    public function getPost($post_id){
        try{
            $post = Post::with("user","comment","like")->find($post_id);
            return response()->json([
                "success" => true,
                "posts" => $post
            ],200);
        }catch(\Exception $exception){
            return response()->json([
                "success" => false,
                "message" => "internal server error",
                "error" => $exception->getMessage()
            ],500);
        }
    }
    public function destroy(Request $request,$post_id){
        try{
            $post = Post::find($post_id);
            $post->delete();
            return response()->json([
                "success" => true,
                "message" => "post deleted successfully"
            ],200);
        }catch(\Exception $exception){
            return response()->json([
                "success" => false,
                "message" => "internal server error",
                "error" => $exception->getMessage()
            ],500);
        }
    }
}
