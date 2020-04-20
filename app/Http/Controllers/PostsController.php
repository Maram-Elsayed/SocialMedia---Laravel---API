<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Post;
use App\Comment;
use App\PostReaction;
use App\User;
use DB;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    
    public function display_posts($posts)
    {   
        if($posts->count()<1)
           return response()->json("No post found",200);
           
        $data =['posts' => []];
        foreach ($posts as $key => $post) {  
            $data['posts'][$key] = [
                'id' => $post->id,
                'user'=>$post->user->name,
                'profile_picture'=>'http://localhost:8080/socialmedia-api/storage/app/public/profile_pictures/'.$post->user->profile_picture,
                'caption'=>$post->caption,
                'posted'=>$post->created_at,
                'image' => 'http://localhost:8080/socialmedia-api/storage/app/public/cover_images/'.$post->cover_image,
                'react_to_post' => 'http://localhost:8080/socialmedia-api/public/api/react?post='.$post->id,
                'reactions'=>  PostReaction::where('postId',$post->id)->count(),
                'view_reactions' => 'http://localhost:8080/socialmedia-api/public/api/react/'.$post->id,
                'View all comments' =>'http://localhost:8080/socialmedia-api/public/api/comments?post='.$post->id,
                'comments'=> User::join('comments','users.id','=','comments.userId')->select('users.name','users.profile_picture','comment','image As comment_image')->get(),
                'write a comment' => 'http://localhost:8080/socialmedia-api/public/api/comments?post='.$post->id


            ];
        }
        
        return response()->json($data);
    }

    public function index()
    {
        $user=$this->authUser();
        $posts = Post::with('user')->orderBy('created_at','desc')->paginate(10);
        
        return $this->display_posts($posts);
    }
    
    public function viewUserPosts($id)
    {
        $user=$this->authUser();
        $posts = Post::with('user')->where('user_id', $id)->orderBy('created_at','desc')->paginate(10);
        return $this->display_posts($posts);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'caption' => 'nullable',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        // Handle File Upload
        if($request->hasFile('cover_image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        } else {
            $fileNameToStore = null;
        }

        // Create Post
        $post = new Post;
        $post->caption = $request->input('caption');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return response()->json($post,201);
    }

    public function show($id)
    {
        $post=Post::where('id', $id)->with('user')->get();
       
        
       return $this->display_posts($post);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $user=$this->authUser();
        $post = Post::find($id);
        if($user->id!=$post->user_id){
            return response()->json("Unauthorized",401);
        }
        if (!isset($post)){
            return response()->json("Post not found",404);
        }
         // Handle File Upload
        if($request->hasFile('cover_image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
            // Delete file if exists
            Storage::delete('public/cover_images/'.$post->cover_image);
        }
        
        // Update Post
       
        if($request->input('caption')){
        $post->caption = $request->input('caption');
        }
        if($request->hasFile('cover_image')){
            $post->cover_image = $fileNameToStore;
        }
        $post->save();
        return response()->json($post,200);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!isset($post)){
            return response()->json("Post not found",404);
        }
        if($post->cover_image != 'noimage.jpg'){
            // Delete Image
            Storage::delete('public/cover_images/'.$post->cover_image);
        }
        
        $post->delete();
        return response()->json('Post deleted', 200);
    }

  
}
