<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = User::with(['roles'])->where('user_type_id', 1)->latest('id')->paginate(config('app.pagination_count', 10));
        return view('cms.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::whereHas('userTypes', function ($query) {
            $query->where('user_types.id', 1);
        })->get();
        return view('cms.admins.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'user_name' => 'required|string|max:255',
            'city' => 'required|string|in:gaza,khan_younis,rafah,jabalia,beit_hanun,beit_lahya,deir_al_balah,al_zawaid,al_nasirat,al_brij,al_mughazi',
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($request) {
                    $userTypeId = 1; // Admin user type
                    $isRoleValidForType = Role::where('id', $value)
                        ->whereHas('userTypes', function ($query) use ($userTypeId) {
                        $query->where('user_types.id', $userTypeId);
                    })
                        ->exists();

                    if (!$isRoleValidForType) {
                        $fail('The selected role is not valid for the chosen user type.');
                    }
                },
            ],
        ];
        $validated = $request->validate($rules);
        return DB::transaction(function () use ($validated, $request) {
            if ($request->hasFile('profile_image')) {
                $validated['profile_image'] = $request->file('profile_image')->store('uploads/profiles-images', 'custom');
            }
            $admin = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'user_type_id' => 1, // Admin user type
                'user_name' => $validated['user_name'],
                'city' => $validated['city'],
                'phone_number' => $validated['phone_number'],
                'gender' => $validated['gender'],
                'is_active' => $request->boolean('is_active'),
                'profile_image' => $validated['profile_image'] ?? null,
            ]);
            $role = Role::find($validated['role']);
            $admin->assignRole($role);
            return response()->json([
                'message' => 'Admin created successfully.',
                'icon' => 'success'
            ], 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $admin = User::with(['roles'])->where('user_type_id', 1)->findOrFail($id);
        $roles = Role::whereHas('userTypes', function ($query) {
            $query->where('user_types.id', 1);
        })->get();
        return view('cms.admins.show', compact('admin', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = User::with(['roles'])->where('user_type_id', 1)->findOrFail($id);
        $roles = Role::whereHas('userTypes', function ($query) {
            $query->where('user_types.id', 1);
        })->get();
        return view('cms.admins.edit', compact('admin', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = User::with(['roles'])->where('user_type_id', 1)->findOrFail($id);
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'user_name' => 'required|string|max:255',
            'city' => 'required|string|in:gaza,khan_younis,rafah,jabalia,beit_hanun,beit_lahya,deir_al_balah,al_zawaid,al_nasirat,al_brij,al_mughazi',
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($request) {
                    $userTypeId = 1; // Admin user type
                    $isRoleValidForType = Role::where('id', $value)
                        ->whereHas('userTypes', function ($query) use ($userTypeId) {
                        $query->where('user_types.id', $userTypeId);
                    })
                        ->exists();

                    if (!$isRoleValidForType) {
                        $fail('The selected role is not valid for the chosen user type.');
                    }
                },
            ],
        ];
        $validated = $request->validate($rules);
        return DB::transaction(function () use ($validated, $request, $admin) {
            if ($request->hasFile('profile_image')) {
                if ($admin->profile_image && File::exists(public_path($admin->profile_image))) {
                    File::delete(public_path($admin->profile_image));
                }
                $validated['profile_image'] = $request->file('profile_image')->store('uploads/profiles-images', 'custom');
            }
            $admin->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'user_name' => $validated['user_name'],
                'city' => $validated['city'],
                'phone_number' => $validated['phone_number'],
                'gender' => $validated['gender'],
                'is_active' => $request->boolean('is_active'),
                'profile_image' => $validated['profile_image'] ?? $admin->profile_image,
            ]);
            $role = Role::find($validated['role']);
            $admin->syncRoles($role);
            return response()->json([
                'message' => 'Admin updated successfully.',
                'icon' => 'success',
                'redirect' => route('admin.admins.index')
            ], 200);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = User::with(['roles'])->where('user_type_id', 1)->findOrFail($id);
        if ($admin->profile_image && File::exists(public_path($admin->profile_image))) {
            File::delete(public_path($admin->profile_image));
        }
        $admin->delete();
        return response()->json([
            'message' => 'Admin deleted successfully.',
            'icon' => 'success'
        ], 200);
    }
}
