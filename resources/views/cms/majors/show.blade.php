@extends('cms.parent')

@section('title', 'Show Major')

@section('main-title', 'Show Major')

@section('sub-title', 'show major')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class=" d-flex justify-between align-items-center">
                    <div class="card-title">Show Major</div>
                    <div>
                        <a href="{{ route('admin.majors.index') }}" class="btn btn-secondary">Back to Majors</a>
                        <a href="{{ route('admin.majors.edit', $major->id) }}" class="btn btn-info">Edit
                            Major</a>
                    </div>
                </div>
            </div>
            <form class="needs-validation" novalidate>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name_en" class="form-label">English Name</label>
                            <input type="text" class="form-control" id="name_en" name="name_en"
                                value="{{ old('name_en', $major->name['en'] ?? '') }}" disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="name_ar" class="form-label">Arabic Name</label>
                            <input type="text" class="form-control" id="name_ar" name="name_ar"
                                value="{{ old('name_ar', $major->name['ar'] ?? '') }}" disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="degree" class="form-label">Degree</label>
                            <select class="form-select" id="degree" name="degree" disabled>
                                <option selected disabled value="">Choose&hellip;</option>
                                <option value="diploma" {{ ($major->degree ?? '') === 'diploma' ? 'selected' : '' }}>
                                    Diploma
                                </option>
                                <option value="bachelor" {{ ($major->degree ?? '') === 'bachelor' ? 'selected' : '' }}>
                                    Bachelor
                                </option>
                                <option value="master" {{ ($major->degree ?? '') === 'master' ? 'selected' : '' }}>
                                    Master
                                </option>
                                <option value="phd" {{ ($major->degree ?? '') === 'phd' ? 'selected' : '' }}>
                                    PhD
                                </option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="department_id" class="form-label">Departments</label>
                            <select class="form-select" id="department_id" name="department_id" disabled>
                                <option selected disabled value="">Choose&hellip;</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ ($major->department_id ?? '') === $department->id ? 'selected' : '' }}>
                                        {{ $department->trans_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input me-2" style="width: 50px; height: 25px;" type="checkbox"
                                    role="switch" id="is_active" name="is_active" disabled
                                    {{ $major->is_active ?? true ? 'checked' : '' }} />
                                <label class="form-check-label fs-5" for="is_active">Active</label>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12 d-flex align-items-center justify-between flex-wrap row-gap-2 column-gap-4">
                            <span class="font-bold text-body-secondary">Created at: <span
                                    class="font-medium">{{ $major->created_at->format('Y-m-d H:i:s A') }}</span></span>
                            <span class="font-bold text-body-secondary">Last Updated: <span
                                    class="font-medium">{{ $major->updated_at->format('Y-m-d H:i:s A') }}
                                    ({{ $major->updated_at->diffForHumans() }})</span></span>
                        </div>
                    </div>

                </div>



            </form>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
