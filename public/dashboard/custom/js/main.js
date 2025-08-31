$(document).ready(function(){
    $('.delete_all_button').hide();

    let langOptions = {
        language: $('html').attr('lang'),
        pathPrefix: "/dashboard/lang"
    };
    $("[data-localize]").localize('app', langOptions);






});