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


    $('.sgi_form_delete').click(function (e) {
        e.preventDefault();

        if(confirm('Deseja mesmo REMOVER esse dado?')){
            $(this).submit();
        }

    });

    $('.sgi_form_cancel').click(function (e) {
        e.preventDefault();

        if(confirm('Deseja mesmo CANCELAR esse dado?')){
            $(this).submit();
        }

    });

    $('.sgi_form_chargeback').click(function (e) {
        e.preventDefault();

        if(confirm('Deseja mesmo ESTORNAR esse dado?')){
            $(this).submit();
        }

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


