$('#call_center_phone').mask('(00) 00000-0000')
$('#call_center_prize_amount').maskMoney()

$('#prize_amount').maskMoney();

$('#prize_amount').on('keyup', () => {
    let value = $('#prize_amount').val()
    $('#prize_amount_hidden').val(value)
})

$('.sgi-select2').select2({
    theme: 'bootstrap4'
})

$('#call_center_prize_amount').on('keyup', () => {
    let value = $('#call_center_prize_amount').val()
    $('#call_center_prize_amount_hidden').val(value)
})
