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
                        <h4>Edit Medical Service Category</h4>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.medicalservicecategory.index') }}">Medical Service Categories</a></li>
                            <li class="breadcrumb-item active">Edit</li>
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
                            <h4>Update Medical Service Category</h4>
                            <p class="f-m-light mt-1">Update the details below.</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.medicalservicecategory.update', $medicalservicecategory->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="category_name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                        <input type="text" name="category_name" id="category_name" 
                                               class="form-control @error('category_name') is-invalid @enderror" 
                                               value="{{ old('category_name', $medicalservicecategory->category_name) }}" 
                                               placeholder="Enter category name" required>
                                        @error('category_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control bg-light" value="{{ $medicalservicecategory->slug }}" readonly>
                                        <small class="text-muted">Auto-generated</small>
                                    </div>

                                    <div class="col-12 text-end mt-4">
                                        <a href="{{ route('admin.medicalservicecategory.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary px-4">Update Category</button>
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