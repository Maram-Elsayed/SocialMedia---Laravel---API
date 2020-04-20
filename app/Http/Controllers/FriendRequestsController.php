<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\FriendRequest;
use App\Friend;
use App\User;
use DB;

class FriendRequestsController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function display_friendRequests($friend_requests)
    {
        $data =['friend_requests' => []];
        foreach ($friend_requests as $key => $friend_request) {  
            $data['friend_requests'][$key] = [
                'id' => $friend_request->id,
                'user_from'=>$friend_request->user->name,
                'profile_picture'=>'http://localhost:8080/socialmedia-api/storage/app/public/profile_pictures/'.$friend_request->user->profile_picture
               
            ];
        }
        return response()->json($data);
    }

    public function index()
    {
        $user=$this->authUser();
        $friend_requests = FriendRequest::with('user')->where('user_to', $user->id)->orderBy('created_at','desc')->get();
        if($friend_requests->count()>0){
            return $this->display_friendRequests($friend_requests);
        }
        return response()->json("No friend requests found",200);
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $user=$this->authUser();
        $this->validate($request, [
            'user_to' => 'required'
        ]);
        if($user->id==$request->user_to){
            return response()->json("Invalid input",400);
        }
        $user_to=User::find($request->user_to);
        if(!isset($user_to)){
            return response()->json("User not found",404);
        }
        
        if(FriendRequest::where('user_to', $request->user_to)->where('user_from', $user->id)->count()>0){
            return response()->json("Friends request sent before",200);
        }
        $friend_request=new FriendRequest;
        $friend_request->user_from=$user->id;
        $friend_request->user_to=$request->user_to;
        $friend_request->save();
        return response()->json("Friends request sent",200);
    }

    public function accept_decline_request(Request $request, $id)
    {
        $user=$this->authUser();
        $this->validate($request, [
            'accept' => 'required|boolean'
        ]);
        $friend_request=FriendRequest::find($id);
        if(!isset($friend_request)){
            return response()->json("Friend request not found",404);
        }
        if($request->accept==0){
            $friend_request->delete();
            return response()->json('Friend request declined', 200);
        }
        else if ($request->accept==1){
            $friends=new Friend;
            $friends->user1=$user->id;
            $friends->user2=$friend_request->user_from;
            $friends->save();
            $friend_request->delete();
            return response()->json('Request accepted. You are now friends', 200);
        }
       
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
        $friend_request=FriendRequest::find($id);
        if($friend_request->user_from!=$user->id){
            return response()->json("Invalid request",400);
        }
        else{
            $friend_request->delete();
            return response()->json('Friend request canceled', 200);
        }

    }
}
