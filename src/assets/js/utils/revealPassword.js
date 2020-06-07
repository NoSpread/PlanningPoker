function revealPassword(id) {
    if ($(`#${id}`).hasClass('mdi-eye-outline')) {
        $(`#${id}`)
            .removeClass('mdi-eye-outline')
            .addClass('mdi-eye-off-outline');
        $(`#${id}`)
            .parents('._input-single')
            .children('._input')
            .attr('type', 'text');
    } else {
        $(`#${id}`)
            .removeClass('mdi-eye-off-outline')
            .addClass('mdi-eye-outline');
        $(`#${id}`)
            .parents('._input-single')
            .children('._input')
            .attr('type', 'password');
    }
}
