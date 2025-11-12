<!doctype html>
<html lang="en">
<head>
    @include('components.backend.head')
</head>
<body>

    @include('components.backend.header')

    <!--start sidebar wrapper-->
    @include('components.backend.sidebar')
    <!--end sidebar wrapper-->

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h4>Add Discover KJSH Category</h4>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    <svg class="stroke-icon">
                                        <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>
                                    </svg>
                                    Home
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.category.index') }}">Categories</a>
                            </li>
                            <li class="breadcrumb-item active">Add New</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container-fluid starts -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Add Discover KJSH Category</h4>
                            <p class="f-m-light mt-1">Fill in the details below to create a new category.</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="category_name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                        <input type="text" name="category_name" id="category_name" 
                                               class="form-control @error('category_name') is-invalid @enderror" 
                                               value="{{ old('category_name') }}" 
                                               placeholder="Enter category name" required>
                                        @error('category_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Add more fields here later (image, status, etc.) -->

                                    <div class="col-12 text-end mt-4">
                                        <a href="{{ route('admin.category.index') }}" class="btn btn-secondary me-2">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary px-4">
                                            Save Category
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid ends -->
    </div>

    <!-- footer start -->
    @include('components.backend.footer')
    </div>
    </div>

    @include('components.backend.main-js')
</body>
</html>