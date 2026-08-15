 <div class="card-body">
     <div class="row g-3">
         <div class="col-md-6">
             <label for="title" class="form-label">Title</label>
             <input type="text" class="form-control" id="title" name="title" value="{{ $category->title ?? '' }}"
                 required />
             <div class="invalid-feedback"></div>
         </div>
         <div class="col-md-6">
             <label for="form_fields" class="form-label">Form Fields</label>
             <input type="text" class="form-control" id="form_fields" name="form_fields"
                 value="{{ old('form_fields', is_array($category->form_fields ?? null) ? implode(',', $category->form_fields) : '') }}"
                 placeholder="postpone_semester, postpone_reason , postpone_date" />
             <small class="text-muted">افصل بين القيم بفاصلة</small>
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
