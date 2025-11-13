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
                    <div class="col-6"></div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    <svg class="stroke-icon">
                                        <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>
                                    </svg>
                                </a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                        <li class="breadcrumb-item active">Doctors ({{ $doctors->count() }})</li>
                                    </ol>
                                </nav>
                                <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary px-5 radius-30">
                                    + Add
                                </a>
                            </div>

                            <div class="table-responsive custom-scrollbar">
                                <table class="display" id="basic-1">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Photo</th>
                                            <th>Doctor Name</th>
                                            <th>Speciality</th>
                                            <th>Exp</th>
                                            <th>Fee</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($doctors as $key => $doctor)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <img src="{{ $doctor->profile_image_url }}" class="rounded-circle" width="50" height="50" alt="{{ $doctor->full_name }}">
                                            </td>
                                            <td>
                                                {{ $doctor->full_name }}
                                                @if($doctor->is_featured)
                                                    <span class="badge bg-warning text-dark ms-2">Featured</span>
                                                @endif
                                            </td>
                                            <td>{{ $doctor->subcategory->subcategory_name ?? '—' }}</td>
                                            <td>{{ $doctor->experience_years }}</td>
                                            <td>₹{{ number_format($doctor->consultation_fee) }}</td>
                                            <td>
                                                <form action="{{ route('admin.doctors.toggleActive', $doctor->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn {{ $doctor->is_active ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                <form action="{{ route('admin.doctors.toggleFeatured', $doctor->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-info btn-sm">
                                                        <i class="fa fa-star"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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