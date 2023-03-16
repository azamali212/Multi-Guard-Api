<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiResponser;
use App\Models\Teacher;
use Carbon\Carbon;
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
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => Carbon::now()->addDays(7)->timestamp,
                'user' => $data,
            ]);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }  
    }

    
}
