<?php

namespace App\Http\Controllers;

use App\Models\Deanship;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::with('deanship')->latest('id')->paginate(env('PAGINATION_COUNT', 10));
        return view('cms.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $deanships = Deanship::all();
        return view('cms.departments.create', compact('deanships'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|numeric|digits:4|unique:departments,code',
            'email' => 'required|email|max:255|unique:departments,email',
            'extension_number' => 'nullable|string|max:255',
            'office_number' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deanship_id' => 'required|exists:deanships,id',
        ]);
        Department::create([
            'name' => [
                'en' => $validatedData['name_en'],
                'ar' => $validatedData['name_ar'],
            ],
            'code' => $validatedData['code'],
            'email' => $validatedData['email'],
            'extension_number' => $validatedData['extension_number'] ?? null,
            'office_number' => $validatedData['office_number'],
            'is_active' => $request->boolean('is_active'),
            'description' => $validatedData['description'] ?? null,
            'deanship_id' => $validatedData['deanship_id'],
        ]);
        return response()->json([
            'message' => 'Department created successfully.',
            'icon' => 'success',
        ], 201);
    }   

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        $deanships = Deanship::all();
        return view('cms.departments.show', compact('department', 'deanships'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $deanships = Deanship::all();
        return view('cms.departments.edit', compact('department', 'deanships'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validatedData = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => [
                'required',
                'numeric',
                'digits:4',
                Rule::unique('departments', 'code')->ignore($department->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('departments', 'email')->ignore($department->id),
            ],
            'extension_number' => 'nullable|string|max:255',
            'office_number' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deanship_id' => 'required|exists:deanships,id',
        ]);
        $department->update([
            'name' => [
                'en' => $validatedData['name_en'],
                'ar' => $validatedData['name_ar'],
            ],
            'code' => $validatedData['code'],
            'email' => $validatedData['email'],
            'extension_number' => $validatedData['extension_number'] ?? null,
            'office_number' => $validatedData['office_number'],
            'is_active' => $request->boolean('is_active'),
            'description' => $validatedData['description'] ?? null,
            'deanship_id' => $validatedData['deanship_id'],
        ]);
        return response()->json([
            'message' => 'Department updated successfully.',
            'icon' => 'success',
            'redirect' => route('admin.departments.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json([
            'message' => 'Department deleted successfully.',
            'icon' => 'success',
        ]);
    }
}
