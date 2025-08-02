"use strict";
var KTModalMailAdd = function () {
    var t, e, o, n, r, i , q;
    return {
        init: function () {
           
            i = new bootstrap.Modal(document.querySelector("#kt_modal_add_mail")), r = document.querySelector("#kt_modal_add_mail_form"), t = r.querySelector("#kt_modal_add_mail_submit"), e = r.querySelector("#kt_modal_add_mail_cancel"), o = r.querySelector("#kt_modal_add_mail_close"), n = FormValidation.formValidation(r, {
               
            }), t.addEventListener("click", (function (e) {
                e.preventDefault(), n && n.validate().then((function (e) {
                    console.log("validated!"), "Valid" == e ? (t.setAttribute("data-kt-indicator", "on"), t.disabled = !0, setTimeout((function () {
                        t.removeAttribute("data-kt-indicator"), Swal.fire({
                            text: "Form has been successfully submitted!",
                            icon: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then((function (e) {
                            e.isConfirmed && (i.hide(), t.disabled = !1, window.location = r.getAttribute("data-kt-redirect"))
                        }))
                    }), 2e3)) : Swal.fire({
                        text: "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })
                }))
            })), e.addEventListener("click", (function (t) {
                t.preventDefault(), Swal.fire({
                    text: "Are you sure you would like to cancel?",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "Yes, cancel it!",
                    cancelButtonText: "No, return",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light"
                    }
                }).then((function (t) {
                    t.value ? (r.reset(), i.hide()) : "cancel" === t.dismiss && Swal.fire({
                        text: "Your form has not been cancelled!.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })
                }))
            })), o.addEventListener("click", (function (t) {
                t.preventDefault(), Swal.fire({
                    text: "Are you sure you would like to cancel?",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "Yes, cancel it!",
                    cancelButtonText: "No, return",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light"
                    }
                }).then((function (t) {
                    t.value ? (r.reset(), i.hide()) : "cancel" === t.dismiss && Swal.fire({
                        text: "Your form has not been cancelled!.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })
                }))
            }))
        }
    }
}();
KTUtil.onDOMContentLoaded((function () {
    KTModalMailAdd.init()
}));
