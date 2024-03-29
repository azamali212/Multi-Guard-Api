<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiResponser;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeacherLoginController extends Controller
{
    use ApiResponser;


    public function TeacherLogin(Request $request){
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->guard('teacher')->attempt($data)) {
            $user =  Teacher::where('email', $data['email'])->first();
            config(['auth.guards.api.provider' => 'teacher']);
            $token = auth()->guard('teacher')->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => Carbon::now()->addDays(7)->timestamp,
                'user' => $data,
                'roles'=>$user->getRoleNames(),
            ]);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }  
    }
}
