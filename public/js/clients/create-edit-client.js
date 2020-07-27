$(document.body).ready(function(){$('#client_phone').mask('(00) 00000-0000');$('#client_cnpj').mask('00.000.000/0000-00',{reverse:true});$('#client_rate_admin').mask('##0,00%', {reverse: true});$('#client_comission_manager').mask('##0,00%', {reverse: true})})

$('.save-button').click(function (e) {
    $(this).addClass('disabled').html('<i class="fas fa-spinner"></i> Salvando...');
    $("form").submit(function(event){
        $(".save-button").attr('disabled', 'disabled');
    });
});
