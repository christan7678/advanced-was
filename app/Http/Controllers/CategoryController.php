<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Only authenticated users can access CRUD operations.
     */
    public function __construct()
    {
        $this->middleware('auth:admin,web')->only(['index', 'show']);

        $this->middleware('auth:admin')->except(['index', 'show']);
    }

    /**
     * READ ALL - Display a listing of all categories.
     * GET /categories
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * CREATE FORM - Show the form to create a new category.
     * GET /categories/create
     */
    public function create()
    {
        if (Gate::allows('isAdmin')) {
            
        return view('categories.create');
        }
        return redirect()->route('categories.index')->with('error', 'You do not have permission to create categories.');
    }

    /**
     * STORE - Save a new category to the database.
     * POST /categories
     */
    public function store(Request $request)
    {
        if(Gate::allows('isAdmin')){
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create($request->only('name'));

        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
        }
        return redirect()->route('categories.index')->with('error', 'You do not have permission to create categories.');
    }

    /**
     * READ ONE - Display a specific category.
     * GET /categories/{id}
     */
    public function show(Category $category)
    {
        $category->load('events'); // Eager load events related to this category
        return view('categories.show', compact('category'));
    }

    /**
     * EDIT FORM - Show the form to edit an existing category.
     * GET /categories/{id}/edit
     */
    public function edit(Category $category)
    {
        if (Gate::allows('isAdmin')) {
            return view('categories.edit', compact('category'));
        }
        return redirect()->route('categories.index')->with('error', 'You do not have permission.');
    }

    /**
     * UPDATE - Save the updated category to the database.
     * PUT /categories/{id}
     */
    public function update(Request $request, Category $category)
    {
        if(Gate::allows('isAdmin')){
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update($request->only('name'));

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }
        return redirect()->route('categories.index')->with('error', 'You do not have permission to update categories.');
    }

    /**
     * DELETE - Remove a category from the database.
     * DELETE /categories/{id}
     */
    public function destroy(Category $category)
    {
        if (Gate::allows('isAdmin')){
        // Prevent deletion if category has events linked to it
            if ($category->events()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Cannot delete category — it has events linked to it.');
        }
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
        }
        return redirect()->route('categories.index')->with('error', 'You do not have permission to delete categories.');
    }
}
