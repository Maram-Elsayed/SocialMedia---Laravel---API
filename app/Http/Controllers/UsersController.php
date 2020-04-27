<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\hash;
use Illuminate\Http\Request;
use App\User;
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
   
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'gender'=>'required',
            'birthday'=>'nullable|date|date_format:d/m/Y',
            'profile_picture' => 'image|nullable|max:1999'
        ]);
       
        if(User::where('email',$request->input('email'))->count() > 0)
             return response()->json("email used" ,401);
        
        $user =new User;
        if($request->hasFile('profile_picture')){
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
        } else {
            $fileNameToStore = null;
        }

        $user->name = $request->input('name');
        $user->email= $request->input('email');
        $user->gender= $request->input('gender');
        $user->gender= $request->input('birthday');
        $user->password= bcrypt($request->input('password'));
        $user->profile_picture = $fileNameToStore;
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

    public function update(Request $request)
    {
        $user=$this->authUser();
        if($request->hasFile('profile_picture')){   
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
        } 
        if($request->input('name')){
            $user->name=$request->input('name');
        }
        if($request->input('status')){
            $user->status=$request->input('status');
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
