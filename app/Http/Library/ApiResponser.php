<?php 

namespace App\Http\Library;
use Illuminate\Http\JsonResponse;

trait ApiResponser{

    /**
     * @void bool
     */

   //Success Message For Fornt end
   protected function onSuccess($data,string $message='', int $code = 200): JsonResponse
   {
       return response()->json([
           'status' => $code,
           'message' => $message,
           'data' => $data,
       ], $code);
   }

    //Error Message 
    protected function onError(int $code, string $message = ''): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
        ], $code);
    }
    
    protected function userValidationRule(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
