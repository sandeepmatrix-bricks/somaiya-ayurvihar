<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\KjshSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KjshSubCategoryController extends Controller
{
    public function index()
    {
        $subcategories = KjshSubCategory::with('category')->get();
        return view('backend.kjshsubcategory.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::pluck('category_name', 'id');
        return view('backend.kjshsubcategory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_name' => 'required|string|max:255|unique:kjsh_sub_categories,subcategory_name',
        ]);

        KjshSubCategory::create([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'slug' => Str::slug($request->subcategory_name),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.kjshsubcategory.index')
            ->with('success', 'KJSH Subcategory added successfully!');
    }

    public function edit(KjshSubCategory $kjshsubcategory)
    {
        $categories = Category::pluck('category_name', 'id');
        return view('backend.kjshsubcategory.edit', compact('kjshsubcategory', 'categories'));
    }

    public function update(Request $request, KjshSubCategory $kjshsubcategory)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_name' => 'required|string|max:255|unique:kjsh_sub_categories,subcategory_name,' . $kjshsubcategory->id,
        ]);

        $kjshsubcategory->update([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'slug' => Str::slug($request->subcategory_name),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.kjshsubcategory.index')
            ->with('success', 'KJSH Subcategory updated successfully!');
    }

    public function destroy(KjshSubCategory $kjshsubcategory)
    {
        $kjshsubcategory->update(['deleted_by' => Auth::id()]);
        $kjshsubcategory->delete();

        return redirect()->route('admin.kjshsubcategory.index')
            ->with('success', 'KJSH Subcategory deleted successfully!');
    }
}