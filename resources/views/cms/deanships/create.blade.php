@extends('cms.parent')

@section('title', 'Create Deanship')

@section('main-title', 'Create Deanship')

@section('sub-title', 'create deanship')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Create Deanship</div>
            </div>
            <form class="ajax-form" action="{{ route('admin.deanships.store') }}" method="POST" novalidate>
                @include('cms.deanships.form-content')
                <div class="card-footer">
                    @can('Create Deanship')
                        <button class="btn btn-info" type="submit">Create</button>
                    @endcan
                    @can('Index Deanship')
                    @endcan
                    <a href="{{ route('admin.deanships.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @stack('edit_is_active')
    @stack('create_and_edit_ajax')
    {{-- <script>
        // Enable Bootstrap-style validation for forms marked with .needs-validation
        // or .needs-validation-tooltip. Prevents submission if any field is invalid.
        (() => {
            'use strict';
            const selector = '.needs-validation, .needs-validation-tooltip';
            for (const form of document.querySelectorAll(selector)) {
                form.addEventListener(
                    'submit',
                    (event) => {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    },
                    false,
                );
            }
        })();
    </script> --}}
@endsection
