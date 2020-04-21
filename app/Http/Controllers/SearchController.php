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

class SearchController extends Controller
{
    public function display_users($users)
    {   
        if($users->count()<1)
           return response()->json("No results found",200);

        $data = ['results' => []];
        foreach ($users as $key => $user) {  
        $data['results'][$key] = [
            'id' => $user->id,
            'user'=>$user->name,
            'profile_picture'=>'http://localhost:8080/socialmedia-api/storage/app/public/profile_pictures/'.$user->profile_picture,
            'profile'=> 'http://localhost:8080/socialmedia-api/users/'.$user->id
        ];
    }
    return response()->json($data);
    }

    public function display_posts($posts)
    {     
        if($posts->count()<1)
           return response()->json("No results found",200);

        $data = ['results' => []];
        foreach ($posts as $key => $post) {  
            $data['results'][$key] = [
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

    public function search_posts(Request $request)
    {
    
        $posts = Post::with('user')->where('caption','LIKE','%'.$request->searchItem.'%')->paginate(10);               
        return  $this->display_posts($posts);
        
    }

    public function search_users(Request $request)
    {
        
        $users=User::where('name','LIKE','%'.$request->searchItem.'%')->get();       
      return $this->display_users($users);
        
    }


}
