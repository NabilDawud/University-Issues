@extends('cms.parent')

@section('title', 'Show Deanship')

@section('main-title', 'Show Deanship')

@section('sub-title', 'show deanship')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class=" d-flex justify-between align-items-center">
                    <div class="card-title">Show Deanship</div>
                    <div>
                        @can('Index Deanship')
                            <a href="{{ route('admin.deanships.index') }}" class="btn btn-secondary">Back to Deanships</a>
                        @endcan
                        @can('Edit Deanship')
                            <a href="{{ route('admin.deanships.edit', $deanship->id) }}" class="btn btn-info">Edit Deanship</a>
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
                                value="{{ old('name_en', $deanship->name['en'] ?? '') }}" disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="name_ar" class="form-label">Arabic Name</label>
                            <input type="text" class="form-control" id="name_ar" name="name_ar"
                                value="{{ old('name_ar', $deanship->name['ar'] ?? '') }}" disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="code" class="form-label">Code</label>
                            <div class="input-group has-validation">
                                <input type="text" class="form-control" id="code" name="code"
                                    value="{{ old('code', $deanship->code ?? '') }}" disabled />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $deanship->email ?? '') }}" disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="extension_number" class="form-label">Extension Number</label>
                            <input type="text" class="form-control" id="extension_number" name="extension_number"
                                value="{{ old('extension_number', $deanship->extension_number ?? '') }}" disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="office_number" class="form-label">Office Number</label>
                            <input type="text" class="form-control" id="office_number" name="office_number"
                                value="{{ old('office_number', $deanship->office_number ?? '') }}" disabled />
                        </div>
                        <div class="col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" disabled id="description" name="description" rows="4">{{ old('description', $deanship->description ?? '') }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input me-2" style="width: 50px; height: 25px;" type="checkbox"
                                    role="switch" id="is_active" name="is_active" disabled
                                    {{ old('is_active', $deanship->is_active ?? '') ? 'checked' : '' }} />
                                <label class="form-check-label fs-5"
                                    for="is_active">{{ $deanship->is_active ? 'Active' : 'Inactive' }}</label>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12 d-flex align-items-center justify-between flex-wrap row-gap-2 column-gap-4">
                            <span class="font-bold text-body-secondary">Created at: <span
                                    class="font-medium">{{ $deanship->created_at->format('Y-m-d H:i:s A') }}</span></span>
                            <span class="font-bold text-body-secondary">Last Updated: <span
                                    class="font-medium">{{ $deanship->updated_at->format('Y-m-d H:i:s A') }}
                                    ({{ $deanship->updated_at->diffForHumans() }})</span></span>
                        </div>
                    </div>

                </div>



            </form>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
