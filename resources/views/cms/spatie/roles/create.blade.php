@extends('cms.parent')

@section('title', 'Create Role')

@section('main-title', 'Create Role')

@section('sub-title', 'create role')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Create Role</div>
            </div>
            <form class="ajax-form" action="{{ route('admin.roles.store') }}" method="POST" novalidate>
                @include('cms.spatie.roles.form-content')
                <div class="card-footer">
                    @can('Create Role')
                        <button class="btn btn-info" type="submit">Create</button>
                    @endcan
                    @can('Index Role')
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
                    @endcan
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @stack('create_and_edit_ajax')
@endsection
