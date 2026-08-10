@extends('cms.parent')

@section('title', 'Edit Admin')

@section('main-title', 'Edit Admin')

@section('sub-title', 'edit Admin')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Edit Admin</div>
            </div>
            <form class="needs-validation ajax-form" action="{{ route('admin.admins.update', $admin->id) }}" method="POST"
                novalidate>
                @csrf
                @method('PUT')
                @include('cms.admins.form-content')

                <div class="card-footer">
                    @can('Edit Admin')
                        <button class="btn btn-info" type="submit">Update</button>
                    @endcan
                    @can('Index Admin')
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Cancel</a>
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
