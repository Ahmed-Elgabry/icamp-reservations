$(document).ready(function(){
    const t = document.getElementById("kt_ecommerce_add_product_form");

    // Global Delete Confirmation Function
    window.confirmDelete = function(deleteUrl, csrfToken) {
        // Check if Swal is available
        if (typeof Swal === 'undefined') {
            alert('SweetAlert is not loaded!');
            return;
        }
        
        // Get localized text from global translations object or fallback to English
        const confirmTitle = (window.deleteTranslations && window.deleteTranslations.confirm_delete) || 'Confirm Delete';
        const confirmText = (window.deleteTranslations && window.deleteTranslations.delete_warning_message) || 'Are you sure you want to delete this item? This action cannot be undone.';
        const confirmButton = (window.deleteTranslations && window.deleteTranslations.yes_delete) || 'Yes, Delete';
        const cancelButton = (window.deleteTranslations && window.deleteTranslations.cancel) || 'Cancel';
        
        Swal.fire({
            title: confirmTitle,
            text: confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: confirmButton,
            cancelButtonText: cancelButton,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit delete form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.style.display = 'none';
                
                // Add CSRF token
                const csrfTokenField = document.createElement('input');
                csrfTokenField.type = 'hidden';
                csrfTokenField.name = '_token';
                csrfTokenField.value = csrfToken || $('meta[name="csrf-token"]').attr('content') || '';
                form.appendChild(csrfTokenField);
                
                // Add DELETE method
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                // Add redirect back to current page
                const redirectField = document.createElement('input');
                redirectField.type = 'hidden';
                redirectField.name = 'redirect_back';
                redirectField.value = window.location.href;
                form.appendChild(redirectField);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    };

    $(document).on('submit','.store',function(e){
        e.preventDefault();
        var $form = $(this);
        var url = $form.attr('action');
        // Find this form's submit button and use it for indicator
        var o = $form.find('#kt_ecommerce_add_product_submit')[0] || $form.find('button[type="submit"]')[0] || null;
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
                    var successMsg = $form.attr('data-success-message') || ($.localize && $.localize.data && $.localize.data['app'] && $.localize.data['app']['common'] ? $.localize.data['app']['common']['submitted'] : 'Submitted successfully');
                    Swal.fire({
                        text: successMsg,
                        icon: "success",
                        buttonsStyling: !1,
                        confirmButtonText: `${$.localize && $.localize.data && $.localize.data['app'] && $.localize.data['app']['common'] ? $.localize.data['app']['common']['got_it'] : 'OK'}`,
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then((function () {
                        // Emit a success event so pages can react without reload
                        $form.trigger('store:success', [response]);
                        // Close any parent modal if present
                        try {
                            var $modal = $form.closest('.modal');
                            if ($modal && $modal.length) {
                                var modalInst = bootstrap.Modal.getInstance($modal[0]) || new bootstrap.Modal($modal[0]);
                                modalInst.hide();
                            }
                        } catch (e) {}
                        // Optional redirect if attribute provided
                        const redirect = ($form.attr('data-kt-redirect')) || (t && t.getAttribute ? t.getAttribute("data-kt-redirect") : null);
                        if (redirect) {
                            window.location = redirect;
                        } else {
                            if (o) o.disabled = !1;
                        }
                    }))
                }), 2e3);
            },
            error: function (xhr) {
                var fallbackGeneral = ($.localize && $.localize.data && $.localize.data['app'] && $.localize.data['app']['common'] && $.localize.data['app']['common']['general_error']) || 'An error occurred';
                var msg = fallbackGeneral;
                try {
                    if (xhr && xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.errors && typeof xhr.responseJSON.errors === 'object') {
                            var errs = [];
                            Object.keys(xhr.responseJSON.errors).forEach(function(k){
                                var v = xhr.responseJSON.errors[k];
                                if (Array.isArray(v)) { errs = errs.concat(v); } else if (v) { errs.push(v); }
                            });
                            if (errs.length) { msg = errs.join('\n'); }
                        }
                    } else if (xhr && xhr.responseText) {
                        try {
                            var parsed = JSON.parse(xhr.responseText);
                            if (parsed && (parsed.message || parsed.error)) {
                                msg = parsed.message || parsed.error;
                            } else {
                                msg = xhr.responseText;
                            }
                        } catch (e) {
                            msg = xhr.responseText;
                        }
                    }
                } catch (e) {}

                Swal.fire({
                    html: msg,
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: `${$.localize && $.localize.data && $.localize.data['app'] && $.localize.data['app']['common'] ? $.localize.data['app']['common']['got_it'] : 'OK'}`,
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });

                $(".text-danger").remove();
                $form.find('input').removeClass('border-danger');
                $form.find('textarea').removeClass('border-danger');

                var respErrors = (xhr && xhr.responseJSON && xhr.responseJSON.errors && typeof xhr.responseJSON.errors === 'object') ? xhr.responseJSON.errors : null;
                if (respErrors) $.each(respErrors, function(key,value) {
                    var ar_item  =  key.includes('.ar') ?  key.replace(".ar", "[ar]") : key;
                    var en_item  =  key.includes('.en') ?  key.replace(".en", "[en]") : key;
                    var valText = Array.isArray(value) ? value.join(' ') : value;
                    if(ar_item != en_item) {
                        $form.find('input[name="' + ar_item + '"]').addClass('border-danger');
                        $form.find('input[name="' + ar_item + '"]').after(`<span class="mt-5 text-danger">${valText}</span>`);
                        $form.find('input[name="' + en_item + '"]').addClass('border-danger');
                        $form.find('input[name="' + en_item + '"]').after(`<span class="mt-5 text-danger">${valText}</span>`);
                       
                        $form.find('select[name="' + ar_item + '"]').after(`<span class="mt-5 text-danger">${valText}</span>`);
                        $form.find('select[name="' + en_item + '"]').after(`<span class="mt-5 text-danger">${valText}</span>`);
    
                        $form.find('textarea[name="' + ar_item + '"]').addClass('border-danger');
                        $form.find('textarea[name="' + ar_item + '"]').after(`<span class="mt-5 text-danger">${valText}</span>`);
                        $form.find('textarea[name="' + en_item + '"]').addClass('border-danger');
                        $form.find('textarea[name="' + en_item + '"]').after(`<span class="mt-5 text-danger">${valText}</span>`);
                    } else {
                        $form.find('input[name="' + ar_item + '"]').addClass('border-danger');
                        $form.find('input[name="' + ar_item + '"]').after(`<span class="mt-5 text-danger">${valText}</span>`);
                        
                        $form.find('select[name="' + ar_item + '"]').after(`<span class="mt-5 text-danger">${valText}</span>`);
                        $form.find('select[name="' + en_item + '"]').after(`<span class="mt-5 text-danger">${valText}</span>`);
    
                        $form.find('textarea[name="' + ar_item + '"]').addClass('border-danger');
                        $form.find('textarea[name="' + ar_item + '"]').after(`<span class="mt-5 text-danger">${valText}</span>`);
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

    $(document).on('submit', '.update', function(e) {
        e.preventDefault();
        var $form = $(this);
        var url = $form.attr('action');
        var o = $form.find('#kt_ecommerce_add_product_submit')[0] || $form.find('button[type="submit"]')[0] || null;

        $.ajax({
            url: url,
            method: 'post',
            data: new FormData($form[0]),
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                o && o.setAttribute && o.setAttribute("data-kt-indicator", "on");
                o && (o.disabled = !0);
                setTimeout(function() {
                    o && o.removeAttribute && o.removeAttribute("data-kt-indicator");
                    var successMsg = $form.attr('data-success-message') || 'Updated successfully';
                    Swal.fire({
                        text: successMsg,
                        icon: "success",
                        buttonsStyling: !1,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(function() {
                        $form.trigger('update:success', [response]);
                        const redirect = $form.attr('data-kt-redirect');
                        if (redirect) {
                            window.location = redirect;
                        } else {
                            if (o) o.disabled = !1;
                        }
                    });
                }, 2000);
            },
            error: function(xhr) {
                var msg = 'An error occurred';
                if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire({
                    html: msg,
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        });
    });
});
