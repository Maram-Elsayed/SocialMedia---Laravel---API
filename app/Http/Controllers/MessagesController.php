<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Message;
use App\Chat;
use App\Participant;
use App\User;
use DB;

class MessagesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function display_messages($messages)
    {
        $data =['messages' => []];
        foreach ($messages as $key => $message) {  
            $data['messages'][$key] = [
                'user'=>$message->user->name,
                'profile_picture'=>'http://localhost:8080/socialmedia-api/storage/app/public/profile_pictures/'.$message->user->profile_picture,
                'message'=>$message->message
            ];
        }
        return response()->json($data);
    }

    
    public function index()
    {
        
    }

    public function create()
    {
        //
    }

    public function send_message(Request $request, $id)
    {
        $user=$this->authUser();
        $chat=Participant::where([['userId',$user->id],['chatId',$id]])->first();
        if(!$chat){
            return response()->json('Chat not found',404);
        }
        $message=new Message; 
        $message->user_from=$user->id;
        $message->message=$request->message;
        $message->chatId=$id;
        $message->save();
        Chat::where('id', $id)->update(['latest_message'=>$request->message]);  
        return $this->show($id);
    }

    public function show($id)
    {
        $user=$this->authUser();
        $messages=Message::with('user')->with('chat')->where('chatId',$id)->get();
      //  return response()->json($messages);
        return $this->display_messages($messages);
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
        $user=$this->authUser();
        $message=Message::find($id);
        if(!$message){
            return response()->json('message not found');
        }
        if($user->id == $message->user_from){
            $message->delete();
            return response()->json('message deleted');
        }
        
        return response()->json('unathorized', 401); 
    }
}
