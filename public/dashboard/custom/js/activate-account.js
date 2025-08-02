$('#kt_account_deactivate_form').validate({
    rules: {
      
      "deactivate" : {
          required : true ,
      },

    }
})


$(document).on('submit','#kt_account_deactivate_form',function(e){
        e.preventDefault();
        var url = $(this).attr('action')
        $.ajax({
            url: url,
            method: 'post',
            data: new FormData($(this)[0]),
            dataType:'json',
            processData: false,
            contentType: false,
           success: function(response){
          
            Swal.fire({
                text: `${$.localize.data['app']['common']['submitted']}`,
                icon: "success",
                buttonsStyling: !1,
                confirmButtonText: `${$.localize.data['app']['common']['got_it']}`,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            }).then((function (e) {
                if(response.is_active == 1)
                {
                    $('#kt_account_deactivate_account_submit').text(`${$.localize.data['app']['common']['deactivate_account']}`);
                    $('#deactivate_text').text(`${$.localize.data['app']['common']['deactivate_text']}`);
                    $('#deactivate_desc').html(`${$.localize.data['app']['common']['deactivate_desc']}`);
                }else{
                    $('#kt_account_deactivate_account_submit').text(`${$.localize.data['app']['common']['activate_account']}`);
                    $('#deactivate_text').text(`${$.localize.data['app']['common']['activate_text']}`);
                    $('#deactivate_desc').html(`${$.localize.data['app']['common']['activate_desc']}`);

                }
                $('#deactivate').prop("checked" , false);
            }))
            
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
                })  
            }
        });
});