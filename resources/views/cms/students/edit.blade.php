@extends('cms.parent')

@section('title', 'Edit Student')

@section('main-title', 'Edit Student')

@section('sub-title', 'edit student')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Edit Student</div>
            </div>
            <form class="needs-validation ajax-form" action="{{ route('admin.students.update', $student->id) }}"
                method="POST" novalidate>
                @csrf
                @method('PUT')
                @include('cms.students.form-content')

                <div class="card-footer">
                    <button class="btn btn-info" type="submit">Update</button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @stack('edit_is_active')
    @stack('create_and_edit_ajax')
@endsection
