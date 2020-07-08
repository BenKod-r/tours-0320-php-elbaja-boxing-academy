/* global upload, upload */
$('input[type=file]').change(function (e) { $(this).next('.custom-file-label').text(e.target.files[0].name); });
