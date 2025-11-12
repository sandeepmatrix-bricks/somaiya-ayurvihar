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
                        <h4>Edit KJSH Subcategory</h4>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.kjshsubcategory.index') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Edit KJSH Subcategory</li>
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
                            <h4>KJSH Subcategory Form</h4>
                            <p class="f-m-light mt-1">Fill up your true details and submit the form.</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.kjshsubcategory.update', $kjshsubcategory->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="category_id">Parent Category</label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="">Select Parent Category</option>
                                        @foreach($categories as $id => $name)
                                            <option value="{{ $id }}" {{ $kjshsubcategory->category_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="subcategory_name">Subcategory Name</label>
                                    <input type="text" name="subcategory_name" id="subcategory_name" class="form-control" value="{{ $kjshsubcategory->subcategory_name }}" placeholder="Enter subcategory name" required>
                                </div>
                                <br>
                                <div class="col-12 text-end">
                                    <a href="{{ route('admin.kjshsubcategory.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update</button>
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