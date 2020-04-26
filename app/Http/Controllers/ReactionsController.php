<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Reaction;
use DB;

class ReactionsController extends Controller
{

       
    public function index()
    {
        $reactions=Reaction::all();
        if($reactions->count()<1){
            return response()->json('No reactions found');
        }
        return response()->json($reactions);
    }

    
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        Reaction::create(['name'=>$request->name]);
        return $this->index();
    }

    
    public function show($id)
    {
        
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
        $reactions=Reaction::find($id);
        $reactions->delete();
        return $this->index();
    }
}
