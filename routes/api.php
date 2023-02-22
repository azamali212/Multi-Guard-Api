<?php

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\TeacherRegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Admin Route
//Route::post('admin/create',[RegisterController::class,'AdminRegister']);
Route::post('admin/login',[App\Http\Controllers\Admin\LoginController::class,'AdminLogin']);
//Route::get('teacher',[TeacherRegisterController::class,'teacher']);

Route::group(['prefix'=>'admin','middleware'=> ['auth:admin-api']],function(){
   
    Route::get('teacher',[TeacherRegisterController::class,'index']);
});
    



