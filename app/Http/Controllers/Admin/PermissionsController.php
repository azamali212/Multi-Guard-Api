<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('can:PermissionShow', ['only' => ['index', 'show']]);
        $this->middleware('can:PermissionCreate', ['only' => ['create', 'store']]);
        $this->middleware('can:PermissionEdit', ['only' => ['edit', 'update']]);
        $this->middleware('can:PermissionDelete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $permissions = Permission::latest();
        if (request()->has('search')) {
            $permissions->where('name', 'Like', '%' . request()->input('search') . '%');
        }
        $permissions = $permissions->paginate(5);

        return response()->json([$permissions], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:'.config('permission.table_names.permissions', 'permissions').',name',
        ]);

        $permissions = Permission::create($request->all());

        return response()->json([$permissions], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $permissions = Permission::findById($id);
        return response()->json([
            'permissions' => $permissions,
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

        $permissions = Permission::findOrFail($id);

        $permissions->update([
            'name' => $validatedData['name'],
        ]);

        if ($request->has('permissions')) {
            $roles = Role::whereIn('id', $validatedData['roles'])->get();
            $permissions->roles()->sync($roles);
        }

        return response()->json(['permission' => $permissions], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permissions = Permission::find($id);

        $permissions->delete();
        if ($permissions) {

            return response()->json(['message' => 'Permission Deleted']);
        } else {

            return response()->json(['message' => 'Failed to Deleted Permission']);
        }
    }
}
