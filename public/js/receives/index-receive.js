$(document).ready(function () {
    $('#receive_client').select2({
        theme: 'bootstrap4'
    });

    $('.sgi-shipment').click(function () {
        let awarded_id = $(this).data('value')

        $(this).addClass('disabled').html('<i class="fas fa-check"></i> Remessa gerada')

        $(`.sgi-shipment-generate-${awarded_id}`).click()
    })

    $('.sgi-cancel').click(function () {
        $(this).css({display: 'none'})
    })
})
