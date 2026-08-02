@extends('cms.parent')

@section('title', 'Edit Deanship')

@section('main-title', 'Edit Deanship')

@section('sub-title', 'edit deanship')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Edit Deanship</div>
            </div>
            <form class="needs-validation ajax-form" action="{{ route('admin.deanships.update', $deanship->id) }}"
                method="POST" novalidate>
                @csrf
                @method('PUT')
                @include('cms.deanships.form-content')

                <div class="card-footer">
                    <button class="btn btn-info" type="submit">Update</button>
                    <a href="{{ route('admin.deanships.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @stack('edit_is_active')
    @stack('create_and_edit_ajax')
@endsection
