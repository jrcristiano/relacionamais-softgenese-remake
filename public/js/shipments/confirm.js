$('.sgi_form_delete').click(function (e) {
    return confirm('Deseja mesmo remover esse dado?') ? true : false;
});

$('.sgi_form_cancel').click(function (e) {
    return confirm('Deseja mesmo cancelar esse dado?') ? true : false;

});

$('.sgi_form_chargeback').click(function (e) {
    return confirm('Deseja mesmo estornar esse dado?') ? true : false;
});
