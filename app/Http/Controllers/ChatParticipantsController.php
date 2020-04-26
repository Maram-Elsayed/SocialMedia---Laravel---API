<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Message;
use App\Chat;
use App\Participant;
use App\User;
use DB;


class ChatParticipantsController extends Controller
{

    public function display_participants($participants){
        if($participants->count()<1){
            return response()->json("No participants found");
        }
        $data =['participants'=> []];
        foreach ($participants as $key => $participant) {
            $data['participants'][$key] = [
                'name'=>$participant->name,
                'profile picture'=>$participant->profile_picture!=null?'http://localhost:8080/socialmedia-api/storage/app/public/profile_pictures/'.$participant->user->profile_picture:null
                
            ];
          }
          return response()->json($data);
    }

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function add_participants(Request $request,$id)
    {
        $user=$this->authUser();  
        $user=Participant::where([['chatId',$id],['userId',$user->id]])->first();
        if(!$user){
            return response()->json('Chat not found');
        }
        $participants=$request->participants; 
        if(!$participants){
            return response()->json('No users selected',400);
        }
        foreach($participants as $participant){
            if(Participant::where([['chatId',$id],['userId',$user->id]])->count()<1){
                continue;
            }
        }
        return response()->json('Members added successfully',400);
    }

    public function show($id)
    {
        $user=$this->authUser();
        $user=Participant::where([['chatId',$id],['userId',$user->id]])->first();
        if(!$user){
            return response()->json('Chat not found');
        }
        $participants=Participant::join('users','users.id','=','participants.userId')
        ->where('participants.chatId',$id)
        ->select(['users.name','users.profile_picture'])->get();
        return $this->display_participants($participants);
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
        $participants=Participant::where([['chatId',$id],['userId',$user->id]])->first();
        $participants->delete();
        if(Participant::where('chatId',$id)->count()<1){
            Chat::find($id)->delete();
        }
        return response()->json('You left the group');

    }
}
