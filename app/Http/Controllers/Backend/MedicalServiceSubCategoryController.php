<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MedicalServiceCategory;
use App\Models\MedicalServiceSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MedicalServiceSubCategoryController extends Controller
{
    public function index()
    {
        $subcategories = MedicalServiceSubCategory::with('category')->get();
        return view('backend.medicalservicesubcategory.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = MedicalServiceCategory::pluck('category_name', 'id');
        return view('backend.medicalservicesubcategory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:medical_service_categories,id',
            'subcategory_name' => 'required|string|max:255|unique:medical_service_sub_categories,subcategory_name',
        ]);

        MedicalServiceSubCategory::create([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'slug' => Str::slug($request->subcategory_name),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.medicalservicesubcategory.index')
            ->with('success', 'Medical Service Subcategory added!');
    }

    public function edit(MedicalServiceSubCategory $medicalservicesubcategory)
    {
        $categories = MedicalServiceCategory::pluck('category_name', 'id');
        return view('backend.medicalservicesubcategory.edit', compact('medicalservicesubcategory', 'categories'));
    }

    public function update(Request $request, MedicalServiceSubCategory $medicalservicesubcategory)
    {
        $request->validate([
            'category_id' => 'required|exists:medical_service_categories,id',
            'subcategory_name' => 'required|string|max:255|unique:medical_service_sub_categories,subcategory_name,' . $medicalservicesubcategory->id,
        ]);

        $medicalservicesubcategory->update([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'slug' => Str::slug($request->subcategory_name),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.medicalservicesubcategory.index')
            ->with('success', 'Medical Service Subcategory updated!');
    }

    public function destroy(MedicalServiceSubCategory $medicalservicesubcategory)
    {
        $medicalservicesubcategory->update(['deleted_by' => Auth::id()]);
        $medicalservicesubcategory->delete();

        return redirect()->route('admin.medicalservicesubcategory.index')
            ->with('success', 'Medical Service Subcategory deleted!');
    }
}