 <div class="card-body">
     <div class="row g-3">
         <div class="col-md-6">
             <label for="description" class="form-label">Description</label>
             <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $issue->description ?? '') }}</textarea>
             <div class="invalid-feedback"></div>
         </div>

         <div class="col-md-6">
             <label for="major_id" class="form-label">Major <small class="text-muted">(Optional)</small></label>
             <select class="form-select" id="major_id" name="major_id">
                 <option selected disabled value="">Choose&hellip;</option>
                 @foreach ($majors as $major)
                     <option value="{{ $major->id }}"
                         {{ ($issue->major_id ?? '') === $major->id ? 'selected' : '' }}>
                         {{ $major->trans_name }}
                     </option>
                 @endforeach
             </select>
             <div class="invalid-feedback"></div>
         </div>

         <div class="col-md-6">
             <label for="category_id" class="form-label">Category</label>
             <select class="form-select" id="category_id" name="category_id">
                 <option selected disabled value="">Choose&hellip;</option>
                 @foreach ($categories as $category)
                     <option value="{{ $category->id }}" data-fields="{{ json_encode($category->form_fields) }}"
                         {{ ($issue->category_id ?? '') === $category->id ? 'selected' : '' }}>
                         {{ $category->title }}
                     </option>
                 @endforeach
             </select>
             <div class="invalid-feedback"></div>
         </div>

         <div id="dynamic-fields-section" class="d-none">
             <h5 class="mb-3 d-inline-block rounded bg-primary  text-white p-2"><i class="feather-edit me-1"></i>Dynamic Fields</h5>
             <div id="dynamic-fields-container" class="row">
                 <!-- سيتم إضافة الحقول هنا تلقائياً بـ JS -->
             </div>
         </div>
     </div>

 </div>
@push('dynamic-fields-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        const fieldsSection = document.getElementById('dynamic-fields-section');
        const fieldsContainer = document.getElementById('dynamic-fields-container');

        // تمرير البيانات السابقة المخزنة في القضية من Blade إلى JS
        const existingFormData = @json($issue->form_data ?? []);
        

        function renderDynamicFields() {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            if (!selectedOption) return;

            const rawFields = selectedOption.getAttribute('data-fields');

            // تفريغ الحاوية أولاً
            fieldsContainer.innerHTML = '';

            if (!rawFields || rawFields === 'null') {
                fieldsSection.classList.add('d-none');
                return;
            }

            try {
                const fields = JSON.parse(rawFields); // مصفوفة أسماء الحقول

                if (Array.isArray(fields) && fields.length > 0) {
                    fieldsSection.classList.remove('d-none');

                    fields.forEach(fieldKey => {
                        // تحويل اسم الحقل لمظهر مقروء
                        const labelText = fieldKey.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        
                        // جلب القيمة المخزنة سابقاً إن وجدت
                        const fieldValue = existingFormData[fieldKey] ?? '';

                        const fieldHtml = `
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">${labelText}</label>
                                <input type="text" 
                                       name="form_data[${fieldKey}]" 
                                       class="form-control" 
                                       placeholder="أدخل ${labelText}" 
                                       value="${fieldValue}"
                                       required>
                                <div class="invalid-feedback"></div>
                            </div>
                        `;
                        fieldsContainer.insertAdjacentHTML('beforeend', fieldHtml);
                    });
                } else {
                    fieldsSection.classList.add('d-none');
                }
            } catch (e) {
                console.error("Error parsing fields JSON", e);
                fieldsSection.classList.add('d-none');
            }
        }

        // 1. تشغيل الدالة فور تحميل الصفحة (لدعم التعديل Edit و old input)
        renderDynamicFields();

        // 2. تشغيل الدالة عند تغيير التصنيف
        categorySelect.addEventListener('change', renderDynamicFields);
    });
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

                             showErrors(error.errors ?? {}, form);

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
