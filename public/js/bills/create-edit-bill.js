$(document).ready(function () {
    $('.sgi-select2').select2({
        theme: 'bootstrap4'
    });

    $('#bill_value').maskMoney();
});

$('.save-button').click(function (e) {
    $(this).addClass('disabled').html('<i class="fas fa-spinner"></i> Salvando...');
    $('.spinner-border').removeClass('d-none')

    $("form").submit(function(event){
        $(".save-button").attr('disabled', 'disabled');
    });
});
