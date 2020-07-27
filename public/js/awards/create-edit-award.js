$(document).ready(function () {
    $('#awarded_value').maskMoney();

    $('#awarded_type').change(function () {
        var awardedType = $(this).val();

        if (awardedType == 1) {
            $('#card_type').removeClass('d-none')
            $('#awarded_type_card').removeAttr('disabled')
            $('.status_all').addClass('d-none')
            $('.status_group').addClass('d-none')

        } else {
            $('#card_type').addClass('d-none')
            $('#awarded_type_card').attr('disabled')
        }

        if (awardedType == 2) {
            $('.status_payment_manual').val('')
            $('.payment_value').val('')

            $('.status_group').removeClass('d-none')
            $('.status_deposit_account').removeClass('d-none')
            $('.status_payment_manual').addClass('d-none')
            $('.upload-file').removeClass('d-none')

        } else {
            $('.status_deposit_account').addClass('d-none')
            $('.upload-file').addClass('d-none')
        }

        if (awardedType == 3) {
            $('.status_deposit_account').val('')

            $('.status_group').removeClass('d-none')
            $('.payment_manual_value').removeClass('d-none')
            $('.status_payment_manual').removeClass('d-none')
            $('.status_deposit_account').addClass('d-none')
            $('.discover_bank_status').removeClass('d-none')
            $('.awarded_date_payment_manual').removeClass('d-none')

        } else {
            $('.manual_payment_value').removeClass('d-none')
            $('.payment_manual_value').addClass('d-none')
            $('.discover_bank_status').addClass('d-none')
            $('.awarded_date_payment_manual').addClass('d-none')
        }
    });
});

$('.save-button').click(function (e) {
    $(this).addClass('disabled').html('<i class="fas fa-spinner"></i> Salvando...');
    $("form").submit(function(event){
        $(".save-button").attr('disabled', 'disabled');
    });
});
