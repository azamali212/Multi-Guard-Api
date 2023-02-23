<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Services\SearchAndPaginateService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TeacherRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::all();

        return response()->json(['teachers' => $teachers]);
    }

    public function create(Request $request)
    {

        //Validation
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ];

        $validation = $request->validate($rules);

        //Create Teacher
        $teachers = new Teacher;

        $teachers->name = $validation['name'];
        $teachers->email = $validation['email'];
        $teachers->password = $validation['password'];

        $teachers->save();

        $token = $teachers->createToken('access-toke')->accessToken;

        $teachers->assignRole('teacher_api');

        return response()->json([$token => 'Teacher created successfully']);
    }

    public function show($id)
    {

        $teachers = Teacher::find($id);

        if (!$teachers) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($teachers);
    }


    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->name = $request->input('name');
        $teacher->email = $request->input('email');

        if ($teacher->save()) {
            return response()->json(['message' => 'Teacher updated']);
        } else {
            return response()->json(['message' => 'Failed to update teacher']);
        }
    }

    public function destory($id)
    {
        $teachers = Teacher::find($id);

        $teachers->delete();

        if ($teachers) {

            return response()->json(['message' => 'Teacher Deleted']);
            
        } else {
            
            return response()->json(['message' => 'Failed to Deleted teacher']);
        }
    }
}
