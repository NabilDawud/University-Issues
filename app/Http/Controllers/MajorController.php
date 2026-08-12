<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Major::class);
        $majors = Major::with('department')->latest('id')->paginate(config('app.pagination_count', 10));
        return view('cms.majors.index', compact('majors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Major::class);
        $departments = Department::all();
        return view('cms.majors.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Major::class);
        $validatedData = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'degree' => 'required|in:bachelor,diploma,master,phd',
            'department_id' => 'required|exists:departments,id',
        ]);
        Major::create([
            'name' => [
                'en' => $validatedData['name_en'],
                'ar' => $validatedData['name_ar'],
            ],
            'degree' => $validatedData['degree'],
            'is_active' => $request->boolean('is_active'),
            'department_id' => $validatedData['department_id'],
        ]);
        return response()->json([
            'message' => 'Major created successfully.',
            'icon' => 'success'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Major $major)
    {
        Gate::authorize('view', $major);
        $departments = Department::all();
        return view('cms.majors.show', compact('major', 'departments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Major $major)
    {
        Gate::authorize('update', $major);
        $departments = Department::all();
        return view('cms.majors.edit', compact('major', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Major $major)
    {
        Gate::authorize('update', $major);
        $validatedData = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'degree' => 'required|in:bachelor,diploma,master,phd',
            'department_id' => 'required|exists:departments,id',
        ]);
        $major->update([
            'name' => [
                'en' => $validatedData['name_en'],
                'ar' => $validatedData['name_ar'],
            ],
            'degree' => $validatedData['degree'],
            'is_active' => $request->boolean('is_active'),
            'department_id' => $validatedData['department_id'],
        ]);
        return response()->json([
            'message' => 'Major updated successfully.',
            'icon' => 'success',
            'redirect' => route('admin.majors.index')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Major $major)
    {
        Gate::authorize('delete', $major);
        $major->delete();
        return response()->json([
            'message' => 'Major deleted successfully.',
            'icon' => 'success'
        ], 200);
    }
}
