@extends('cms.parent')

@section('title', 'Show Department')

@section('main-title', 'Show Department')

@section('sub-title', 'show department')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class=" d-flex justify-between align-items-center">
                    <div class="card-title">Show Department</div>
                    <div>
                        @can('Index Department')
                            <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">Back to Departments</a>
                        @endcan
                        @can('Edit Department')
                            <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-info">Edit
                                Department</a>
                        @endcan
                    </div>
                </div>
            </div>
            <form class="needs-validation" novalidate>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name_en" class="form-label">English Name</label>
                            <input type="text" class="form-control" id="name_en" name="name_en"
                                value="{{ old('name_en', $department->name['en'] ?? '') }}" disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="name_ar" class="form-label">Arabic Name</label>
                            <input type="text" class="form-control" id="name_ar" name="name_ar"
                                value="{{ old('name_ar', $department->name['ar'] ?? '') }}" disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="code" class="form-label">Code</label>
                            <div class="input-group has-validation">
                                <input type="text" class="form-control" id="code" name="code"
                                    value="{{ old('code', $department->code ?? '') }}" disabled />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $department->email ?? '') }}" disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="extension_number" class="form-label">Extension Number</label>
                            <input type="text" class="form-control" id="extension_number" name="extension_number"
                                value="{{ old('extension_number', $department->extension_number ?? '') }}" disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="office_number" class="form-label">Office Number</label>
                            <input type="text" class="form-control" id="office_number" name="office_number"
                                value="{{ old('office_number', $department->office_number ?? '') }}" disabled />
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                <label for="deanship_id" class="form-label">Deanship</label>
                                <select class="form-select" id="deanship_id" name="deanship_id" disabled>
                                    <option selected disabled value="">Choose&hellip;</option>
                                    @foreach ($deanships as $deanship)
                                        <option value="{{ $deanship->id }}"
                                            {{ $department->deanship_id ?? '' == $deanship->id ? 'selected' : '' }}>
                                            {{ $deanship->trans_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-12 mt-3 mt-md-4 ">
                                <div class="form-check form-switch d-flex align-items-center ps-0">
                                    <label class="form-check-label fs-5 pe-5" for="is_active">Active</label>
                                    <input class="form-check-input" style="width: 50px; height: 25px;" type="checkbox"
                                        role="switch" id="is_active" name="is_active" disabled
                                        {{ $department->is_active ?? true ? 'checked' : '' }} />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" disabled id="description" name="description" rows="4">{{ old('description', $department->description ?? '') }}</textarea>
                        </div>
                        <hr>
                        <div class="col-md-12 d-flex align-items-center justify-between flex-wrap row-gap-2 column-gap-4">
                            <span class="font-bold text-body-secondary">Created at: <span
                                    class="font-medium">{{ $department->created_at->format('Y-m-d H:i:s A') }}</span></span>
                            <span class="font-bold text-body-secondary">Last Updated: <span
                                    class="font-medium">{{ $department->updated_at->format('Y-m-d H:i:s A') }}
                                    ({{ $department->updated_at->diffForHumans() }})</span></span>
                        </div>
                    </div>

                </div>



            </form>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
