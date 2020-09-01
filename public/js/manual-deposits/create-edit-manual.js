$(document).ready(function () {
    $('#awarded_value').maskMoney();
});

$('.save-button').click(function (e) {
    $(this).addClass('disabled').html('<i class="fas fa-spinner"></i> Salvando...');
    $('#new-loading').removeClass('d-none')

    $("form").submit(function(event){
        $(".save-button").attr('disabled', 'disabled');
    });
});
