$(document).ready(function () {
    var collection_id = [];
    var domain = window.location.origin;

    function download(filename, url) {
        var element = document.createElement('a');
        element.setAttribute('href', url)
        element.setAttribute('download', filename);

        element.style.display = 'none';
        document.body.appendChild(element);

        element.click();
        document.body.removeChild(element);
    }

    $('#generate-vincs').click(function () {
        var id = $(this).data('id')
        var file = $(this).data('file')

        var path = `${domain}/admin/api/base-acesso-card-completo/${id}/update`
        var data = {
            base_acesso_card_generated: 1,
            shipment_file_vinc: file
        }

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.post(path, data, function (response) {
            console.log(response)

            let url = `${domain}/storage/shipments/${response}`
            console.log(url)
            download(response, url)
        })

        // window.location = `${domain}/admin/financeiro/remessas?tipo_premiacao=1`

        $('.alert').addClass('d-none')
    });

    $('.custom-control-input').click(function () {
        var data_id = $(this).data('id');

        if ($(`.check-id${data_id}`).is(':checked')) {
            var index = collection_id.indexOf(data_id);

            if (index == -1) {
                collection_id.push(data_id)
                $('.footer-bar').removeClass('d-none')
            }

            if (index == 0 || index == 1) {
                collection_id.splice(index, 1);
                $('.footer-bar').addClass('d-none')
            }
        }

        if (!$(`.check-id${data_id}`).is(':checked')) {
            var index = collection_id.indexOf(data_id);
            if (index > 1) {
                collection_id.splice(index, 1)
            }

            if (index == 0 || index == 1) {
                collection_id.splice(index, 1)
            }

            if (collection_id.length == 0) {
                $('.footer-bar').addClass('d-none')
            }
        }
    })

    var path = `${domain}/admin/api/acesso-card/store`
    var collection = {
        data: collection_id
    }

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $('.button-send').click(function () {
        $.post(path, collection, function (response) {
            console.log(response)

            let url = `${domain}/storage/shipments/${response}`
            console.log(url)
            download(response, url)

            window.location = `${domain}/admin/financeiro/remessas?tipo_premiacao=1`
        })
    })
})
