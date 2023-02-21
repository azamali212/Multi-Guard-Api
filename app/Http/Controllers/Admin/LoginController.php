<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiResponser;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use ApiResponser;

    public function AdminLogin(Request $request){
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->guard('admin')->attempt($data)) {
            config(['auth.guards.api.provider' => 'admin']);
            $token = auth()->guard('admin')->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }  
    }
}
