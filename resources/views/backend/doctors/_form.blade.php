<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif

    <div class="row">

        <!-- Row 1: Name -->
        <div class="col-md-3 mb-3">
            <label>Salutation <span class="text-danger">*</span></label>
            <select name="salutation" class="form-control" required>
                @foreach(['Dr.', 'Mr.', 'Mrs.', 'Ms.', 'Prof.'] as $sal)
                    <option value="{{ $sal }}" {{ old('salutation', $doctor?->salutation) == $sal ? 'selected' : '' }}>{{ $sal }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 mb-3">
            <label>First Name <span class="text-danger">*</span></label>
            <input name="first_name" class="form-control"
                   value="{{ old('first_name', $doctor?->first_name) }}" required>
        </div>

        <div class="col-md-3 mb-3">
            <label>Middle Name</label>
            <input name="middle_name" class="form-control"
                   value="{{ old('middle_name', $doctor?->middle_name) }}">
        </div>

        <div class="col-md-3 mb-3">
            <label>Last Name <span class="text-danger">*</span></label>
            <input name="last_name" class="form-control"
                   value="{{ old('last_name', $doctor?->last_name) }}" required>
        </div>

        <!-- Row 2 -->
        <div class="col-md-4 mb-3">
            <label>Gender <span class="text-danger">*</span></label>
            <select name="gender" class="form-control" required>
                <option value="">Select</option>
                @foreach(['Male','Female','Other'] as $g)
                    <option value="{{ $g }}" {{ old('gender', $doctor?->gender) == $g ? 'selected' : '' }}>{{ $g }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label>Registration Number <span class="text-danger">*</span></label>
            <input name="registration_number" class="form-control"
                   value="{{ old('registration_number', $doctor?->registration_number) }}" required>
        </div>

        <div class="col-md-4 mb-3">
            <label>Registration Council <span class="text-danger">*</span></label>
            <input name="registration_council" class="form-control"
                   value="{{ old('registration_council', $doctor?->registration_council) }}" required>
        </div>


        <!-- DEGREE MULTI SELECT (LIKE WAREHOUSE UI) -->
        <div class="col-md-6 mt-3">
            <label for="degreeDropdown">Select Degrees <span class="text-danger">*</span></label>

            <div class="dropdown w-100" data-bs-auto-close="outside">
                <button class="btn btn-outline-secondary dropdown-toggle w-100"
                        type="button" id="degreeDropdown"
                        data-bs-toggle="dropdown">
                    <span id="degreeDropdownLabel">
                        {{ count(old('degree_id', $doctor?->degrees ?? [])) > 0 ? count(old('degree_id', $doctor?->degrees ?? [])) . ' selected' : 'Choose Degrees' }}
                    </span>
                </button>

                <div class="dropdown-menu p-3 w-100" style="max-height:300px;overflow-y:auto;">
                    
                    <div class="mb-2">
                        <input type="text" id="degreeSearch" class="form-control" placeholder="Search degree...">
                    </div>

                    <ul id="degreeList" class="list-unstyled mb-0">
                        @foreach($degrees as $degree)
                            <li class="form-check">
                                <input class="form-check-input degree-checkbox"
                                       type="checkbox"
                                       name="degree_id[]"
                                       value="{{ $degree->id }}"
                                       id="degree_{{ $degree->id }}"
                                       {{ in_array($degree->id, old('degree_id', $doctor?->degrees ?? [])) ? 'checked' : '' }}>

                                <label class="form-check-label" for="degree_{{ $degree->id }}">
                                    {{ $degree->degree_name }}
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>


        <div class="col-md-6 mb-3">
            <label>Languages (comma separated) <span class="text-danger">*</span></label>
            <input name="languages" class="form-control"
                   value="{{ old('languages', $doctor?->languages) }}" required>
        </div>

        <!-- Speciality + Experience + Fee -->
        <div class="col-md-6 mb-3">
            <label>Speciality <span class="text-danger">*</span></label>
            <select name="medical_service_sub_category_id" class="form-control" required>
                <option value="">Select Speciality</option>
                @foreach($subcategories as $id => $name)
                    <option value="{{ $id }}" {{ old('medical_service_sub_category_id', $doctor?->medical_service_sub_category_id) == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 mb-3">
            <label>Experience (Years) <span class="text-danger">*</span></label>
            <input name="experience_years" type="number" class="form-control"
                   value="{{ old('experience_years', $doctor?->experience_years) }}" required>
        </div>

        <div class="col-md-3 mb-3">
            <label>Consultation Fee <span class="text-danger">*</span></label>
            <input name="consultation_fee" type="number" class="form-control"
                   value="{{ old('consultation_fee', $doctor?->consultation_fee) }}" required>
        </div>


        <!-- Available Days -->
        <div class="col-md-12 mb-3">
            <label>Available Days <span class="text-danger">*</span></label><br>

            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input"
                           name="available_days[]"
                           value="{{ $day }}"
                           {{ in_array($day, old('available_days', $doctor?->available_days ?? [])) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $day }}</label>
                </div>
            @endforeach
        </div>


        <!-- NEW: MULTIPLE TIME SLOTS -->
        <div class="col-md-12 mb-3">
            <label>Consultation Timings</label>

            @php
                $slots = old('consultation_timings', $doctor?->consultation_timings ?? []);
            @endphp

            <div id="timeSlotsContainer">

                @foreach($slots as $s)
                <div class="row g-2 time-slot mb-2">
                    <div class="col-md-4">
                        <input type="time" name="consultation_timings[][start]" class="form-control"
                               value="{{ $s['start'] ?? '' }}" required>
                    </div>

                    <div class="col-md-4">
                        <input type="time" name="consultation_timings[][end]" class="form-control"
                               value="{{ $s['end'] ?? '' }}" required>
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger removeSlot">X</button>
                    </div>
                </div>
                @endforeach

            </div>

            <button type="button" class="btn btn-secondary mt-2" id="addSlotBtn">+ Add Time Slot</button>
        </div>


        <!-- Contact -->
        <div class="col-md-6 mb-3">
            <label>Phone <span class="text-danger">*</span></label>
            <input name="phone" class="form-control"
                   value="{{ old('phone', $doctor?->phone) }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>Email <span class="text-danger">*</span></label>
            <input name="email" type="email" class="form-control"
                   value="{{ old('email', $doctor?->email) }}" required>
        </div>


        <!-- Address -->
        <div class="col-md-12 mb-3">
            <label>Address Line 1 <span class="text-danger">*</span></label>
            <input name="address_line_1" class="form-control"
                   value="{{ old('address_line_1', $doctor?->address_line_1) }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>City <span class="text-danger">*</span></label>
            <input name="city" class="form-control"
                   value="{{ old('city', $doctor?->city) }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>Pincode <span class="text-danger">*</span></label>
            <input name="pincode" class="form-control"
                   value="{{ old('pincode', $doctor?->pincode) }}" required>
        </div>


        <!-- Media -->
        <div class="col-md-6 mb-3">
            <label>Profile Image @if(!$doctor) <span class="text-danger">*</span> @endif</label>
            <input type="file" name="profile_image" class="form-control" accept="image/*"
                   {{ !$doctor ? 'required' : '' }}>
            @if($doctor?->profile_image)
                <img src="{{ $doctor->profile_image_url }}" class="img-thumbnail mt-2" width="100">
            @endif
        </div>

        <div class="col-md-6 mb-3">
            <label>Short Video</label>
            <input type="file" name="short_video" class="form-control" accept="video/*">
            @if($doctor?->short_video)
                <video width="200" controls class="mt-2">
                    <source src="{{ $doctor->short_video_url }}" type="video/mp4">
                </video>
            @endif
        </div>

        <!-- Submit -->
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary px-4">{{ $buttonText }}</button>
        </div>

    </div>
</form>

<script>
document.getElementById('addSlotBtn').addEventListener('click', function() {
    const container = document.getElementById('timeSlotsContainer');

    const html = `
        <div class="row g-2 time-slot mb-2">
            <div class="col-md-4">
                <input type="time" name="consultation_timings[][start]" class="form-control" required>
            </div>
            <div class="col-md-4">
                <input type="time" name="consultation_timings[][end]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger removeSlot">X</button>
            </div>
        </div>`;

    container.insertAdjacentHTML('beforeend', html);
});

document.addEventListener('click', function(e){
    if(e.target.classList.contains('removeSlot')){
        e.target.closest('.time-slot').remove();
    }
});
</script>
