@extends('cms.parent')

@section('title', 'Edit Issue')

@section('main-title', 'Edit Issue')

@section('sub-title', 'edit issue')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Edit Issue</div>
            </div>
            <form class="needs-validation ajax-form" action="{{ route('admin.issues.update', $issue->id) }}" method="POST"
                novalidate>
                @csrf
                @method('PUT')
                @include('cms.issues.form-content')

                <div class="card-footer">
                    @can('Edit Issue')
                        <button class="btn btn-info" type="submit">Update</button>
                    @endcan
                    @can('Index Issue')
                        <a href="{{ route('admin.issues.index') }}" class="btn btn-secondary">Cancel</a>
                    @endcan
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @stack('create_and_edit_ajax')
    @stack('dynamic-fields-script')

@endsection
