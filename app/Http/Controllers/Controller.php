<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function authUser()
    {
        try{
            $user=auth()->user();
        }
        catch(\Tymon\JWTAuth\Exception\UserNotDefinedException $e){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $user;
    }
}
