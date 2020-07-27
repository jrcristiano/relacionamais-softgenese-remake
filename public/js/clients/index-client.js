$(document).ready(function () {
    $('.client_phone').mask('(00) 00000-0000');

    let client_cnpj = $('.client_cnpj').text();
    let mask = client_cnpj.length === 14 ? '00.000.000/0000-00' : '000.000.000-00';
    $('.client_cnpj').mask(mask, {reverse: true});
});
