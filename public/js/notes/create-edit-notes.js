$(document).ready(function () {
    $('#note_receipt_award_real_value').maskMoney()
    $('#note_receipt_taxable_real_value').maskMoney()
    $('#note_receipt_other_value').maskMoney()

    $('#select_field').change(function () {
        let value = $(this).val()

        if (value == 1) {
            $('.award_value').addClass('d-none')
            $('.other_values').addClass('d-none')
            $('.patrimony').removeClass('d-none')

            $('.award_value input').val('')
            $('.other_values input').val('')
        }

        if (value == 2) {
            $('.patrimony').addClass('d-none')
            $('.award_value').removeClass('d-none')

            $('.patrimony input').val('')
            $('.other_values input').val('')
        }

        if (value == 3) {
            $('.patrimony').addClass('d-none')
            $('.award_value').addClass('d-none')
            $('.other_values').removeClass('d-none')

            $('.award_value input').val('')
            $('.patrimony input').val('')
        }
    });

    $('.note_status').change(function () {
        let status = $(this).val();

        if (status == 2) {
            $('#sgi-note-receipt_date').removeClass('d-none')
        }
    });
});

$('.save-button').click(function (e) {
    $(this).addClass('disabled').html('<i class="fas fa-spinner"></i> Salvando...');
    $("form").submit(function(event){
        $(".save-button").attr('disabled', 'disabled');
    });
});
