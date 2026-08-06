 <div class="card-body">
     <div class="row g-3">
         <div class="col-md-4">
             <label for="name" class="form-label">Name</label>
             <input type="text" class="form-control" id="name" name="name" value="{{ $user->name ?? '' }}"
                 required />
             <div class="invalid-feedback"></div>
         </div>
         <div class="col-md-4">
             <label for="email" class="form-label">Email</label>
             <input type="email" class="form-control" id="email" name="email" autocomplete="email"
                 value="{{ $user->email ?? '' }}" required />
             <div class="invalid-feedback"></div>
         </div>
         @if (request()->routeIs('admin.users.create'))
             <div class="col-md-4">
                 <label for="password" class="form-label">Password</label>
                 <input type="password" class="form-control" id="password" name="password" autocomplete="new-password"
                     required />
                 <div class="invalid-feedback"></div>
             </div>
             <div class="col-md-4">
                 <label for="email" class="form-label">Password Confirmation</label>
                 <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                     autocomplete="new-password" required />
                 <div class="invalid-feedback"></div>
             </div>
         @endif
         <div class="col-md-4">
             <label for="user_name" class="form-label">User Name</label>
             <input type="text" class="form-control" id="user_name" name="user_name"
                 value="{{ $user->user_name ?? '' }}" required />
             <div class="invalid-feedback"></div>
         </div>
         <div class="col-md-4">
             <label for="city" class="form-label">City</label>
             <select class="form-select" id="city" name="city">
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
                 <option value="deir_al_balah" {{ ($user->city ?? '') === 'deir_al_balah' ? 'selected' : '' }}>
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
             <input type="text" class="form-control" id="phone_number" name="phone_number"
                 value="{{ $user->phone_number ?? '' }}" required />
             <div class="invalid-feedback"></div>
         </div>
         <div class="col-md-4">
             <label for="gender" class="form-label">Gender</label>
             <select class="form-select" id="gender" name="gender">
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
             <select class="form-select" id="user_type_id" name="user_type_id">
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
         <div class="col-md-4">
             <label for="profile_image" class="form-label">Profile Image</label>
             <div class="input-group">
                 <input type="file" class="form-control" id="profile_image" name="profile_image" />
                 <label class="input-group-text" for="profile_image">Upload</label>
             </div>
             <div class="invalid-feedback"></div>
         </div>
         @isset($user->profile_image)
             <div class="col-md-4">
                 <label class="form-label">Current Profile Image</label>
                 <div>
                     <img src="{{ asset($user->profile_image) }}" alt="Current Profile Image" class="img-thumbnail"
                         style="max-width: 150px;">
                 </div>
             </div>
         @endisset


         <div class="col-md-4 align-self-end">
             <div class="form-check form-switch d-flex justify-content-center align-items-center">
                 <input class="form-check-input me-2" style="width: 50px; height: 25px;" type="checkbox"
                     role="switch" id="is_active" name="is_active"
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
     </div>

 </div>

 @push('type_user')
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             const userTypeSelect = document.querySelector('#user_type_id');
             const employeeFields = document.querySelector('#employee-fields');
             const studentFields = document.querySelector('#student-fields');

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
 @endpush


 @push('edit_is_active')
     <script>
         const is_active = document.getElementById('is_active');
         const is_active_label = document.querySelector('label[for="is_active"]');

         function updateLabel() {
             is_active_label.textContent = is_active.checked ? 'Active' : 'Inactive';
         }

         updateLabel(); // لتعيين النص عند تحميل الصفحة
         is_active.addEventListener('change', updateLabel);
     </script>
 @endpush

 @push('create_and_edit_ajax')
     <script>
         document.querySelectorAll('.ajax-form')
             .forEach(form => {

                 form.addEventListener('submit', async function(e) {

                     e.preventDefault();

                     let method = form.querySelector('[name="_method"]')?.value ??
                         form.method;

                     let formData = new FormData(form);


                     try {

                         let response = await ajaxRequest(
                             form.action,
                             method,
                             formData
                         );


                         Swal.fire({
                             icon: response.icon,
                             title: response.message,
                             showConfirmButton: false,
                             timer: 1200
                         });


                         if (response.redirect) {

                             setTimeout(() => {

                                 window.location.href = response.redirect;

                             }, 1200);

                         } else {

                             form.reset();

                             document
                                 .querySelectorAll('.is-invalid')
                                 .forEach(el => {
                                     el.classList.remove('is-invalid');
                                 });
                             document.querySelectorAll('.invalid-feedback')
                                 .forEach(el => {
                                     el.innerHTML = '';
                                 });
                         }


                     } catch (error) {


                         if (error.errors) {

                             showErrors(error.errors);

                         } else {

                             Swal.fire({
                                 icon: error.icon ?? 'error',
                                 title: error.message ?? 'Something went wrong'
                             });

                         }

                     }

                 });

             });
     </script>
 @endpush
