<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Major;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);
        $users = User::with(['userType', 'student', 'employee', 'roles'])->latest('id')->paginate(config('app.pagination_count', 10));
        return view('cms.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', User::class);
        $userTypes = UserType::all();
        $departments = Department::all();
        $majors = Major::all();
        return view('cms.users.create', compact('userTypes', 'departments', 'majors'));
    }
    public function getRolesByUserType($userTypeId)
    {
        $roles = Role::whereHas('userTypes', function ($query) use ($userTypeId) {
            $query->where('user_types.id', $userTypeId);
        })->select('id', 'name')->get();

        return response()->json($roles);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', User::class);
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'user_type_id' => 'required|exists:user_types,id',
            'user_name' => 'required|string|max:255|unique:users,user_name',
            'city' => 'required|string|in:gaza,khan_younis,rafah,jabalia,beit_hanun,beit_lahya,deir_al_balah,al_zawaid,al_nasirat,al_brij,al_mughazi',
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($request) {
                    $userTypeId = $request->input('user_type_id');
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

        if ($request->input('user_type_id') == 2) {
            $rules = array_merge($rules, [
                'id_number' => 'required|string|max:255|unique:students,id_number',
                'student_number' => 'required|string|max:255|unique:students,student_number',
                'major_id' => 'required|exists:majors,id',
            ]);
        } elseif ($request->input('user_type_id') == 3) {
            $rules = array_merge($rules, [
                'employee_number' => 'required|string|max:255|unique:employees,employee_number',
                'office_number' => 'nullable|string|max:255',
                'employee_type' => 'required|in:instructor,dean_head,department_head,assistant',
                'department_id' => 'required|exists:departments,id',
            ]);
        }
        $validatedData = $request->validate($rules);

        return DB::transaction(function () use ($request, $validatedData) {
            if ($request->hasFile('profile_image')) {
                $validatedData['profile_image'] = $request->file('profile_image')->store('uploads/profiles-images', 'custom');
            }
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'user_type_id' => $validatedData['user_type_id'],
                'user_name' => $validatedData['user_name'],
                'city' => $validatedData['city'],
                'phone_number' => $validatedData['phone_number'],
                'gender' => $validatedData['gender'],
                'is_active' => $request->boolean('is_active'),
                'profile_image' => $validatedData['profile_image'] ?? null,
            ]);
            $role = Role::find($validatedData['role']);
            $user->assignRole($role);
            if ($user->user_type_id == 2) {
                $user->student()->create([
                    'id_number' => $validatedData['id_number'],
                    'student_number' => $validatedData['student_number'],
                    'major_id' => $validatedData['major_id'],
                ]);
                $message = 'Student created successfully.';
            } elseif ($user->user_type_id == 3) {
                $user->employee()->create([
                    'employee_number' => $validatedData['employee_number'],
                    'office_number' => $validatedData['office_number'],
                    'employee_type' => $validatedData['employee_type'],
                    'department_id' => $validatedData['department_id'],
                ]);
                $message = 'Employee created successfully.';
            } else {
                $message = 'Admin created successfully.';
            }
            return response()->json([
                'message' => $message,
                'icon' => 'success'
            ], 201);
        });
    }




    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);
        $userTypes = UserType::all();
        $departments = Department::all();
        $majors = Major::all();
        return view('cms.users.show', compact('userTypes', 'departments', 'majors', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        Gate::authorize('update', $user);
        $userTypes = UserType::all();
        $departments = Department::all();
        $majors = Major::all();
        return view('cms.users.edit', compact('userTypes', 'departments', 'majors', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'user_type_id' => 'required|exists:user_types,id',
            'user_name' => ['required', 'string', 'max:255', Rule::unique('users', 'user_name')->ignore($user->id)],
            'city' => 'required|string|in:gaza,khan_younis,rafah,jabalia,beit_hanun,beit_lahya,deir_al_balah,al_zawaid,al_nasirat,al_brij,al_mughazi',
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($request) {
                    $userTypeId = $request->input('user_type_id');
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

        if ($request->input('user_type_id') == 2) {
            $rules = array_merge($rules, [
                'id_number' => ['required', 'string', 'max:255', Rule::unique('students', 'id_number')->ignore($user->student?->id)],
                'student_number' => ['required', 'string', 'max:255', Rule::unique('students', 'student_number')->ignore($user->student?->id)],
                'major_id' => 'required|exists:majors,id',
            ]);
        } elseif ($request->input('user_type_id') == 3) {
            $rules = array_merge($rules, [
                'employee_number' => ['required', 'string', 'max:255', Rule::unique('employees', 'employee_number')->ignore($user->employee?->id)],
                'office_number' => 'nullable|string|max:255',
                'employee_type' => 'required|in:instructor,dean_head,department_head,assistant',
                'department_id' => 'required|exists:departments,id',
            ]);
        }
        $validatedData = $request->validate($rules);

        return DB::transaction(function () use ($request, $validatedData, $user) {
            if ($request->hasFile('profile_image')) {
                if ($user->profile_image && File::exists(public_path($user->profile_image))) {
                    File::delete(public_path($user->profile_image));
                }
                $validatedData['profile_image'] = $request->file('profile_image')->store('uploads/profiles-images', 'custom');
            }

            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'user_type_id' => $validatedData['user_type_id'],
                'user_name' => $validatedData['user_name'],
                'city' => $validatedData['city'],
                'phone_number' => $validatedData['phone_number'],
                'gender' => $validatedData['gender'],
                'is_active' => $request->boolean('is_active'),
                'profile_image' => $validatedData['profile_image'] ?? $user->profile_image,
            ]);

            $role = Role::find($validatedData['role']);
            $user->syncRoles($role);

            if ($user->user_type_id == 2) {
                if ($user->employee) {
                    $user->employee()->delete();
                }
                $user->student()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'id_number' => $validatedData['id_number'],
                        'student_number' => $validatedData['student_number'],
                        'major_id' => $validatedData['major_id'],
                    ]
                );

                $message = 'Student updated successfully.';
            } elseif ($user->user_type_id == 3) {
                if ($user->student) {
                    $user->student()->delete();
                }

                $user->employee()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'employee_number' => $validatedData['employee_number'],
                        'office_number' => $validatedData['office_number'] ?? null,
                        'employee_type' => $validatedData['employee_type'],
                        'department_id' => $validatedData['department_id'],
                    ]
                );

                $message = 'Employee updated successfully.';
            } else {
                if ($user->student) {
                    $user->student()->delete();
                }
                if ($user->employee) {
                    $user->employee()->delete();
                }

                $message = 'Admin updated successfully.';
            }

            return response()->json([
                'message' => $message,
                'icon' => 'success',
                'redirect' => route('admin.users.index')
            ], 200);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        if ($user->profile_image && File::exists(public_path($user->profile_image))) {
            File::delete(public_path($user->profile_image));
        }
        if ($user->student) {
            $user->student()->delete();
        }
        if ($user->employee) {
            $user->employee()->delete();
        }
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully.',
            'icon' => 'success'
        ]);
    }
}
