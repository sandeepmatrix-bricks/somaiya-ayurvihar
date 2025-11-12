<!doctype html>
<html lang="en">
<head>
    @include('components.backend.head')
</head>
<body>
    @include('components.backend.header')
    @include('components.backend.sidebar')

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h4>Add Medical Service Subcategory</h4>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.medicalservicesubcategory.index') }}">Subcategories</a></li>
                            <li class="breadcrumb-item active">Add New</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Subcategory Form</h4>
                            <p class="f-m-light mt-1">Select parent category and enter name.</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.medicalservicesubcategory.store') }}" method="POST">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="category_id" class="form-label">Parent Category <span class="text-danger">*</span></label>
                                        <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                            <option value="">Select Parent Category</option>
                                            @foreach($categories as $id => $name)
                                                <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="subcategory_name" class="form-label">Subcategory Name <span class="text-danger">*</span></label>
                                        <input type="text" name="subcategory_name" id="subcategory_name" 
                                               class="form-control @error('subcategory_name') is-invalid @enderror" 
                                               value="{{ old('subcategory_name') }}" 
                                               placeholder="Enter subcategory name" required>
                                        @error('subcategory_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 text-end mt-4">
                                        <a href="{{ route('admin.medicalservicesubcategory.index') }}" class="btn btn-secondary me-2">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary px-4">
                                            Save Subcategory
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.backend.footer')
    @include('components.backend.main-js')
</body>
</html>