let mainBoxes = [
    /*
	{
		index: Number,
		id: String,
		state: Boolean
	}
	*/
];

const collectBoxes = () => {
    const boxes = $('.main-box');
    for (let i = 0; i < boxes.length; i++) {
        console.log(boxes[i]);
        mainBoxes[i] = {
            index: i,
            id: $(boxes[i]).attr('id'),
            state: $(boxes[i]).hasClass('main-box-open') ? true : false
        };

        // there is no main-box-static that is closed on default
        if ($(`#${mainBoxes[i].id}`).hasClass('main-box-static')) continue;

        $(`#${mainBoxes[i].id} .main-box-footer .main-box-expand i`).addClass(
            'mdi-chevron-up'
        );

        if (!mainBoxes[i].state)
            setState([mainBoxes[i].id], mainBoxes[i].state);

        $(`#${mainBoxes[i].id} .main-box-footer`).on('click', () => {
            setState([mainBoxes[i].id], !mainBoxes[i].state);
        });
    }
    return true;
};

const filterBoxes = (id) => {
    return new Promise((resolve, reject) => {
        const boxes = mainBoxes.filter((x) => $.inArray(x.id, id) != -1);
        if (!boxes.length) reject(`No box found!`);
        else resolve(boxes);
    });
};

const setState = async (id, state) => {
    const boxes = await filterBoxes(id);
    boxes.forEach((x) => {
        state
            ? $(`#${x.id}`).addClass('main-box-open')
            : $(`#${x.id}`).removeClass('main-box-open');
        mainBoxes[x.index].state = state;
        transformBoxes([mainBoxes[x.index]]);
    });
    return true;
};

const transformBoxes = (boxes) => {
    boxes.forEach((x) => {
        if (x.state) {
            $(`#${x.id} .main-box-content`).slideDown();
            $(`#${x.id} .main-box-footer`).animate({ bottom: '-=44px' }, 500);
            $(`#${x.id} .main-box-footer .main-box-footer-content`).fadeIn(500);
            $(`#${x.id} .main-box-footer .main-box-expand i`).css({
                transform: 'rotate(360deg)'
            });
        } else {
            $(`#${x.id} .main-box-content`).slideUp();
            $(`#${x.id} .main-box-footer`).animate({ bottom: '+=44px' }, 500);
            $(`#${x.id} .main-box-footer .main-box-footer-content`).fadeOut(
                500
            );
            $(`#${x.id} .main-box-footer .main-box-expand i`).css({
                transform: 'rotate(180deg)'
            });
        }
    });
    return true;
};

(() => {
    collectBoxes();
})();
