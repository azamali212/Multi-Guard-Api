<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StudentRegisterController extends Controller
{
    public function index(Request $request)
    {
        $studentSearch = Student::query();
        //dd($studentSearch);

        if ($request->has('name')) {
            $studentSearch->where('name', 'like', '%' . $request->input('name') . '%');
        }

        // Paginate the results
        $students = $studentSearch->paginate(10);
        return response()->json(['students' => $students]);
    }

    public function create(Request $request)
    {

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $roleNames = $request->input('role');
        $permissionNames = $request->input('permissions');

        $student = new Student();
        $student->name = $name;
        $student->email = $email;
        $student->password = bcrypt($password);
        $student->save();
        if ($roleNames === null) {
            $roles = Role::where('name', 'student')->get();
        } else {
            $roles = Role::whereIn('name', $roleNames)->get();
        }
        //dd($roleNames);
        $permissions = Permission::whereIn('name', $permissionNames)->get();

        // Assign the role and permissions to the student
        $student->assignRole($roles);
        $student->givePermissionTo($permissions);

        return response()->json(['message' => 'Student created successfully']);
    }

    public function show($id)
    {

        $students = Student::with('roles')->findOrFail($id);

        return response()->json(['students' => $students]);
    }

    public function update(Request $request, $id)
    {
        $students = Student::findOrFail($id);
        $students->name = $request->input('name');
        $students->email = $request->input('email');

        if ($students->save()) {
            return response()->json(['message' => 'Student updated']);
        } else {
            return response()->json(['message' => 'Failed to update Student']);
        }
    }

    public function destory($id)
    {
        $students = Student::find($id);

        $students->delete();

        if ($students) {

            return response()->json(['message' => 'students Deleted']);
        } else {

            return response()->json(['message' => 'Failed to Deleted students']);
        }
    }
}
