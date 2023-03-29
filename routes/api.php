<?php

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\PermissionsController;
// use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StudentRegisterController;
use App\Http\Controllers\Admin\TeacherRegisterController;
use App\Http\Controllers\StudentLoginController;
use App\Http\Controllers\Teacher\TeacherLoginController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;


// Admin Route
Route::post('admin/login', [App\Http\Controllers\Admin\LoginController::class, 'AdminLogin']);
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin-api', 'role:api']], function () {

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
Route::group(['prefix' => 'teacher', 'middleware' => ['auth:teacher-api', 'role:teacher_api']], function () {
    Route::get('teacher', [TeacherRegisterController::class, 'index']);
    Route::get('student', [StudentRegisterController::class, 'index']);
    Route::post('student/store', [StudentRegisterController::class, 'create']);
    Route::get('student/{id}', [StudentRegisterController::class, 'show']);
    Route::put('student/{id}', [StudentRegisterController::class, 'update']);
    Route::delete('student/{id}', [StudentRegisterController::class, 'destory']);
});

//Student Route
Route::post('student/login', [StudentLoginController::class, 'StudentLogin']);
Route::group(['prefix' => 'student', 'middleware' => ['auth:student-api', 'role:student_api']], function () {
    Route::get('student', [StudentRegisterController::class, 'index']);
});
