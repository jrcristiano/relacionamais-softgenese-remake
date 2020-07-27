$(document).ready(function () {
    var company, cnpj, tax

    function number_format (number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    $('#demand_taxable_manual').maskMoney()
    $('#demand_taxable_amount').maskMoney()

    $('.sgi-select2').select2({
        theme: 'bootstrap4'
    });

    $('input[name=demand_prize_amount]').maskMoney()

    $('#demand_form').change(function () {
        company = $('.sgi-select2 option:selected').text()
        cnpj = $('.sgi-select2 option:selected').val()
        cnpj = cnpj.replace(/[^0-9]/g, '')

        $('#demand_client_name').attr('value', company)
    });

    $("#demand_prize_amount").keyup(function () {
        var url = window.location.origin

        let awardValue = $('#demand_prize_amount').val()
            awardValue = awardValue.replace(/[^0-9]/g, '')
            awardValue = parseFloat(awardValue)

            let path = `${url}/admin/api/demand/award-value/${awardValue}/cnpj/${cnpj}`

        $.get(path, function (response) {
            tax = response.demand_taxable_amount / 100
            tax = tax.toFixed(2)

            tax = number_format(tax, 2, ',', '.')

            if (!$('#trib_manual').is(':checked')) {
                $('input[name=demand_taxable_amount]').val(tax)
            }
        })
    })

    $('#demand_client_cnpj').mask('00.000.000/0000-00', {reverse:true})

    $('#add_value').click(function () {
        let checked = $(this).is(':checked')
        if (checked) {
            $('.other_value').removeClass('d-none')
        } else {
            $('.other_value').addClass('d-none')
        }
    })

    $('input[name=demand_other_value]').maskMoney()

    $('#trib_manual').click(function () {
        let checked = $(this).is(':checked')

        if (checked) {
            $('.checkbox_taxable_amount').text('Valor trib. manual')
            $('#demand_taxable_amount').attr('name', 'demand_taxable_manual')
                .attr('id', 'demand_taxable_manual')
                .attr('name', 'demand_taxable_manual')
                .removeAttr('disabled')
                .maskMoney()
        } else {
            $('.checkbox_taxable_amount').text('Valor tributável')
            $('#demand_taxable_manual')
                .attr('id', 'demand_taxable_amount')
                .attr('name', 'demand_taxable_amount')
                .attr('disabled', 'disabled')
                .maskMoney()
        }
    })

    $('.save-button').click(function () {
        $('#demand_form').submit();
    })
})
