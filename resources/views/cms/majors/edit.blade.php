@extends('cms.parent')

@section('title', 'Edit Major')

@section('main-title', 'Edit Major')

@section('sub-title', 'edit major')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Edit Major</div>
            </div>
            <form class="needs-validation ajax-form" action="{{ route('admin.majors.update', $major->id) }}" method="POST"
                novalidate>
                @csrf
                @method('PUT')
                @include('cms.majors.form-content')

                <div class="card-footer">
                    @can('Edit Major')
                        <button class="btn btn-info" type="submit">Update</button>
                    @endcan
                    @can('Index Major')
                        <a href="{{ route('admin.majors.index') }}" class="btn btn-secondary">Cancel</a>
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
