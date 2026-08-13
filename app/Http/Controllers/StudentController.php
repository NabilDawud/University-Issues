<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAnyOfType', [User::class, 2]);
        $students = User::accessibleStudents()->where('user_type_id', 2)->with(['student', 'roles'])->latest('id')->paginate(config('app.pagination_count', 10));
        return view('cms.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('createOfType', [User::class, 2]);
        $roles = Role::whereHas('userTypes', function ($query) {
            $query->where('user_types.id', 2);
        })->get();
        $majors = Major::all();
        return view('cms.students.create', compact('roles', 'majors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('createOfType', [User::class, 2]);
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'user_name' => 'required|string|unique:users,user_name|max:255',
            'city' => 'required|string|in:gaza,khan_younis,rafah,jabalia,beit_hanun,beit_lahya,deir_al_balah,al_zawaid,al_nasirat,al_brij,al_mughazi',
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($request) {
                    $userTypeId = 2; // Student user type
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
            'id_number' => 'required|string|max:255|unique:students,id_number',
            'student_number' => 'required|string|max:255|unique:students,student_number',
            'major_id' => 'required|exists:majors,id',
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
                'user_type_id' => 2, // Student user type
                'user_name' => $validated['user_name'],
                'city' => $validated['city'],
                'phone_number' => $validated['phone_number'],
                'gender' => $validated['gender'],
                'is_active' => $request->boolean('is_active'),
                'profile_image' => $validated['profile_image'] ?? null,
            ]);
            $user->student()->create([
                'id_number' => $validated['id_number'],
                'student_number' => $validated['student_number'],
                'major_id' => $validated['major_id'],
            ]);
            $role = Role::find($validated['role']);
            $user->assignRole($role);
            return response()->json([
                'message' => 'Student created successfully.',
                'icon' => 'success'
            ], 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = User::where('user_type_id', 2)->with('student')->findOrFail($id);
        Gate::authorize('view', $student);
        $roles = Role::whereHas('userTypes', function ($query) {
            $query->where('user_types.id', 2);
        })->get();
        $majors = Major::all();
        return view('cms.students.show', compact('student', 'majors', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = User::where('user_type_id', 2)->with('student')->findOrFail($id);
        Gate::authorize('update', $student);
        $roles = Role::whereHas('userTypes', function ($query) {
            $query->where('user_types.id', 2);
        })->get();
        $majors = Major::all();
        return view('cms.students.edit', compact('student', 'roles', 'majors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = User::where('user_type_id', 2)->with('student')->findOrFail($id);
        Gate::authorize('update', $student);
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'user_name' => ['required', 'string', 'unique:users,user_name,' . $student->id, 'max:255'],
            'city' => 'required|string|in:gaza,khan_younis,rafah,jabalia,beit_hanun,beit_lahya,deir_al_balah,al_zawaid,al_nasirat,al_brij,al_mughazi',
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($request) {
                    $userTypeId = 2; // Student user type
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
            'id_number' => 'required|string|max:255|unique:students,id_number,' . ($student->student->id ?? ''),
            'student_number' => 'required|string|max:255|unique:students,student_number,' . ($student->student->id ?? ''),
            'major_id' => 'required|exists:majors,id',
        ];
        $validated = $request->validate($rules);
        return DB::transaction(function () use ($validated, $request, $student) {
            if ($request->hasFile('profile_image')) {
                if ($student->profile_image && File::exists(public_path($student->profile_image))) {
                    File::delete(public_path($student->profile_image));
                }
                $validated['profile_image'] = $request->file('profile_image')->store('uploads/profiles-images', 'custom');
            }
            $student->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'user_name' => $validated['user_name'],
                'city' => $validated['city'],
                'phone_number' => $validated['phone_number'],
                'gender' => $validated['gender'],
                'is_active' => $request->boolean('is_active'),
                'profile_image' => $validated['profile_image'] ?? null,
            ]);
            $student->student->update([
                'id_number' => $validated['id_number'],
                'student_number' => $validated['student_number'],
                'major_id' => $validated['major_id'],
            ]);
            $role = Role::find($validated['role']);
            $student->syncRoles([$role]);
            return response()->json([
                'message' => 'Student updated successfully.',
                'icon' => 'success',
                'redirect' => route('admin.students.index')
            ], 200);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = User::where('user_type_id', 2)->with('student')->findOrFail($id);
        Gate::authorize('delete', $student);
        if ($student->profile_image && File::exists(public_path($student->profile_image))) {
            File::delete(public_path($student->profile_image));
        }
        $student->student()->delete();
        $student->delete();
        return response()->json([
            'message' => 'Student deleted successfully.',
            'icon' => 'success'
        ], 200);
    }
}
