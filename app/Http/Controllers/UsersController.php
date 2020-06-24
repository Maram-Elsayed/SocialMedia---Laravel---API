<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\hash;
use Illuminate\Http\Request;
use App\User;
use App\Post;
use DB;


class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register']]);
    }
 
    public function index()
    {
        
    }

    public function create_post($userId,$fileNameToStore, $request){
        $post = new Post;        
        $gender=User::select('gender')->where('id',$userId)->first();
        if($gender=='female'){
            $pronoun='her';
        } else{
            $pronoun='his';
        }
         
        $post->description ='updated '.$pronoun.' profile picture';
        $path = $request->file('profile_picture')->storeAs('public/cover_images', $fileNameToStore);
        $post->cover_image = $fileNameToStore;
        $post->user_id=$userId;
        $post->save();
    }
   
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'gender'=>'required',
            'birthday'=>'nullable|date|date_format:d/m/Y'
        ]);
       
        if(User::where('email',$request->input('email'))->count() > 0)
             return response()->json("email used" ,401);
        
        $user =new User;

        $user->name = $request->input('name');
        $user->email= $request->input('email');
        $user->gender= $request->input('gender');
        $user->birthday= $request->input('birthday');
        $user->password= bcrypt($request->input('password'));
        $user->profile_picture = null;
        $user->save();
  
        return response()->json([
            'message' => 'User created successfully',
            'Login' => 'http://localhost:8080/socialmedia-api/public/api/login',
        ]);

    }

    public function show($id)
    {
        $user=User::find($id);
        if(!$user)
           return response()->json("User not found",404);
        return response()->json($user,200);
    }

    public function edit($id)
    {
        //
    }

    public function update_profile_picture(Request $request)
    {
        $user=$this->authUser();
        $this->validate($request, [
            'profile_picture' => 'image|required|max:1999'
        ]);
        
        // Get filename with the extension
        $filenameWithExt = $request->file('profile_picture')->getClientOriginalName();
        // Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just ext
        $extension = $request->file('profile_picture')->getClientOriginalExtension();
        // Filename to store
        $fileNameToStore= $filename.'_'.time().'.'.$extension;
        // Upload Image
        $path = $request->file('profile_picture')->storeAs('public/profile_pictures', $fileNameToStore);
        
        Storage::delete('public/profile_picture/'.$user->profile_picture);
        $user->profile_picture='http://localhost:8080/socialmedia-api/storage/app/public/profile_pictures/'.$fileNameToStore;

        $this->create_post($user->id, $fileNameToStore, $request);
        $user->save();

        return response()->json($user,200);
       
    }

    public function update(Request $request)
    {
        $user=$this->authUser();
        $this->validate($request, [
            'birthday'=>'nullable|date|date_format:d/m/Y',
        ]);
       
        if($request->input('name')){
            $user->name=$request->input('name');
        }
        if($request->input('status')){
            $user->status=$request->input('status');
        }
        if($request->input('gender')){
            $user->gender=$request->input('gender');
        }
        if($request->input('birthday')){
            $user->birthday=$request->input('birthday');
        }
      $user->save();
      return response()->json($user,200);
       
    }

    public function remove_profile_picure(){
        $user=$this->authUser();
        Storage::delete('public/profile_picture/'.$user->profile_picture);
        $user->profile_picture=null;
        $user->save();
        return response()->json($user,200);
    }

    public function destroy($id)
    {
        //
    }
}
