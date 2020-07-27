$(document).ready(function () {
    let until = $('#provider_cnpj').data('id');
    let mask = $('#provider_cnpj').text().length === 14 ? '00.000.000/0000-00' : '000.000.000-00';

    for(let i = 1; i <= until; i++) {
        $(`.provider_cnpj_${i}`).mask(mask)
    }

    //$('.provider_cnpj').mask(mask, {reverse: true});

});
