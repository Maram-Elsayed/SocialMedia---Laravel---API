<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Post;
use App\Comment;
use App\PostNotification;
use App\User;
use DB;

class CommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        //
    }

    public function send_notification($postId,$userId)
    {
        $post=Post::where('id', $postId)->first();
        $userCommented=User::select('name')->where('id',$userId)->first();
        if($userId != $post->user_id)  {
            if(Str::contains($post->description,'profile picture')){
                $message=$userCommented->name.' commented on your profile picture';
            }
            else{
            $message=$userCommented->name.' commented on your post';
            }
            $postnotification=PostNotification::create(['message'=>$message, 'is_read'=>0, 'is_seen'=>0, 'userId'=>$post->user_id, 'postId'=>$postId]);
        }
        
        
    }

    public function store(Request $request)
    {
        $user=$this->authUser();
        $post=Post::where('id', $request->post)->get();
        if($post->count()<1){
            return response()->json("Post not found");
        }
        $this->validate($request, [
            'comment' => 'nullable',
            'image' => 'image|nullable|max:1999'
        ]);

        // Handle File Upload
        if($request->hasFile('image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('image')->storeAs('public/comment_images', $fileNameToStore);
        } else {
            $fileNameToStore = null;
        }
   
        Comment::create(['comment'=>$request->comment,'userId'=>$user->id,'image'=>$fileNameToStore,'postId'=>$request->post]);
        $this->send_notification($request->post,$user->id);
        return response()->json(['message'=>'Comment added successfully',
                                 'view_post'=> 'http://localhost:8080/socialmedia-api/public/api/posts/'.$request->post]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comments=Comment::with('user')->where('postId',$id)->get();
        if($comments->count()<1)
           return response()->json("No comments found",200);
           
        $data =['comments' => []];
        foreach ($comments as $key => $comment) {  
            $data['comments'][$key] = [
                'user'=>$comment->user->name,
                'profile_picture'=>'http://localhost:8080/socialmedia-api/storage/app/public/profile_pictures/'.$comment->user->profile_picture,
                'posted'=>$comment->created_at,
                'comment'=>$comment->comment,
                'comment_image' => $comment->image!=null?'http://localhost:8080/socialmedia-api/storage/app/public/cover_images/'.$comment->image:null,
            ];
        }
        
        return response()->json($data);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $user=$this->authUser();
        $comment = Comment::find($id)->first();
        if($user->id!=$comment->userId){
            return response()->json("Unauthorized",401);
        }
        if (!isset($comment)){
            return response()->json("Commnet not found",404);
        }
        
         // Handle File Upload
        if($request->hasFile('image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('image')->storeAs('public/comment_images', $fileNameToStore);
            
            // Delete file if exists
            Storage::delete('public/comment_images/'.$comment->image);
        }
        
        // Update Post
       
        if($request->comment){
        $comment->comment = $request->comment;
        }
        if($request->hasFile('image')){
            $comment->image = $fileNameToStore;
        }
        $comment->save();
        return response()->json($comment,200);
    }

    public function destroy($id)
    {
        $user=$this->authUser();
        $comment= Comment::where([['userId',$user->id],['id',$id]])->first();
        if(!$comment)
        {
            return response()->json('Comment not found',400);
        }
        if($comment->image != null){
            Storage::delete('public/comment_images/'.$comment->image);
        }
        
        $comment->delete();
        return $this->show($id);
    }
}
