/* 
animations = [
    {
        selector: String,
        method: String,
        duration: Int,
        cooldown: Int,
        infinite: Boolean
    }
];
*/

function animate_element(
    selector,
    method,
    duration,
    cooldown,
    infinite = false,
    delay = 0
) {
    let animation = [
        'animated',
        infinite ? 'infinite' : 'none',
        method,
        delay > 0 ? `delay-${delay}` : 'none'
    ];
    setInterval(() => {
        $(selector).addClass(animation);
        setTimeout(() => {
            $(selector).removeClass(animation);
        }, Math.round(duration / 1000) * 1000);
    }, Math.round(duration / 1000) * 1000 + Math.round(cooldown / 1000) * 1000);
}
