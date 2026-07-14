@extends('layouts.app')

@section('title', 'Create Student')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-user-plus"></i> Register New Student</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.students.store') }}" enctype="multipart/form-data">
                    @csrf

                    <h5 class="mb-3">Personal Information</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="surname" class="form-label">Surname *</label>
                            <input type="text" class="form-control @error('surname') is-invalid @enderror" 
                                   id="surname" name="surname" value="{{ old('surname') }}" required>
                            @error('surname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="other_names" class="form-label">Other Names *</label>
                            <input type="text" class="form-control @error('other_names') is-invalid @enderror" 
                                   id="other_names" name="other_names" value="{{ old('other_names') }}" required>
                            @error('other_names')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender *</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth *</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="entry_year" class="form-label">Year of Entry *</label>
                            <input type="number" class="form-control @error('entry_year') is-invalid @enderror" 
                                   id="entry_year" name="entry_year" min="2020" max="2030" value="{{ old('entry_year', date('Y')) }}" required>
                            @error('entry_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="class_id" class="form-label">Class *</label>
                            <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->class_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">Student Photo</label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                               id="photo" name="photo" accept="image/*">
                        <small class="text-muted">Max 2MB. Accepted: JPEG, PNG, JPG, GIF</small>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5 class="mb-3">Guardian Information</h5>

                    <div class="mb-3">
                        <label for="guardian_id" class="form-label">Guardian</label>
                        <select class="form-select @error('guardian_id') is-invalid @enderror" id="guardian_id" name="guardian_id">
                            <option value="">Select or create guardian</option>
                            @foreach($guardians as $guardian)
                                <option value="{{ $guardian->id }}" {{ old('guardian_id') == $guardian->id ? 'selected' : '' }}>
                                    {{ $guardian->name }} ({{ $guardian->phone }})
                                </option>
                            @endforeach
                        </select>
                        @error('guardian_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5 class="mb-3">Community Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="zone" class="form-label">Zone</label>
                            <input type="text" class="form-control @error('zone') is-invalid @enderror" 
                                   id="zone" name="zone" value="{{ old('zone') }}" placeholder="e.g., Nansana East">
                            @error('zone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="community_worker_id" class="form-label">Community Worker</label>
                            <select class="form-select @error('community_worker_id') is-invalid @enderror" id="community_worker_id" name="community_worker_id">
                                <option value="">Select Community Worker</option>
                                @foreach($communityWorkers as $worker)
                                    <option value="{{ $worker->id }}" {{ old('community_worker_id') == $worker->id ? 'selected' : '' }}>
                                        {{ $worker->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('community_worker_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Student</button>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
