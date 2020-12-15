$.fn.serializeObject=function(){var formArray=$(this).serializeArray();for(var i=0;i<formArray.length;i++){result[formArray[i]['name']]=formArray[i]['value'];}return result;};
$(document).ready(function () {

    // Buscas gerais
    $("#filter_table").on("keyup",function () {
        var value = $(this).val().toLowerCase();
        $("#client_table tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value)>-1)
        });
    });

    // Busca de NF
    $("#filter_table_nfe").on("keyup",function () {
        var value = $(this).val().toLowerCase();
        $("#client_table tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value)>-1)
        });
    });

    $('[data-toggle="tooltip"]').tooltip();

    $('#sgi-mobile-menu').click(function () {
        $('.sgi-vh-100').show();
        document.body.style.overflow='hidden';
    });

    $('#sgi-btn-close').click(function () {
        $('.sgi-vh-100').hide();
        document.body.style.overflow='auto';
    });
});
