<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Gate::authorize('viewAny', Category::class); هاي بنستخدمها لو عملت policy للcategory
        Gate::authorize('Index Category');
        $categories = Category::latest('id')->paginate(config('app.pagination_count', 10));
        return view('cms.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('Create Category');
        return view('cms.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('Create Category');
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Category::create([
            'title' => $validated['title'],
        ]);

        return response()->json([
            'message' => 'Category created successfully.',
            'icon' => 'success'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        Gate::authorize('Show Category');
        return view('cms.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        Gate::authorize('Edit Category');
        return view('cms.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        Gate::authorize('Edit Category');
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $category->update([
            'title' => $validated['title'],
        ]);

        return response()->json([
            'message' => 'Category updated successfully.',
            'icon' => 'success',
            'redirect' => route('admin.categories.index')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        Gate::authorize('Delete Category');
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.',
            'icon' => 'success'
        ], 200);
    }
}
