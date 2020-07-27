$(document).ready(function () {
    let spreadsheet_document = $('#spreadsheet_document').text();
    let mask = spreadsheet_document.length === 14 ? '00.000.000/0000-00' : '000.000.000-00';
    $('.spreadsheet_document').mask(mask, {reverse: true});
});
