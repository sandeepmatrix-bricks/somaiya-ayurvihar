<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DoctorFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $doctorId = $this->route('doctor')?->id;

        return [
            /* -------------------- PERSONAL INFO -------------------- */
            'salutation' => 'required|in:Dr.,Mr.,Mrs.,Ms.,Prof.',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date|before:today',

            /* -------------------- PROFESSIONAL -------------------- */
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

            /* -------------------- CONSULTATION -------------------- */
            'consultation_fee' => 'required|numeric|min:0|max:50000',

            'available_days' => 'required|array|min:1',
            'available_days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',

            'consultation_type' => 'required|in:split,full',

            /* Split Timings */
            'morning_start' => 'nullable|required_if:consultation_type,split|date_format:H:i',
            'morning_end'   => 'nullable|required_if:consultation_type,split|date_format:H:i|after:morning_start',

            'evening_start' => 'nullable|required_if:consultation_type,split|date_format:H:i',
            'evening_end'   => 'nullable|required_if:consultation_type,split|date_format:H:i|after:evening_start',

            /* Full-Day Timing */
            'full_start' => 'nullable|required_if:consultation_type,full|date_format:H:i',
            'full_end'   => 'nullable|required_if:consultation_type,full|date_format:H:i|after:full_start',

            /* -------------------- CONTACT -------------------- */
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

            /* -------------------- ADDRESS -------------------- */
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'required|digits:6',

            /* -------------------- FILES -------------------- */
            'profile_image' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            'short_video' => 'nullable|mimes:mp4,mov,avi|max:30720',

            /* -------------------- STATUS -------------------- */
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'morning_end.after' => 'Morning end time must be after morning start.',
            'evening_end.after' => 'Evening end time must be after evening start.',
            'full_end.after'    => 'Full day end time must be after start time.',
            'profile_image.required' => 'Profile photo is mandatory for new doctor.',
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
        $timings = null;

        /* -------------------- SPLIT TYPE -------------------- */
        if ($this->consultation_type === 'split') {
            $timings = [
                'type' => 'split',
                'morning' => [
                    'start' => $this->morning_start,
                    'end'   => $this->morning_end,
                ],
                'evening' => [
                    'start' => $this->evening_start,
                    'end'   => $this->evening_end,
                ],
            ];
        }

        /* -------------------- FULL DAY TYPE -------------------- */
        if ($this->consultation_type === 'full') {
            $timings = [
                'type' => 'full',
                'full_day' => [
                    'start' => $this->full_start,
                    'end'   => $this->full_end,
                ],
            ];
        }

        $this->merge([
            'consultation_timings' => $timings,
            'is_featured' => $this->has('is_featured') ? 1 : 0,
            'is_active'   => $this->has('is_active') ? 1 : 1,
        ]);
    }
}
