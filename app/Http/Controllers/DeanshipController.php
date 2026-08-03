<?php

namespace App\Http\Controllers;

use App\Models\Deanship;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\Rule;

use function Pest\Laravel\delete;

class DeanshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deanships = Deanship::latest('id')->withCount('departments')->paginate(config('app.pagination_count', 10));
        return view('cms.deanships.index', compact('deanships'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.deanships.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|numeric|digits:4|unique:deanships,code',
            'email' => 'required|email|unique:deanships,email',
            'extension_number' => 'nullable|string|max:20',
            'office_number' => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        Deanship::create([
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
        ]);

        return response()->json([
            'message' => 'Deanship created successfully.',
            'icon' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Deanship $deanship)
    {
        return view('cms.deanships.show', compact('deanship'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deanship $deanship)
    {
        return view('cms.deanships.edit', compact('deanship'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deanship $deanship)
    {
        // dd($request->all() , $request->boolean('is_active'));
        $validatedData = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => [
                'required',
                'numeric',
                'digits:4',
                Rule::unique('deanships', 'code')->ignore($deanship->id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('deanships', 'email')->ignore($deanship->id)
            ],
            'extension_number' => 'nullable|string|max:20',
            'office_number' => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        $deanship->update([
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
        ]);

        return response()->json([
            'message' => 'Deanship created successfully.',
            'icon' => 'success',
            'redirect' => route('admin.deanships.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deanship $deanship)
    {
        $deanship->delete();
        return response()->json([
            'message' => 'Deanship deleted successfully.',
            'icon' => 'success'
        ]);
    }
}
