$('#my_tweet').focus(function() {
    $(this).attr('rows', 4);
})
.blur(function() {
    if (!$(this).val().trim()) {
        $(this).attr('rows', 1);
    }
});