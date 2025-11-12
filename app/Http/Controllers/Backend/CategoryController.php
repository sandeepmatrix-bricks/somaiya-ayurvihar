<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('backend.kjshcategory.index', compact('categories'));
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('backend.kjshcategory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
        ]);

        // Generate unique slug
        $slug = Str::slug($request->category_name, '-');

        // Create the category
        Category::create([
            'category_name' => $request->category_name,
            'slug'          => $slug,
            'created_by'    => Auth::id(),
        ]);

        // Success message + redirect to admin list
        return redirect()
            ->route('admin.kjshcategory.index')
            ->with('success', 'Category added successfully!');
    }

    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        // Return the edit view with category data
        return view('backend.kjshcategory.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $id,
        ]);

        $slug = Str::slug($request->category_name, '-');

        $category = Category::findOrFail($id);

        $category->update([
            'category_name' => $request->category_name,
            'slug' => $slug,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.category.index')->with('message', 'Product Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->update([
            'deleted_by' => Auth::id(),
        ]);

        $category->delete();

        return redirect()->route('admin.category.index')
            ->with('message', 'Product Category deleted successfully!');
    }
}
