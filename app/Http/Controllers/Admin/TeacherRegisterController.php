<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
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

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $roleNames = $request->input('roles');
        $permissionsName = $request->input('permissions');
    
        $teachers = new Teacher();
        $teachers->name = $name;
        $teachers->email = $email;
        $teachers->password = bcrypt($password);
        $teachers->save();
    
        $roles = Role::whereIn('name', $roleNames)->get();
        $teachers->assignRole($roles);
        $permissions = Permission::whereIn('name',$permissionsName)->get();
        $teachers->givePermissionTo($permissions);
        return response()->json(['massege' => 'Teacher created successfully']);
    }

    public function show($id)
    {
        
        $teacher = Teacher::with('roles.permissions')->findOrFail($id);

        return response()->json(['teacher' => $teacher]);
       
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
