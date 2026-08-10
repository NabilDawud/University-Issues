@extends('cms.parent')

@section('title', 'Show Student')

@section('main-title', 'Show Student')

@section('sub-title', 'show student')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class=" d-flex justify-between align-items-center">
                    <div class="card-title">Show Student</div>
                    <div>
                        @can('Index Student')
                            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Back to Students</a>
                        @endcan
                        @can('Edit Student')
                            <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-info">Edit
                                Student</a>
                        @endcan
                    </div>
                </div>
            </div>
            <form class="needs-validation" novalidate>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="name_en" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" disabled
                                value="{{ $student->name ?? '' }}" required />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" disabled
                                autocomplete="email" value="{{ $student->email ?? '' }}" required />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="user_name" class="form-label">User Name</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" disabled
                                value="{{ $student->user_name ?? '' }}" required />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="city" class="form-label">City</label>
                            <select class="form-select" id="city" name="city" disabled>
                                <option selected disabled value="">Choose&hellip;</option>
                                <option value="gaza" {{ ($student->city ?? '') === 'gaza' ? 'selected' : '' }}>
                                    Gaza (غزة)
                                </option>
                                <option value="khan_younis"
                                    {{ ($student->city ?? '') === 'khan_younis' ? 'selected' : '' }}>
                                    Khan Younis (خانيونس)
                                </option>
                                <option value="rafah" {{ ($student->city ?? '') === 'rafah' ? 'selected' : '' }}>
                                    Rafah (رفح)
                                </option>
                                <option value="jabalia" {{ ($student->city ?? '') === 'jabalia' ? 'selected' : '' }}>
                                    Jabalia (جباليا)
                                </option>
                                <option value="beit_hanun" {{ ($student->city ?? '') === 'beit_hanun' ? 'selected' : '' }}>
                                    Beit Hanun (بيت حانون)
                                </option>
                                <option value="beit_lahya" {{ ($student->city ?? '') === 'beit_lahya' ? 'selected' : '' }}>
                                    Beit Lahya (بيت لاهيا)
                                </option>
                                <option value="deir_al_balah"
                                    {{ ($student->city ?? '') === 'deir_al_balah' ? 'selected' : '' }}>
                                    Deir al-Balah (دير البلح)
                                </option>
                                <option value="al_zawaid" {{ ($student->city ?? '') === 'al_zawaid' ? 'selected' : '' }}>
                                    Al-Zawaid (الزوايدة)
                                </option>
                                <option value="al_nasirat" {{ ($student->city ?? '') === 'al_nasirat' ? 'selected' : '' }}>
                                    Al-Nasirat (النصيرات)
                                </option>
                                <option value="al_brij" {{ ($student->city ?? '') === 'al_brij' ? 'selected' : '' }}>
                                    Al-Brij (البريج)
                                </option>
                                <option value="al_mughazi" {{ ($student->city ?? '') === 'al_mughazi' ? 'selected' : '' }}>
                                    Al-Mughazi (المغازي)
                                </option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="phone_number" class="form-label">Phone number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" disabled
                                value="{{ $student->phone_number ?? '' }}" required />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender" disabled>
                                <option selected disabled value="">Choose&hellip;</option>
                                <option value="male" {{ ($student->gender ?? '') === 'male' ? 'selected' : '' }}>
                                    Male
                                </option>
                                <option value="female" {{ ($student->gender ?? '') === 'female' ? 'selected' : '' }}>
                                    Female
                                </option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        @isset($student->profile_image)
                            <div class="col-md-4">
                                <label for="current_profile_image" class="form-label">Current Profile Image</label>
                                <div>
                                    <img src="{{ asset($student->profile_image) }}" alt="Current Profile Image"
                                        class="img-thumbnail" style="max-width: 150px;">
                                </div>
                            </div>
                        @endisset
                        <div class="col-md-4">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" disabled>
                                <option selected disabled value="">Choose user type first&hellip;</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ isset($student) && ($student->roles->first()->id ?? '') === $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-4 align-self-center">
                            <div class="form-check form-switch d-flex justify-content-start align-items-center">
                                <input class="form-check-input me-2" style="width: 50px; height: 25px;" type="checkbox"
                                    role="switch" id="is_active" name="is_active" disabled
                                    {{ $student->is_active ?? true ? 'checked' : '' }} />
                                <label class="form-check-label fs-5" for="is_active">Active</label>
                            </div>
                        </div>

                        <div id="student-fields" class="row g-3">
                            @include('cms.students.partials.student-fields')
                        </div>
                        <hr>
                        <div class="col-md-12 d-flex align-items-center justify-between flex-wrap row-gap-2 column-gap-4">
                            <span class="font-bold text-body-secondary">Created at: <span
                                    class="font-medium">{{ $student->created_at->format('Y-m-d H:i:s A') }}</span></span>
                            <span class="font-bold text-body-secondary">Last Updated: <span
                                    class="font-medium">{{ $student->updated_at->format('Y-m-d H:i:s A') }}
                                    ({{ $student->updated_at->diffForHumans() }})</span></span>
                        </div>
                    </div>

                </div>



            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.setAttribute('disabled', 'disabled');
        });
    </script>
@endsection
