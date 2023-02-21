<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiResponser;
use App\Models\User;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    use ApiResponser;

    public function AdminRegister(Request $request)
    {
        
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('access-toke')->accessToken;

        return response()->json(['token' => $token], 200);
    }
}
