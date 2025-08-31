$(document).ready(function(){
    const t = document.getElementById("kt_ecommerce_add_product_form"),
    o = document.getElementById("kt_ecommerce_add_product_submit");

    $(document).on('submit','.store',function(e){
        e.preventDefault();
        var $form = $(this);
        var url = $form.attr('action');
        $.ajax({
            url: url,
            method: 'post',
            data: new FormData($form[0]),
            dataType:'json',
            processData: false,
            contentType: false,
            success: function(response){
                o && o.setAttribute && o.setAttribute("data-kt-indicator", "on");
                o && (o.disabled = !0);
                setTimeout((function () {
                    o && o.removeAttribute && o.removeAttribute("data-kt-indicator");
                    var successMsg = $form.attr('data-success-message') || `${$.localize.data['app']['common']['submitted']}`;
                    Swal.fire({
                        text: successMsg,
                        icon: "success",
                        buttonsStyling: !1,
                        confirmButtonText: `${$.localize.data['app']['common']['got_it']}`,
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then((function () {
                        // Emit a success event so pages can react without reload
                        $form.trigger('store:success', [response]);
                        // Optional redirect if attribute provided
                        const redirect = t && t.getAttribute ? t.getAttribute("data-kt-redirect") : null;
                        if (redirect) {
                            window.location = redirect;
                        } else {
                            if (o) o.disabled = !1;
                        }
                    }))
                }), 2e3);
            },
            error: function (xhr) {
                Swal.fire({
                    html: `${$.localize.data['app']['common']['general_error']}`,
                    icon: `${$.localize.data['app']['common']['error']}`,
                    buttonsStyling: !1,
                    confirmButtonText: `${$.localize.data['app']['common']['got_it']}`,
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });

                $(".text-danger").remove();
                $form.find('input').removeClass('border-danger');
                $form.find('textarea').removeClass('border-danger');

                $.each(xhr.responseJSON.errors, function(key,value) {
                    var ar_item  =  key.includes('.ar') ?  key.replace(".ar", "[ar]") : key;
                    var en_item  =  key.includes('.en') ?  key.replace(".en", "[en]") : key;
                    if(ar_item != en_item) {
                        $form.find('input[name="' + ar_item + '"]').addClass('border-danger');
                        $form.find('input[name="' + ar_item + '"]').after(`<span class="mt-5 text-danger">${value}</span>`);
                        $form.find('input[name="' + en_item + '"]').addClass('border-danger');
                        $form.find('input[name="' + en_item + '"]').after(`<span class="mt-5 text-danger">${value}</span>`);
                       
                        $form.find('select[name="' + ar_item + '"]').after(`<span class="mt-5 text-danger">${value}</span>`);
                        $form.find('select[name="' + en_item + '"]').after(`<span class="mt-5 text-danger">${value}</span>`);
    
                        $form.find('textarea[name="' + ar_item + '"]').addClass('border-danger');
                        $form.find('textarea[name="' + ar_item + '"]').after(`<span class="mt-5 text-danger">${value}</span>`);
                        $form.find('textarea[name="' + en_item + '"]').addClass('border-danger');
                        $form.find('textarea[name="' + en_item + '"]').after(`<span class="mt-5 text-danger">${value}</span>`);
                    } else {
                        $form.find('input[name="' + ar_item + '"]').addClass('border-danger');
                        $form.find('input[name="' + ar_item + '"]').after(`<span class="mt-5 text-danger">${value}</span>`);
                        
                        $form.find('select[name="' + ar_item + '"]').after(`<span class="mt-5 text-danger">${value}</span>`);
                        $form.find('select[name="' + en_item + '"]').after(`<span class="mt-5 text-danger">${value}</span>`);
    
                        $form.find('textarea[name="' + ar_item + '"]').addClass('border-danger');
                        $form.find('textarea[name="' + ar_item + '"]').after(`<span class="mt-5 text-danger">${value}</span>`);
                    }
                });

                if (xhr.responseJSON.error) {
                    Swal.fire({
                        html: xhr.responseJSON.error,
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            }
        });
    });
});
