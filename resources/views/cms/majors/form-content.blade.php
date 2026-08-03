 <div class="card-body">
     <div class="row g-3">
         <div class="col-md-6">
             <label for="name_en" class="form-label">English Name</label>
             <input type="text" class="form-control" id="name_en" name="name_en" value="{{ $major->name['en'] ?? '' }}"
                 required />
             <div class="invalid-feedback"></div>
         </div>
         <div class="col-md-6">
             <label for="name_ar" class="form-label">Arabic Name</label>
             <input type="text" class="form-control" id="name_ar" name="name_ar"
                 value="{{ $major->name['ar'] ?? '' }}" required />
             <div class="invalid-feedback"></div>
         </div>
         <div class="col-md-6">
             <label for="degree" class="form-label">Degree</label>
             <select class="form-select" id="degree" name="degree">
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
             <select class="form-select" id="department_id" name="department_id">
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
                 <input class="form-check-input me-2" style="width: 50px; height: 25px;" type="checkbox" role="switch"
                     id="is_active" name="is_active" {{ $major->is_active ?? true ? 'checked' : '' }} />
                 <label class="form-check-label fs-5" for="is_active">Active</label>
             </div>
         </div>
     </div>

 </div>

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
