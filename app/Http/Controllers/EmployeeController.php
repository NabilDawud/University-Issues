<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAnyOfType', [User::class, 3]);
        $employees = User::accessibleEmployees()->where('user_type_id', 3)->with(['roles', 'employee.department'])->latest('id')->paginate(config('app.pagination_count', 10));
        return view('cms.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('createOfType', [User::class, 3]);
        $roles = Role::whereHas('userTypes', function ($query) {
            $query->where('user_types.id', 3);
        })->get();
        $departments = \App\Models\Department::all();
        return view('cms.employees.create', compact('roles', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('createOfType', [User::class, 3]);
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
                    $userTypeId = 3; // Employee user type
                    $isRoleValidForType = Role::where('id', $value)
                        ->whereHas('userTypes', function ($query) use ($userTypeId) {
                        $query->where('user_types.id', $userTypeId);
                    })
                        ->exists();
                    if (!$isRoleValidForType) {
                        $fail('The selected role is not valid for the chosen user type.');
                    }
                }
            ],
            'employee_number' => 'required|string|max:255|unique:employees,employee_number',
            'office_number' => 'nullable|string|max:255',
            'employee_type' => 'required|in:instructor,dean_head,department_head,assistant',
            'department_id' => 'required|exists:departments,id',
        ];

        $validated = $request->validate($rules);

        return DB::transaction(function () use ($validated, $request) {
            if ($request->hasFile('profile_image')) {
                $validated['profile_image'] = $request->file('profile_image')->store('uploads/profiles-images', 'custom');
            }
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'user_type_id' => 3, // Employee user type
                'user_name' => $validated['user_name'],
                'city' => $validated['city'],
                'phone_number' => $validated['phone_number'],
                'gender' => $validated['gender'],
                'is_active' => $request->boolean('is_active'),
                'profile_image' => $validated['profile_image'] ?? null,
            ]);
            $user->employee()->create([
                'employee_number' => $validated['employee_number'],
                'office_number' => $validated['office_number'],
                'employee_type' => $validated['employee_type'],
                'department_id' => $validated['department_id'],
            ]);
            $role = Role::find($validated['role']);
            $user->assignRole($role);
            return response()->json([
                'message' => 'Employee created successfully.',
                'icon' => 'success'
            ], 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = User::where('user_type_id', 3)->with(['employee.department'])->findOrFail($id);
        Gate::authorize('view', $employee);
        $roles = Role::whereHas('userTypes', function ($query) {
            $query->where('user_types.id', 3);
        })->get();
        $departments = \App\Models\Department::all();
        return view('cms.employees.show', compact('employee', 'roles', 'departments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = User::where('user_type_id', 3)->with(['employee.department'])->findOrFail($id);
        Gate::authorize('update', $employee);
        $roles = Role::whereHas('userTypes', function ($query) {
            $query->where('user_types.id', 3);
        })->get();
        $departments = \App\Models\Department::all();
        return view('cms.employees.edit', compact('employee', 'roles', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = User::where('user_type_id', 3)->with(['employee.department'])->findOrFail($id);
        Gate::authorize('update', $employee);
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'user_name' => 'required|string|max:255',
            'city' => 'required|string|in:gaza,khan_younis,rafah,jabalia,beit_hanun,beit_lahya,deir_al_balah,al_zawaid,al_nasirat,al_brij,al_mughazi',
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($request) {
                    $userTypeId = 3; // Employee user type
                    $isRoleValidForType = Role::where('id', $value)
                        ->whereHas('userTypes', function ($query) use ($userTypeId) {
                        $query->where('user_types.id', $userTypeId);
                    })
                        ->exists();
                    if (!$isRoleValidForType) {
                        $fail('The selected role is not valid for the chosen user type.');
                    }
                }
            ],
            'employee_number' => 'required|string|max:255|unique:employees,employee_number,' . $employee->employee->id ?? '',
            'office_number' => 'nullable|string|max:255',
            'employee_type' => 'required|in:instructor,dean_head,department_head,assistant',
            'department_id' => 'required|exists:departments,id',
        ];
        $validated = $request->validate($rules);
        return DB::transaction(function () use ($validated, $request, $employee) {
            if ($request->hasFile('profile_image')) {
                if ($employee->profile_image && File::exists(public_path($employee->profile_image))) {
                    File::delete(public_path($employee->profile_image));
                }
                $validated['profile_image'] = $request->file('profile_image')->store('uploads/profiles-images', 'custom');
            }
            $employee->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'user_name' => $validated['user_name'],
                'city' => $validated['city'],
                'phone_number' => $validated['phone_number'],
                'gender' => $validated['gender'],
                'is_active' => $request->boolean('is_active'),
                'profile_image' => $validated['profile_image'] ?? null,
            ]);
            $employee->employee->update([
                'employee_number' => $validated['employee_number'],
                'office_number' => $validated['office_number'],
                'employee_type' => $validated['employee_type'],
                'department_id' => $validated['department_id'],
            ]);
            $role = Role::find($validated['role']);
            $employee->syncRoles([$role]);
            return response()->json([
                'message' => 'Employee updated successfully.',
                'icon' => 'success',
                'redirect' => route('admin.employees.index')
            ], 200);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = User::where('user_type_id', 3)->with('employee')->findOrFail($id);
        Gate::authorize('delete', $employee);
        if ($employee->profile_image && File::exists(public_path($employee->profile_image))) {
            File::delete(public_path($employee->profile_image));
        }
        $employee->employee()->delete();
        $employee->delete();
        return response()->json([
            'message' => 'Employee deleted successfully.',
            'icon' => 'success'
        ], 200);
    }
}
