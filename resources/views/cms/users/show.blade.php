@extends('cms.parent')

@section('title', 'Show User')

@section('main-title', 'Show User')

@section('sub-title', 'show user')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class=" d-flex justify-between align-items-center">
                    <div class="card-title">Show User</div>
                    <div>
                        @can('Index User')
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to Users</a>
                        @endcan
                        @can('Edit User')
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-info">Edit
                                User</a>
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
                                value="{{ $user->name ?? '' }}" required />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" disabled
                                autocomplete="email" value="{{ $user->email ?? '' }}" required />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="user_name" class="form-label">User Name</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" disabled
                                value="{{ $user->user_name ?? '' }}" required />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="city" class="form-label">City</label>
                            <select class="form-select" id="city" name="city" disabled>
                                <option selected disabled value="">Choose&hellip;</option>
                                <option value="gaza" {{ ($user->city ?? '') === 'gaza' ? 'selected' : '' }}>
                                    Gaza (غزة)
                                </option>
                                <option value="khan_younis" {{ ($user->city ?? '') === 'khan_younis' ? 'selected' : '' }}>
                                    Khan Younis (خانيونس)
                                </option>
                                <option value="rafah" {{ ($user->city ?? '') === 'rafah' ? 'selected' : '' }}>
                                    Rafah (رفح)
                                </option>
                                <option value="jabalia" {{ ($user->city ?? '') === 'jabalia' ? 'selected' : '' }}>
                                    Jabalia (جباليا)
                                </option>
                                <option value="beit_hanun" {{ ($user->city ?? '') === 'beit_hanun' ? 'selected' : '' }}>
                                    Beit Hanun (بيت حانون)
                                </option>
                                <option value="beit_lahya" {{ ($user->city ?? '') === 'beit_lahya' ? 'selected' : '' }}>
                                    Beit Lahya (بيت لاهيا)
                                </option>
                                <option value="deir_al_balah"
                                    {{ ($user->city ?? '') === 'deir_al_balah' ? 'selected' : '' }}>
                                    Deir al-Balah (دير البلح)
                                </option>
                                <option value="al_zawaid" {{ ($user->city ?? '') === 'al_zawaid' ? 'selected' : '' }}>
                                    Al-Zawaid (الزوايدة)
                                </option>
                                <option value="al_nasirat" {{ ($user->city ?? '') === 'al_nasirat' ? 'selected' : '' }}>
                                    Al-Nasirat (النصيرات)
                                </option>
                                <option value="al_brij" {{ ($user->city ?? '') === 'al_brij' ? 'selected' : '' }}>
                                    Al-Brij (البريج)
                                </option>
                                <option value="al_mughazi" {{ ($user->city ?? '') === 'al_mughazi' ? 'selected' : '' }}>
                                    Al-Mughazi (المغازي)
                                </option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="phone_number" class="form-label">Phone number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" disabled
                                value="{{ $user->phone_number ?? '' }}" required />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender" disabled>
                                <option selected disabled value="">Choose&hellip;</option>
                                <option value="male" {{ ($user->gender ?? '') === 'male' ? 'selected' : '' }}>
                                    Male
                                </option>
                                <option value="female" {{ ($user->gender ?? '') === 'female' ? 'selected' : '' }}>
                                    Female
                                </option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="user_type_id" class="form-label">User Type</label>
                            <select class="form-select" id="user_type_id" name="user_type_id" disabled>
                                <option selected disabled value="">Choose&hellip;</option>
                                @foreach ($userTypes as $userType)
                                    <option value="{{ $userType->id }}" data-type="{{ strtolower($userType->name) }}"
                                        {{ ($user->user_type_id ?? '') === $userType->id ? 'selected' : '' }}>
                                        {{ $userType->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        @isset($user->profile_image)
                            <div class="col-md-4">
                                <label for="current_profile_image" class="form-label">Current Profile Image</label>
                                <div>
                                    <img src="{{ asset($user->profile_image) }}" alt="Current Profile Image"
                                        class="img-thumbnail" style="max-width: 150px;">
                                </div>
                            </div>
                        @endisset


                        <div class="col-md-4 align-self-center">
                            <div class="form-check form-switch d-flex justify-content-start align-items-center">
                                <input class="form-check-input me-2" style="width: 50px; height: 25px;" type="checkbox"
                                    role="switch" id="is_active" name="is_active" disabled
                                    {{ $user->is_active ?? true ? 'checked' : '' }} />
                                <label class="form-check-label fs-5" for="is_active">Active</label>
                            </div>
                        </div>

                        <div id="employee-fields" class="row g-3 d-none">
                            @include('cms.users.partials.employee-fields')
                        </div>
                        <div id="student-fields" class="row g-3 d-none">
                            @include('cms.users.partials.student-fields')
                        </div>
                        <hr>
                        <div class="col-md-12 d-flex align-items-center justify-between flex-wrap row-gap-2 column-gap-4">
                            <span class="font-bold text-body-secondary">Created at: <span
                                    class="font-medium">{{ $user->created_at->format('Y-m-d H:i:s A') }}</span></span>
                            <span class="font-bold text-body-secondary">Last Updated: <span
                                    class="font-medium">{{ $user->updated_at->format('Y-m-d H:i:s A') }}
                                    ({{ $user->updated_at->diffForHumans() }})</span></span>
                        </div>
                    </div>

                </div>



            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userTypeSelect = document.querySelector('#user_type_id');
            const employeeFields = document.querySelector('#employee-fields');
            const studentFields = document.querySelector('#student-fields');
            let inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.setAttribute('disabled', 'disabled');
            });

            function toggleUserFields() {
                const selectedOption = userTypeSelect.options[userTypeSelect.selectedIndex];
                const typeName = selectedOption?.dataset?.type || selectedOption?.text?.trim().toLowerCase();

                employeeFields.classList.add('d-none');
                studentFields.classList.add('d-none');

                if (typeName === 'student') {
                    studentFields.classList.remove('d-none');
                } else if (typeName === 'employee') {
                    employeeFields.classList.remove('d-none');
                }
            }

            userTypeSelect.addEventListener('change', toggleUserFields);

            // Execute on load for edit forms
            toggleUserFields();
        });
    </script>
@endsection
