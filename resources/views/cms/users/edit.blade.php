@extends('cms.parent')

@section('title', 'Edit User')

@section('main-title', 'Edit User')

@section('sub-title', 'edit user')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Edit User</div>
            </div>
            <form class="needs-validation ajax-form" action="{{ route('admin.users.update', $user->id) }}" method="POST"
                novalidate>
                @csrf
                @method('PUT')
                @include('cms.users.form-content')

                <div class="card-footer">
                    @can('Edit User')
                        <button class="btn btn-info" type="submit">Update</button>
                    @endcan
                    @can('Index User')
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                    @endcan
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @stack('type_user')
    @stack('edit_is_active')
    @stack('create_and_edit_ajax')
@endsection
