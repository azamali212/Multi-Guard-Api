<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiResponser;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentLoginController extends Controller
{
    use ApiResponser;

    public function StudentLogin(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->guard('student')->attempt($data)) {
            $user = Student::where('email', $data['email'])->first();
            $token = auth()->guard('student')->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => Carbon::now()->addDays(7)->timestamp,
                'user' => $data,
                'roles' => $user->getRoleNames(),
            ]);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
