<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif

    <div class="row">
        <!-- Row 1: Name -->
        <div class="col-md-3 mb-3">
            <label>Salutation <span class="text-danger">*</span></label>
            <select name="salutation" class="form-control @error('salutation') is-invalid @enderror" required>
                @foreach(['Dr.', 'Mr.', 'Mrs.', 'Ms.', 'Prof.'] as $sal)
                    <option value="{{ $sal }}" {{ old('salutation', $doctor?->salutation) == $sal ? 'selected' : '' }}>{{ $sal }}</option>
                @endforeach
            </select>
            @error('salutation') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3 mb-3">
            <label>First Name <span class="text-danger">*</span></label>
            <input name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $doctor?->first_name) }}" required>
            @error('first_name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3 mb-3">
            <label>Middle Name</label>
            <input name="middle_name" class="form-control" value="{{ old('middle_name', $doctor?->middle_name) }}">
        </div>

        <div class="col-md-3 mb-3">
            <label>Last Name <span class="text-danger">*</span></label>
            <input name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $doctor?->last_name) }}" required>
            @error('last_name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Row 2: Gender + Registration -->
        <div class="col-md-4 mb-3">
            <label>Gender <span class="text-danger">*</span></label>
            <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                <option value="">Select Gender</option>
                <option value="Male" {{ old('gender', $doctor?->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $doctor?->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ old('gender', $doctor?->gender) == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label>Registration Number <span class="text-danger">*</span></label>
            <input name="registration_number" class="form-control @error('registration_number') is-invalid @enderror" 
                   value="{{ old('registration_number', $doctor?->registration_number) }}" required>
            @error('registration_number') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label>Registration Council <span class="text-danger">*</span></label>
            <input name="registration_council" class="form-control @error('registration_council') is-invalid @enderror" 
                   value="{{ old('registration_council', $doctor?->registration_council) }}" required>
            @error('registration_council') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Row 3: Degrees + Languages -->
        <div class="col-md-6 mb-3">
            <label>Degrees <span class="text-danger">*</span></label>
            <select name="degrees[]" class="form-control select2 @error('degrees') is-invalid @enderror" multiple required>
                @foreach($degrees as $degree)
                    <option value="{{ $degree->id }}" 
                        @if(collect(old('degrees', json_decode($doctor?->degrees, true)))->contains($degree->id)) selected @endif>
                        {{ $degree->degree_name }}
                    </option>
                @endforeach
            </select>
            @error('degrees')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <div class="col-md-6 mb-3">
            <label>Languages Spoken (comma separated) <span class="text-danger">*</span></label>
            <input name="languages" class="form-control @error('languages') is-invalid @enderror" 
                   value="{{ old('languages', $doctor?->languages) }}" placeholder="English, Hindi, Tamil" required>
            @error('languages') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Row 4: Speciality + Experience + Fee -->
        <div class="col-md-6 mb-3">
            <label>Speciality <span class="text-danger">*</span></label>
            <select name="medical_service_sub_category_id" class="form-control @error('medical_service_sub_category_id') is-invalid @enderror" required>
                <option value="">Select Speciality</option>
                @foreach($subcategories as $id => $name)
                    <option value="{{ $id }}" {{ old('medical_service_sub_category_id', $doctor?->medical_service_sub_category_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            @error('medical_service_sub_category_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3 mb-3">
            <label>Experience (Years) <span class="text-danger">*</span></label>
            <input name="experience_years" type="number" class="form-control" value="{{ old('experience_years', $doctor?->experience_years) }}" required>
        </div>

        <div class="col-md-3 mb-3">
            <label>Consultation Fee <span class="text-danger">*</span></label>
            <input name="consultation_fee" type="number" class="form-control" value="{{ old('consultation_fee', $doctor?->consultation_fee) }}" required>
        </div>

        <!-- Row 5: Available Days -->
        <div class="col-md-12 mb-3">
            <label>Available Days <span class="text-danger">*</span></label><br>
            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="available_days[]" value="{{ $day }}"
                           {{ in_array($day, old('available_days', $doctor?->available_days ?? [])) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $day }}</label>
                </div>
            @endforeach
            @error('available_days') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Row 6: Timings -->
        <div class="col-md-6 mb-3">
            <label>Morning Time</label>
            <input name="morning_time" class="form-control" placeholder="09:00-13:00" value="{{ old('morning_time', $doctor?->morning_time) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label>Evening Time</label>
            <input name="evening_time" class="form-control" placeholder="17:00-21:00" value="{{ old('evening_time', $doctor?->evening_time) }}">
        </div>

        <!-- Row 7: Contact -->
        <div class="col-md-6 mb-3">
            <label>Phone <span class="text-danger">*</span></label>
            <input name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $doctor?->phone) }}" required>
            @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label>Email <span class="text-danger">*</span></label>
            <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $doctor?->email) }}" required>
            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Row 8: Address -->
        <div class="col-md-12 mb-3">
            <label>Address Line 1 <span class="text-danger">*</span></label>
            <input name="address_line_1" class="form-control @error('address_line_1') is-invalid @enderror" 
                   value="{{ old('address_line_1', $doctor?->address_line_1) }}" required>
            @error('address_line_1') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label>City <span class="text-danger">*</span></label>
            <input name="city" class="form-control @error('city') is-invalid @enderror" 
                   value="{{ old('city', $doctor?->city) }}" required>
            @error('city') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label>Pincode <span class="text-danger">*</span></label>
            <input name="pincode" class="form-control @error('pincode') is-invalid @enderror" 
                   value="{{ old('pincode', $doctor?->pincode) }}" required>
            @error('pincode') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Row 9: Media -->
        <div class="col-md-6 mb-3">
            <label>Profile Image @if(!$doctor) <span class="text-danger">*</span> @endif</label>
            <input name="profile_image" type="file" class="form-control" accept="image/*" {{ !$doctor ? 'required' : '' }}>
            @if($doctor?->profile_image)
                <img src="{{ $doctor->profile_image_url }}" class="mt-2 img-thumbnail" width="100">
            @endif
        </div>

        <div class="col-md-6 mb-3">
            <label>Short Video</label>
            <input name="short_video" type="file" class="form-control" accept="video/*">
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