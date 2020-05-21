$('._box').click(function () {
    if ($(this).hasClass('_checkbox-active')) {
        $(this).removeClass('_checkbox-active');
    } else {
        $(this).addClass('_checkbox-active');
    }
});
