@extends('cms.parent')

@section('title', 'Create Permission')

@section('main-title', 'Create Permission')

@section('sub-title', 'create permission')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Create Permission</div>
            </div>
            <form class="ajax-form" action="{{ route('admin.permissions.store') }}" method="POST" novalidate>
                @include('cms.spatie.permissions.form-content')
                <div class="card-footer">
                    @can('Create Permission')
                        <button class="btn btn-info" type="submit">Create</button>
                    @endcan
                    @can('Index Permission')
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Cancel</a>
                    @endcan
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @stack('create_and_edit_ajax')
@endsection
