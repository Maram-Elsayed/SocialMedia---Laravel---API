<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Friend;
use App\User;
use DB;

class FriendsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function display_friends($friends,$userId)
    {
        if($friends->count()<1){
            return response()->json("No friends found");
        }
        $data =['friends' => []];
        foreach ($friends as $key => $friend) {
            if($friend->user_1->id!=$userId){
                $friend_id=$friend->user_1;
            }
            else{
                $friend_id=$friend->user_2;
            }
            $data['friends'][$key] = [
                'id' => $friend->id,
                'name'=>$friend_id->name,
                'profile_picture'=>$friend_id->profile_picture!=null?'http://localhost:8080/socialmedia-api/storage/app/public/profile_pictures/'.$friend_id->profile_picture:null
               
            ];
        }
        return response()->json($data);
    }

    public function index()
    {
        $user=$this->authUser();
        $friends = Friend::with('user_1')->with('user_2')
        ->where('user1', $user->id)
        ->orWhere('user2', $user->id)->get();
        return $this->display_friends($friends,$user->id);
       
    }

    public function viewUserFriends($id)
    {
        $user=$this->authUser();
        $friends = Friend::with('user_1')->with('user_2')
        ->where('user1', $id)
        ->orWhere('user2', $id)->get();
        return $this->display_friends($friends,$id);
    }

    public function create()
    {
        //
    }
    
    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
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
        $user=$this->authUser();
        $friend = Friend::where([['user1', $user->id],['user2', $id]])
        ->orWhere([['user2', $user->id],['user1', $id]])->first();
        if(!isset($friend)){
            return response()->json("Not found",404);
        }
        else{
            $friend->delete();
            return response()->json('Friend removed', 200);
        }
      
    }
}
