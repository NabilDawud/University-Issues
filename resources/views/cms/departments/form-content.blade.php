 <div class="card-body">
     <div class="row g-3">
         <div class="col-md-6">
             <label for="name_en" class="form-label">English Name</label>
             <input type="text" class="form-control" id="name_en" name="name_en"
                 value="{{ $department->name['en'] ?? '' }}" required />
             <div class="invalid-feedback"></div>
         </div>
         <div class="col-md-6">
             <label for="name_ar" class="form-label">Arabic Name</label>
             <input type="text" class="form-control" id="name_ar" name="name_ar"
                 value="{{ $department->name['ar'] ?? '' }}" required />
             <div class="invalid-feedback"></div>
         </div>
         <div class="col-md-6">
             <label for="code" class="form-label">Code</label>
             <div class="input-group has-validation">
                 <input type="text" class="form-control" id="code" name="code"
                     value="{{ $department->code ?? '' }}" required />
                 <div class="invalid-feedback"></div>
             </div>
         </div>
         <div class="col-md-6">
             <label for="email" class="form-label">Email</label>
             <input type="email" class="form-control " id="email" name="email"
                 value="{{ $department->email ?? '' }}" required />
             <div class="invalid-feedback"></div>
         </div>
         <div class="col-md-6">
             <label for="extension_number" class="form-label">Extension Number</label>
             <input type="text" class="form-control" id="extension_number" name="extension_number"
                 value="{{ $department->extension_number ?? '' }}" />
             <div class="invalid-feedback"></div>
         </div>
         <div class="col-md-6">
             <label for="office_number" class="form-label">Office Number</label>
             <input type="text" class="form-control" id="office_number" name="office_number"
                 value="{{ $department->office_number ?? '' }}" required />
             <div class="invalid-feedback"></div>
         </div>

         <div class="col-md-6">
             <div class="col-md-12">
                 <label for="deanship_id" class="form-label">Deanship</label>
                 <select class="form-select" id="deanship_id" name="deanship_id">
                     <option selected disabled value="">Choose&hellip;</option>
                     @foreach ($deanships as $deanship)
                         <option value="{{ $deanship->id }}"
                             {{ ($department->deanship_id ?? '') === $deanship->id ? 'selected' : '' }}>
                             {{ $deanship->trans_name }}
                         </option>
                     @endforeach
                 </select>
                 <div class="invalid-feedback"></div>
             </div>
             <div class="col-md-12 mt-3 mt-md-4 ">
                 <div class="form-check form-switch d-flex align-items-center ps-0">
                     <label class="form-check-label fs-5 pe-5" for="is_active">Active</label>
                     <input class="form-check-input" style="width: 50px; height: 25px;" type="checkbox" role="switch"
                         id="is_active" name="is_active" {{ ($department->is_active ?? true) ? 'checked' : '' }} />
                 </div>
             </div>
         </div>
         <div class="col-md-6">
             <label for="description" class="form-label">Description</label>
             <textarea class="form-control" id="description" name="description" rows="4">{{ $department->description ?? '' }}</textarea>
             <div class="invalid-feedback"></div>
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
