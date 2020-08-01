$(document).ready(function () {
    var collection_id = [];
    var domain = window.location.origin;

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

    var path = `${domain}/admin/api/shipment/store`
    var collection = {
        data: collection_id
    }

    $('.button-send').click(function () {
        function download(filename, url) {
            var element = document.createElement('a');
            element.setAttribute('href', url)
            element.setAttribute('download', filename);

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();
            document.body.removeChild(element);
        }

        $('.footer-bar').addClass('d-none')

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.post(path, collection, function (response) {

            console.log(response);
            let filename = response.substring(1, (response.length))
            let url = `${domain}/storage/shipments/${filename}`

            download(filename, url)
            window.location = `${domain}/admin/financeiro/remessas`
        })
    })
})
