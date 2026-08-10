@extends('cms.parent')

@section('title', 'Edit Department')

@section('main-title', 'Edit Department')

@section('sub-title', 'edit department')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Edit Department</div>
            </div>
            <form class="needs-validation ajax-form" action="{{ route('admin.departments.update', $department->id) }}"
                method="POST" novalidate>
                @csrf
                @method('PUT')
                @include('cms.departments.form-content')

                <div class="card-footer">
                    @can('Edit Department')
                        <button class="btn btn-info" type="submit">Update</button>
                    @endcan
                    @can('Index Department')
                        <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">Cancel</a>
                    @endcan
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @stack('edit_is_active')
    @stack('create_and_edit_ajax')
@endsection
