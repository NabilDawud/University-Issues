@extends('cms.parent')

@section('title', 'Edit Category')

@section('main-title', 'Edit Category')

@section('sub-title', 'edit category')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Edit Category</div>
            </div>
            <form class="needs-validation ajax-form" action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                novalidate>
                @csrf
                @method('PUT')
                @include('cms.categories.form-content')

                <div class="card-footer">
                    @can('Edit Category')
                        <button class="btn btn-info" type="submit">Update</button>
                    @endcan
                    @can('Index Category')
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
                    @endcan
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @stack('create_and_edit_ajax')
@endsection
