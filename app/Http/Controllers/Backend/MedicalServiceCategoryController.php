<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MedicalServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MedicalServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = MedicalServiceCategory::latest()->paginate(10);
        return view('backend.medicalservicecategory.index', compact('categories'));
    }

    public function create() { return view('backend.medicalservicecategory.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:medical_service_categories,category_name',
        ]);

        MedicalServiceCategory::create([
            'category_name' => $request->category_name,
            'slug' => Str::slug($request->category_name),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.medicalservicecategory.index')
            ->with('success', 'Medical Service Category added!');
    }

    public function edit(MedicalServiceCategory $medicalservicecategory)
    {
        return view('backend.medicalservicecategory.edit', compact('medicalservicecategory'));
    }

    public function update(Request $request, MedicalServiceCategory $medicalservicecategory)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:medical_service_categories,category_name,' . $medicalservicecategory->id,
        ]);

        $medicalservicecategory->update([
            'category_name' => $request->category_name,
            'slug' => Str::slug($request->category_name),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.medicalservicecategory.index')
            ->with('success', 'Medical Service Category updated!');
    }

    public function destroy(MedicalServiceCategory $medicalservicecategory)
    {
        $medicalservicecategory->update(['deleted_by' => Auth::id()]);
        $medicalservicecategory->delete();

        return redirect()->route('admin.medicalservicecategory.index')
            ->with('success', 'Medical Service Category deleted!');
    }
}