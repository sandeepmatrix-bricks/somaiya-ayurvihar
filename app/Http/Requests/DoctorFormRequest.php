<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DoctorFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all authenticated admins
    }

    public function rules(): array
    {
        $doctorId = $this->route('doctor')?->id;

        return [
            // Personal Info
            'salutation' => 'required|in:Dr.,Mr.,Mrs.,Ms.,Prof.',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date|before:today',

            // Professional
            'medical_service_sub_category_id' => 'required|exists:medical_service_sub_categories,id',
            'registration_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('doctors', 'registration_number')->ignore($doctorId),
            ],
            'registration_council' => 'required|string|max:150',
            'registration_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'experience_years' => 'required|integer|min:0|max:60',
            'degrees' => 'required|string|max:255',
            'languages' => 'required|string|max:255',

            // Consultation
            'consultation_fee' => 'required|numeric|min:0|max:50000',
            'available_days' => 'required|array|min:1',
            'available_days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'morning_time' => 'nullable|required_with:available_days|regex:/^\d{2}:\d{2}-\d{2}:\d{2}$/',
            'evening_time' => 'nullable|regex:/^\d{2}:\d{2}-\d{2}:\d{2}$/',

            // Contact
            'phone' => [
                'required',
                'regex:/^[6-9]\d{9}$/',
                Rule::unique('doctors', 'phone')->ignore($doctorId),
            ],
            'whatsapp' => 'nullable|regex:/^[6-9]\d{9}$/',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('doctors', 'email')->ignore($doctorId),
            ],

            // Address
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'required|digits:6',

            // Files
            'profile_image' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
            'short_video' => 'nullable|mimes:mp4,mov,avi|max:30720', // 30MB

            // Status
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'salutation.required' => 'Please select a salutation.',
            'first_name.required' => 'First name is required.',
            'registration_number.unique' => 'This registration number is already taken.',
            'phone.regex' => 'Phone must be a valid 10-digit Indian number.',
            'email.unique' => 'This email is already registered.',
            'available_days.required' => 'Select at least one available day.',
            'morning_time.regex' => 'Morning time format: 09:00-13:00',
            'profile_image.required' => 'Profile photo is mandatory.',
            'profile_image.max' => 'Image must be less than 2MB.',
            'short_video.max' => 'Video must be less than 30MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'medical_service_sub_category_id' => 'Speciality',
            'consultation_fee' => 'Consultation Fee',
            'available_days' => 'Available Days',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_featured' => $this->has('is_featured') ? 1 : 0,
            'is_active' => $this->has('is_active') ? 1 : 1,
        ]);
    }
}