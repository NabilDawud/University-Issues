<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Permission::class);
        // Gate::authorize('Index Permission');
        $permissions = Permission::withCount(['roles', 'users'])->latest()->paginate(config('app.pagination_count', 10));
        return view('cms.spatie.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Permission::class);
        return view('cms.spatie.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Permission::class);
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $request->input('name'),
            'guard_name' => 'web',
        ]);

        return response()->json(['message' => 'Permission created successfully.', 'icon' => 'success'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('view', Permission::class);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);
        Gate::authorize('update', Permission::class);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $permission = Permission::findOrFail($id);
        Gate::authorize('update', Permission::class);

        // $request->validate([
        //     'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        // ]);

        // $permission->update([
        //     'name' => $request->input('name'),
        // ]);

        // return response()->json(['message' => 'Permission updated successfully.', 'icon' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        Gate::authorize('delete', $permission);
        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully.', 'icon' => 'success'], 200);
    }
}
