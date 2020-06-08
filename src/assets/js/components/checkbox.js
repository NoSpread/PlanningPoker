/**
 * Grab all checkboxes on a page and create an invisible input for fancy styling
 */
function collectCheckboxes() {
    const boxes = $('._box');
    for (let i = 0; i < boxes.length; i++) {
        $(`#${boxes[i].id}`).after(`
            <input id="${$(boxes[i]).prop(
                'id'
            )}Box" class="d-none" type="checkbox" value="${$(boxes[i]).prop(
            'id'
        )}" name="checkList[]" checked="${$(boxes[i]).hasClass(
            '_checkbox-active'
        )}">
        `);
    }
}

/**
 * Toogles invisible checkbox to the same state as fancy checkbox
 * @param {String} id 
 */
function setChecked(id) {
    if ($(`#${id}`).hasClass('_checkbox-active')) {
        $(`#${id}`).removeClass('_checkbox-active');
    } else {
        $(`#${id}`).addClass('_checkbox-active');
    }

    $(`#${id}Box`).prop('checked', $(`#${id}`).hasClass('_checkbox-active'));
}

$('._box').click(function () {
    setChecked(this.id);
});

collectCheckboxes();
