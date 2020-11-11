$('#call_center_phone').mask('(00) 00000-0000')

$('.sgi-select2').select2({
    theme: 'bootstrap4'
});

$('.save-button').click(function (e) {
    $(this).addClass('disabled').html('<i class="fas fa-spinner"></i> Salvando...');
    $('#new-loading').removeClass('d-none')

    $("form").submit(function(event){
        $(".save-button").attr('disabled', 'disabled');
    });
});
