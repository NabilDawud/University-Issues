<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Major;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['userType', 'student', 'employee'])->paginate(10);
        return view('cms.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userTypes = UserType::all();
        $departments = Department::all();
        $majors = Major::all();
        return view('cms.users.create', compact('userTypes', 'departments', 'majors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'user_type_id' => 'required|exists:user_types,id',
            'user_name' => 'required|string|max:255',
            'city' => 'required|string|in:gaza,khan_younis,rafah,jabalia,beit_hanun,beit_lahya,deir_al_balah,al_zawaid,al_nasirat,al_brij,al_mughazi',
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validatedData['user_type_id'] == 2) {
            $request->validate([
                'id_number' => 'required|string|max:255|unique:students,id_number',
                'student_number' => 'required|string|max:255|unique:students,student_number',
                'major_id' => 'required|exists:majors,id',
            ]);
        } else if ($validatedData['user_type_id'] == 3) {
            $request->validate([
                'employee_number' => 'required|string|max:255|unique:employees,employee_number',
                'office_number' => 'nullable|string|max:255',
                'employee_type' => 'required|in:instructor,dean_head,department_head,assistant',
                'department_id' => 'required|exists:departments,id',
            ]);
        }

        return DB::transaction(function () use ($request, $validatedData) {
            if ($request->hasFile('profile_image')) {
                $imagePath = $request->file('profile_image')->store('uploads/profiles-images', 'custom');
                $validatedData['profile_image'] = $imagePath ?? null;
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
                'profile_image' => $validatedData['profile_image']
            ]);
            if ($user->user_type_id == 2) {
                $user->student()->create([
                    'id_number' => $request->input('id_number'),
                    'student_number' => $request->input('student_number'),
                    'major_id' => $request->input('major_id'),
                ]);
                return response()->json([
                    'message' => 'Student created successfully.',
                    'icon' => 'success'
                ], 201);
            } elseif ($user->user_type_id == 3) {
                $user->employee()->create([
                    'employee_number' => $request->input('employee_number'),
                    'office_number' => $request->input('office_number'),
                    'employee_type' => $request->input('employee_type'),
                    'department_id' => $request->input('department_id'),
                ]);
                return response()->json([
                    'message' => 'Employee created successfully.',
                    'icon' => 'success'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Admin created successfully.',
                    'icon' => 'success'
                ], 201);
            }

        });
    }




    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'user_type_id' => 'required|exists:user_types,id',
            'user_name' => 'required|string|max:255',
            'city' => 'required|string|in:gaza,khan_younis,rafah,jabalia,beit_hanun,beit_lahya,deir_al_balah,al_zawaid,al_nasirat,al_brij,al_mughazi',
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validatedData['user_type_id'] == 2) {
            $request->validate([
                'id_number' => ['required', 'string', 'max:255', Rule::unique('students', 'id_number')->ignore($user->student?->id)],
                'student_number' => ['required', 'string', 'max:255', Rule::unique('students', 'student_number')->ignore($user->student?->id)],
                'major_id' => 'required|exists:majors,id',
            ]);
        } else if ($validatedData['user_type_id'] == 3) {
            $request->validate([
                'employee_number' => ['required', 'string', 'max:255', Rule::unique('employees', 'employee_number')->ignore($user->employee?->id)],
                'office_number' => 'nullable|string|max:255',
                'employee_type' => 'required|in:instructor,dean_head,department_head,assistant',
                'department_id' => 'required|exists:departments,id',
            ]);
        }

        return DB::transaction(function () use ($request, $validatedData, $user) {
            if ($request->hasFile('profile_image')) {
                File::delete(public_path($user->profile_image));
                $imagePath = $request->file('profile_image')->store('uploads/profiles-images', 'custom');
                $validatedData['profile_image'] = $imagePath;
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
                'profile_image' => $validatedData['profile_image'] ?? $user->profile_image
            ]);
            if ($user->user_type_id == 2) {
                if ($user->employee) {
                    $user->employee()->delete();
                }
                $user->student()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'id_number' => $request->input('id_number'),
                        'student_number' => $request->input('student_number'),
                        'major_id' => $request->input('major_id'),
                    ]
                );
                return response()->json([
                    'message' => 'Student created successfully.',
                    'icon' => 'success',
                    'redirect' => route('admin.users.index')
                ], 201);
            } elseif ($user->user_type_id == 3) {
                if ($user->student) {
                    $user->student()->delete();
                }
                $user->employee()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'employee_number' => $request->input('employee_number'),
                        'office_number' => $request->input('office_number'),
                        'employee_type' => $request->input('employee_type'),
                        'department_id' => $request->input('department_id'),
                    ]
                );
                return response()->json([
                    'message' => 'Employee created successfully.',
                    'icon' => 'success',
                    'redirect' => route('admin.users.index')
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Admin created successfully.',
                    'icon' => 'success',
                    'redirect' => route('admin.users.index')

                ], 201);
            }

        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        File::delete(public_path($user->profile_image));
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully.',
            'icon' => 'success'
        ]);
    }
}
