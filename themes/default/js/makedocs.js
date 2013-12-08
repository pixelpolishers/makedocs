$(document).ready(function() {
    $('#meta-title').click(function() {
        $('#meta-data').slideToggle();
        $('#meta-title .fa-caret-up, #meta-title .fa-caret-down').toggleClass('fa-caret-up fa-caret-down');
    });
});