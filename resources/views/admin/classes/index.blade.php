@extends('layouts.app')

@section('title', 'Classes')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-chalkboard"></i> Classes</h1>
    <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Promote Students
    </a>
</div>

<div class="row">
    @foreach($classes as $class)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">{{ $class->class_name }}</h5>
                </div>
                <div class="card-body">
                    <p><strong>Academic Year:</strong> {{ $class->academic_year }}</p>
                    <p><strong>Teacher:</strong> {{ $class->teacher->name ?? 'Not assigned' }}</p>
                    <p><strong>Students:</strong> <span class="badge bg-info">{{ $class->getActiveStudents()->count() }}</span></p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> View
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
