<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('can:RoleShow', ['only' => ['index', 'show']]);
        $this->middleware('can:RoleCreate', ['only' => ['create', 'store']]);
        $this->middleware('can:RoleEdit', ['only' => ['edit', 'update']]);
        $this->middleware('can:RoleDelete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $roles = (new Role)->newQuery();
        if (request()->has('search')) {
            $roles->where('name', 'Like', '%' . request()->input('search') . '%');
        }

        $roles = $roles->paginate(5);

        return response()->json([$roles], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:roles|max:255',
            'permissions' => 'array|nullable',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $roles = Role::create([
            'name' => $validatedData['name'],
            'guard_name' => 'admin-api',
        ]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $validatedData['permissions'])->get();
            $roles->permissions()->sync($permissions);
        }

        return response()->json(['message' => 'Role created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $roles)
    {

        $permissions = Permission::all();
        $roleHasPermissions = array_column(json_decode($roles->permissions, true), 'id');
        return response()->json([
            'role' => $roles,
            'permissions' => $permissions,
            'role_has_permissions' => $roleHasPermissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
        ]);

        $roles = Role::findOrFail($id);

        $roles->update([
            'name' => $validatedData['name'],
        ]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $validatedData['permissions'])->get();
            $roles->permissions()->sync($permissions);
        }

        return response()->json(['role' => $roles], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $roles = Role::find($id);

        $roles->delete();
        if ($roles) {

            return response()->json(['message' => 'Role Deleted']);
        } else {

            return response()->json(['message' => 'Failed to Deleted Role']);
        }
    }
}
