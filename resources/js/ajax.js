const csrfToken = document
    .querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    


window.ajaxRequest = async function (url, method = 'POST', data = null) {

    let options = {
        method: method,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };


    if (data) {
        options.body = data;
    }


    let response = await fetch(url, options);

    let result = await response.json();


    if (!response.ok) {
           throw {
        status: response.status,
        ...result
    };
    }


    return result;
};

window.showErrors = function(errors){


    // إزالة الأخطاء القديمة
    document
        .querySelectorAll('.is-invalid')
        .forEach(input => {

            input.classList.remove('is-invalid');

        });


    document
        .querySelectorAll('.invalid-feedback')
        .forEach(feedback => {

            feedback.innerHTML = '';

        });



    // إضافة الأخطاء الجديدة
    Object.keys(errors).forEach(field => {


        let input = document.querySelector(`[name="${field}"]`);


        if(input){

            input.classList.add('is-invalid');


            let feedback = input
                .closest('.col-md-6, .col-md-12')
                .querySelector('.invalid-feedback');


            if(feedback){

                feedback.innerHTML = errors[field][0];

            }

        }


    });

};