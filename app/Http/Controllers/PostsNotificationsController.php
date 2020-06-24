<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostNotification;

class PostsNotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function display_notifications($notifications)
    {
        $data =['notifications' => []];
        foreach ($notifications as $key => $notification) {  
            $data['notifications'][$key] = [
                'id' => $notification->id,
                'message'=>$notification->message,
                'postId'=>$notification->postId,
                'is_seen'=>$notification->is_seen,
                'is_read'=>$notification->is_read

            ];
        }
        return response()->json($data);
    }

    public function index()
    {
        $user=$this->authUser();
        $notifications = PostNotification::with('user')->where('userId', $user->id)->orderBy('created_at','desc')->paginate(10);
        PostNotification::where('is_seen', '=', 0)->update(array('is_seen' => 1));
        if($notifications->count()>0){
            return $this->display_notifications($notifications);
        }
        return response()->json("No notifications found",200); 

    }

    
    public function new_notifications_count()
    {
       $user=$this->authUser();
       $notifications_count= PostNotification::where('userId',$user->id)->where('is_seen','=', 0)->count();
       return response()->json($notifications_count,200);
    }

   
    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $user=$this->authUser();
        $notification=PostNotification::where('id',$id)->where('userId',$user->id)->get();
        if($notification->count()<1)
           return response()->json("Invalid notification ID",200);
        
        PostNotification::where('id', $id)->update(['is_read' => 1]);
        return response()->json("notification read",200);

        
    }

   
    public function edit($id)
    {
        //
    }

  
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
