$(document).ready(function(){
    $('#manager_phone').mask('(00) 00000-0000');
    $('#manager_cpf').mask('000.000.000-00', {reverse:true});
});

$('.save-button').click(function (e) {
    $(this).addClass('disabled').html('<i class="fas fa-spinner"></i> Salvando...');
    $("form").submit(function(event){
        $(".save-button").attr('disabled', 'disabled');
    });
});
