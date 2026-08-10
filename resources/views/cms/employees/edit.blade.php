@extends('cms.parent')

@section('title', 'Edit Employees')

@section('main-title', 'Edit Employees')

@section('sub-title', 'edit employees')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Edit Employees</div>
            </div>
            <form class="needs-validation ajax-form" action="{{ route('admin.employees.update', $employee->id) }}"
                method="POST" novalidate>
                @csrf
                @method('PUT')
                @include('cms.employees.form-content')

                <div class="card-footer">
                    <button class="btn btn-info" type="submit">Update</button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @stack('edit_is_active')
    @stack('create_and_edit_ajax')
@endsection
