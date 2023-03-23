<?php

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\PermissionsController;
// use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StudentRegisterController;
use App\Http\Controllers\Admin\TeacherRegisterController;
use App\Http\Controllers\Teacher\TeacherLoginController;
// use Illuminate\Http\Request;
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
Route::post('admin/login', [App\Http\Controllers\Admin\LoginController::class, 'AdminLogin']);
Route::group(['prefix' => 'admin', ['middleware' => 'auth:admin-api','role:api']], function () {

    Route::get('teacher', [TeacherRegisterController::class, 'index']);
    Route::post('teacher/store', [TeacherRegisterController::class, 'create']);
    Route::get('teacher/{id}', [TeacherRegisterController::class, 'show']);
    Route::put('teacher/{id}', [TeacherRegisterController::class, 'update']);
    Route::delete('teacher/{id}', [TeacherRegisterController::class, 'destory']);
    Route::apiResource('role', RoleController::class);
    Route::apiResource('permission', PermissionsController::class);
    Route::get('student', [StudentRegisterController::class, 'index']);
    Route::post('student/store', [StudentRegisterController::class, 'create']);
    Route::get('student/{id}', [StudentRegisterController::class, 'show']);
    Route::put('student/{id}', [StudentRegisterController::class, 'update']);
    Route::delete('student/{id}', [StudentRegisterController::class, 'destory']);
});

//Teacher Route
Route::post('teacher/login', [TeacherLoginController::class, 'TeacherLogin']);
Route::group(['prefix' => 'teacher', ['middleware' => 'auth:tecaher-api','role:api,teacher_api']], function () {
    Route::get('student', [StudentRegisterController::class, 'index']);
    Route::post('student/store', [StudentRegisterController::class, 'create']);
    Route::get('student/{id}', [StudentRegisterController::class, 'show']);
    Route::put('student/{id}', [StudentRegisterController::class, 'update']);
    Route::delete('student/{id}', [StudentRegisterController::class, 'destory']);
});
