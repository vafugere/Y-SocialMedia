$(document).ready(function () {
    $('#home_icon').hover(
        function () {
            $(this).attr('src', 'images/icons/home-hover.png');
        },
        function () {
            $(this).attr('src', 'images/icons/home.png');
        }
    );
});