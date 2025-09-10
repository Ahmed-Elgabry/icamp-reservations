"use strict";
var KTAppEcommerceCategories = function () {
   
    var t, e, n = () => {
        t.querySelectorAll('[data-kt-ecommerce-category-filter="delete_row"]').forEach((t => {
            t.addEventListener("click", (function (t) {
                t.preventDefault();
                const n = t.target.closest("tr");
                // Try to get a friendly label; fallback to first cell text or data-id
                let o = '';
                const nameEl = n.querySelector('[data-kt-ecommerce-category-filter="category_name"]');
                if (nameEl && nameEl.innerText) {
                    o = nameEl.innerText.trim();
                } else {
                    const firstCell = n.querySelector('td');
                    o = (firstCell && firstCell.innerText ? firstCell.innerText.trim() : '') || ($(this).data('id') || '');
                }
                var item = $(this).data('id');
                var url =  $(this).data('url');
                Swal.fire({
                    text: `${$.localize.data['app']['common']['check_for_delete']}` + o + "?",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: `${$.localize.data['app']['common']['ok_delete']}`,
                    cancelButtonText: `${$.localize.data['app']['common']['no_cancel']}`,
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then((function (t) {

                    t.value ? $.ajax({
                        type: "delete",
                        url: url,
                        data: {"_token" :  $('meta[name="csrf-token"]').attr('content')},
                        dataType: "json",
                        success:  (response) => {
                            
                            // Update payment amount if response contains deleted_amount
                            //deleted_amount refer to the amount deleted from the payment
                            //deletedAmount refer to the amount deleted from the expense or payment according the return data response.deleted_expense_amount or response.deleted_amount

                            if (response.success && (response.deleted_amount || response.deleted_expense_amount)) {
                                let targetElement;
                                let deletedAmount;
                                console.log(response.deleted_amount)
                                if (response.deleted_amount) {
                                    
                                    targetElement = document.getElementById('payment-amount');
                                    console.log(targetElement);
                                    deletedAmount = parseFloat(response.deleted_amount) || 0;
                               } else if (response.deleted_expense_amount) {
                                    console.log("true");
                                    targetElement = document.getElementById('expense-amount');
                                    deletedAmount = parseFloat(response.deleted_expense_amount) || 0;
                                }
                                if (targetElement) {
                                    const currentAmount = parseFloat(targetElement.textContent) || 0
                                    const newAmount = currentAmount - deletedAmount;
                                    // Show as integer if no decimal part, otherwise show with decimals
                                    targetElement.textContent = newAmount % 1 === 0 ? newAmount.toString() : newAmount.toFixed(2);
                                }
                            }
                            
                            Swal.fire({
                                text: `${$.localize.data['app']['common']['deleted']}` + o + "!.",
                                icon: "success",
                                buttonsStyling: !1,
                                confirmButtonText: `${$.localize.data['app']['common']['got_it']}`,
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary"
                                }
                            })
                            e.row($(n)).remove().draw()
                        }
                    }).then((function () {

                    })) : "cancel" === t.dismiss && Swal.fire({
                        text: `${$.localize.data['app']['common']['not_deleted']}`,
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: `${$.localize.data['app']['common']['got_it']}`,
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary"
                        }
                    })
                }))
            }))
        }))
    };
    return {
        init: function () {
            (t = document.querySelector("#kt_ecommerce_category_table")) && ((e = $(t).DataTable({
                info: !1,
                order: [],
                pageLength: 10,
                columnDefs: [{
                    orderable: !1,
                    targets: 0
                }, {
                    orderable: !1,
                    targets: 3
                }]
            })).on("draw", (function () {
                n()
            })), document.querySelector('[data-kt-ecommerce-category-filter="search"]').addEventListener("keyup", (function (t) {
                e.search(t.target.value).draw()
            })), n())
        }
    }
}();

   
$(document).on('click','.checkSingle',function () {

    if ($(this).is(":checked")){
        var isAllchecked = 0;
        $(".checkSingle").each(function(){
            if(!this.checked)
                isAllchecked = 1;
        })
        if(isAllchecked != 1){ $("#checkedAll").prop("checked", true); }
        $('.delete_all_button').show()
    }else {
        var count = 0;
        $(".checkSingle").each(function(){
            if(this.checked)
                count ++;
        })
        if (count > 0 ) {
            $('.delete_all_button').show()
        }else{
            $('.delete_all_button').hide()
        }
        $("#checkedAll").prop("checked", false);
    }
});

$(document).on('change','#checkedAll',function(){
    if(this.checked){
            $(".checkSingle").each(function(index, element){
                this.checked = true;
                $('.delete_all_button').show()
            })
    }else{
        $(".checkSingle").each(function(){
            this.checked=false;
            $('.delete_all_button').hide()
        })
    }
});

$('.delete_all_button').on('click', function (e) {
    e.preventDefault()
    Swal.fire({
        text: `${$.localize.data['app']['common']['delete_selected']}`,
        icon: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        confirmButtonText:`${$.localize.data['app']['common']['ok_delete']}` ,
        cancelButtonText: `${$.localize.data['app']['common']['no_cancel']}`,
        customClass: {
            confirmButton: "btn fw-bold btn-danger",
            cancelButton: "btn fw-bold btn-active-light-primary"
        }
    }).then( (result) => {
        if (result.value) {
            var deletedItems = [];
            $('.checkSingle:checked').each(function () {
                var id = $(this).attr('id');
                deletedItems.push({
                    id: id,
                });
            });

            var requestData = JSON.stringify(deletedItems);
            if (deletedItems.length > 0) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: $(this).data('route'),
                    data: {data : requestData , "_token" :  $('meta[name="csrf-token"]').attr('content') },
                    
                    success: function( msg ) {
                        if (msg == 'success') {
                            $('.delete_all_button').hide()
                            Swal.fire({
                                text: `${$.localize.data['app']['common']['deleted_selected']}`,
                                icon: "success",
                                buttonsStyling: !1,
                                confirmButtonText: `${$.localize.data['app']['common']['got_it']}`,
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary"
                                }
                            })
                            $('.checkSingle:checked').each(function () {
                                $('#kt_ecommerce_category_table').DataTable().row($(this).closest('td').parent('tr')).remove().draw();
                            });
                            
                        }
                    }
                });
            }
        }
    })
});

KTUtil.onDOMContentLoaded((function () {
    KTAppEcommerceCategories.init()
}));



