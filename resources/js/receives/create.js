$(document).ready(function () {
    $('.sgi-select2').select2({
        theme:'bootstrap4'
    });

    var company, cnpj;

    $('#receive_form').change(function () {
        company = $('.sgi-select2 option:selected').text();

        cnpj = $('.sgi-select2 option:selected').val();
        cnpj = cnpj.replace(/[^0-9]/g, '');

        $('#receive_client_name').attr('value', company);
    });

    $("#receive_prize_amount").keyup(function () {
        let awardValue=$('#receive_prize_amount').val();
        let url = `http://softgenese.com/admin/api/demand/award-value/${awardValue}/cnpj/${cnpj}`;

        $.get(url,function (response) {
            let tax = response.demand_taxable_amount;
                tax = JSON.parse(tax);
                $('#receive_taxable_amount').attr('value', tax);
            });
        });

        $('#demand_client_cnpj').mask('00.000.000/0000-00', {reverse:true});
        $('#bill_value').maskMoney();
    });
