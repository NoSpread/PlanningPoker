var methods = ['error', 'info', 'success', 'warning'];
var methodIcons = {
    error: 'mdi-alert-octagon-outline',
    info: 'mdi-information-outline',
    success: 'mdi-check-outline',
    warning: 'mdi-alert-outline'
};

/**
 * Inputinfo Styler
 * @param {String} id 
 * @param {String} method 
 * @param {String} message 
 */
function addAfter(id, method, message) {
    if (
        methods.filter((x) => {
            return x == method;
        }).length != 1
    ) {
        console.error(`Method not found.`);
        return false;
    }

    if ($(`#${id} ._input-after`).length > 0) {
        console.error(`Input id "${id}" already has an _input-after element.`);
        return false;
    }

    $(`#${id}`).after(`
        <div class="_input-after _input-${method}">
            <i class="mdi mdi-24px ${methodIcons[method]}"></i>
            <div class="_input-after-text">
                ${message}
            </div>
        </div>
    `);
    return true;
}

/**
 * Deletes inputinfo styling
 * @param {String} id 
 */
function deleteAfter(id) {
    if ($(`#${id} + ._input-after`).length == 0) {
        console.error(
            `Input id "${id}" with an _input-after element doesn't exist.`
        );
        return false;
    }

    $(`#${id} ._input-after`).remove();
    return true;
}

/**
 * Changes method (color) and message
 * @param {String} id 
 * @param {String} newMessage 
 * @param {String} newMethod 
 */
function changeAfter(id, newMessage, newMethod = '') {
    if (id.length < 1) {
        console.error(`Input with id "${id}" doesn't exist.`);
        return false;
    }
    if ($(`#${id} + ._input-after`).length == 0) {
        console.error(
            `Input id "${id}" with an _input-after element doesn't exist.`
        );
        return false;
    }
    if (
        newMethod.length > 0 &&
        methods.filter((x) => {
            return x == newMethod;
        }).length != 1
    ) {
        console.error(`Method not found.`);
        return false;
    }
    if (newMethod.length > 0) {
        $(`#${id} + ._input-after`)
            .removeClass()
            .addClass(['_input-after', `_input-${newMethod}`])
            .children('i.mdi')
            .removeClass()
            .addClass(['mdi', 'mdi-24px', methodIcons[newMethod]]);
    }

    $(`#${id} + ._input-after ._input-after-text`).text(newMessage);
    return true;
}
