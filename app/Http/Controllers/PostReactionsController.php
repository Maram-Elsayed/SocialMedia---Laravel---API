<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Post;
use App\PostReaction;
use App\Reaction;
use App\User;
use DB;

class PostReactionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function view_reactions($post_reactoins){
        $data =['reactions' => []];
        foreach ($post_reactoins as $key => $reaction) {  
            $data['reactions'][$key] = [
                'user'=>$reaction->user->name,
                'name' => $reaction->reaction->name                              
            ];
        }
        
        return response()->json($data);
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user=$this->authUser();
        $post=Post::find($request->post);
        if(!$post){
            return response()->json('Post not found',404);
        }
        $reaction=Reaction::find($request->reaction);
        if(!$reaction){
            return response()->json('Invalid reaction ID',400);
        }
        $post_reactoin=PostReaction::where([['userId',$user->id],['postId',$post->id]])->first();
       // return $post_reactoin;
        if($post_reactoin){
            if($post_reactoin->reactionId==$reaction->id){
                $post_reactoin->delete();
                return response()->json('Reaction deleted');
            }
            
            $post_reactoin->reactionId=$reaction->id;
            $post_reactoin->save();
            
        }
        else{
        PostReaction::create(['userId'=>$user->id, 'reactionId'=>$reaction->id,'postId'=>$post->id]);
        }
        return response()->json('reacted successfully');
    }

    public function show($id)
    {
        $user=$this->authUser();
        $post=Post::find($id);
        if(!$post){
            return response()->json('Post not found',404);
        }
        $post_reactoins=PostReaction::with('user')->with('post')->with('reaction')
        ->where('postId',$id)->paginate(12);
        if($post_reactoins->count()<1){
            return response()->json('There are no reactions on this post',404);
        }
        
        return $this->view_reactions($post_reactoins);
    }

    public function edit($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        $reaction=Reaction::find($id)->first();
        if(!$reaction){
            return response()->json('Reaction not found',400);
        }
    }

    public function destroy($id)
    {
        //
    }
}
