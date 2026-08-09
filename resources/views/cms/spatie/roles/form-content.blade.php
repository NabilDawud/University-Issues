 <div class="card-body">
     <div class="row g-3">
         <div class="col-md-6">
             <label for="name" class="form-label">Role Name</label>
             <input type="text" class="form-control" id="name" name="name" value="{{ $role->name ?? '' }}"
                 required />
             <div class="invalid-feedback"></div>
         </div>
         <div class="col-md-6">
             <label class="form-label" for="user_type">Multi-select</label>
             <select class="form-select" id="user_type" name="user_type[]" multiple size="3">
                 @foreach ($users_type as $user_type)
                     <option value="{{ $user_type->id }}"
                         {{ isset($role->userTypes) && $role->userTypes->contains($user_type->id) ? 'selected' : '' }}>
                         {{ ucfirst($user_type->name) }}
                     </option>
                 @endforeach
             </select>
             <div class="invalid-feedback"></div>
         </div>
     </div>
 </div>



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
