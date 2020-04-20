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

class ChatsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function display_chats($chats,$user)
    {
        if($chats->count()<1){
            return response()->json("No chats found");
        }
        $data =['chats'=> []];
        foreach ($chats as $key => $chat) {  
            if(!$chat->name ){
                $names=Participant::join('users','users.id','=','participants.userId')
                ->where([['chatId',$chat->id],['userId','<>',$user->id]])->get();
               foreach ($names as $name){
                    $chat->name .=  $name->name.',';
                }
               $chat->name = rtrim($chat->name, ", ");
            }
            $data['chats'][$key] = [
                'name'=>$chat->name==null?$user->name:$chat->name,
                'message'=>$chat->latest_message,
                'open_chat'=>"http://localhost:8080/socialmedia-api/public/api/chat/".$chat->id
            ];
        }
        return response()->json($data);
    }

    public function add_member($userId, $chatId){
        $member=new Participant;
        $member->userId=$userId;
        $member->chatId=$chatId;
        $member->save();
    }
    
    public function index()
    {
        $user=$this->authUser();
        $chats = Participant::join('chats','chats.id','=','participants.chatId')
        ->join('users','users.id','=','participants.userId')
        ->select('users.name','users.profile_picture','chats.latest_message','chats.name','chats.id')
        ->where('userId', $user->id)->orderBy('chats.updated_at','desc')
        ->get();
      // return response()->json($chats);
        return $this->display_chats($chats, $user);
    }

    public function create()
    {
       
    }

    public function store(Request $request)
    {
        $user=$this->authUser();
        $chat=Chat::create(['latest_message'=>null,'name'=>$request->name]);
        $participants=$request->participants;  
        Participant::create(['userId'=>$user->id,'chatId'=>$chat->id]); 
        foreach($participants as $participant){
            Participant::create(['userId'=>$participant,'chatId'=>$chat->id]);
        }
        return response()->json($chat);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
