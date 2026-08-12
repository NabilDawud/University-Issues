<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Role::class);
        $roles = Role::latest()->with(['userTypes'])->withCount(['permissions', 'users'])->paginate(config('app.pagination_count', 10));
        return view('cms.spatie.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Role::class);
        $users_type = UserType::all();
        return view('cms.spatie.roles.create', compact('users_type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Role::class);
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'user_type' => 'required|array|min:1', 
            'user_type.*' => 'exists:user_types,id', 
        ]);
        DB::transaction(function () use ($request) {
            $role = Role::create([
                'name' => $request->input('name'),
                'guard_name' => 'web',
            ]);

            $userTypeIds = $request->input('user_type'); // قد تكون ID فردي أو Array من الـ IDs
            $role->userTypes()->attach($userTypeIds);
        });


        return response()->json(['message' => 'Role created successfully.', 'icon' => 'success'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('view', Role::class);
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::with(['userTypes'])->findOrFail($id);
        Gate::authorize('update', $role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        Gate::authorize('delete', $role);
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully.', 'icon' => 'success'], 200);
    }
    public function showPermissionsRole(Role $role)
    {
        Gate::authorize('showPermissionsRole', $role);
        $rolePermissions = $role->permissions()->pluck('name')->toArray();
        $allPermissions = Permission::all();
        return view('cms.spatie.roles.role-permissions', compact('role', 'rolePermissions', 'allPermissions'));
    }
    public function updatePermissionsRole(Request $request, Role $role)
    {
        Gate::authorize('updatePermissionsRole', $role);
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return response()->json(['message' => 'Role permissions updated successfully.', 'icon' => 'success'], 200);
    }
}
