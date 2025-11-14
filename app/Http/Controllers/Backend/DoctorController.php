<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\MedicalServiceSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\DoctorFormRequest;
use DB;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('subcategory')->get();
        return view('backend.doctors.index', compact('doctors'));
    }

    public function create()
    {
        $subcategories = MedicalServiceSubCategory::pluck('subcategory_name', 'id');
        $degrees = DB::table('degrees')->get();
        return view('backend.doctors.create', compact('subcategories','degrees'));
    }

    public function store(DoctorFormRequest $request)
    {
        $validated = $request->validated();
       
        $profile = $request->file('profile_image')->store('doctors/profile', 'public');
        $video = $request->hasFile('short_video') 
            ? $request->file('short_video')->store('doctors/video', 'public') 
            : null;

        $timings = array_filter([
            'morning' => $request->morning_time,
            'evening' => $request->evening_time
        ]);
    
        Doctor::create(array_merge($validated, [
            'profile_image' => $profile,
            'short_video' => $video,
            'consultation_timings' => !empty($timings) ? $timings : null,
            'created_by' => auth()->id(),
        ]));

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor added successfully!');
    }

    public function edit(Doctor $doctor)
    {
        $subcategories = MedicalServiceSubCategory::pluck('subcategory_name', 'id');
        $degrees = DB::table('degrees')->get();
        return view('backend.doctors.edit', compact('doctor', 'subcategories','degrees'));
    }

    public function update(DoctorFormRequest $request, Doctor $doctor)
    {
        $validated = $request->validated();
        dd($request->all());
        if ($request->hasFile('profile_image')) {
            Storage::disk('public')->delete($doctor->profile_image);
            $validated['profile_image'] = $request->file('profile_image')->store('doctors/profile', 'public');
        }

        if ($request->hasFile('short_video')) {
            Storage::disk('public')->delete($doctor->short_video);
            $validated['short_video'] = $request->file('short_video')->store('doctors/video', 'public');
        }

        $timings = array_filter([
            'morning' => $request->morning_time,
            'evening' => $request->evening_time
        ]);
        $validated['consultation_timings'] = !empty($timings) ? $timings : null;
        $validated['updated_by'] = auth()->id();

        $doctor->update($validated);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor updated successfully!');
    }

    public function destroy(Doctor $doctor)
    {
        collect([$doctor->profile_image, $doctor->short_video])->filter() 
            ->each(fn($file) => Storage::disk('public')->delete($file));

        $doctor->update(['deleted_by' => Auth::id()]);
        $doctor->delete();

        return back()->with('success', 'Doctor deleted!');
    }

    public function toggleFeatured(Doctor $doctor)
    {
        $doctor->update(['is_featured' => !$doctor->is_featured]);
        return back();
    }

    public function toggleActive(Doctor $doctor)
    {
        $doctor->update(['is_active' => !$doctor->is_active]);
        return back();
    }
}