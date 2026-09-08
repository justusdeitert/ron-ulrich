/**
 * "More info" toggle + archive <select> sync, ported from the old
 * jQuery snippets in resources/assets/scripts/main.js.
 * The open state is kept in localStorage.
 */
function initMoreInfo(): void {
    if (localStorage.getItem('more-info')) {
        document.body.classList.add('more-info');
    }

    document.querySelector('.more-info .info-left')?.addEventListener('click', () => {
        if (document.body.classList.toggle('more-info')) {
            localStorage.setItem('more-info', 'true');
        } else {
            localStorage.removeItem('more-info');
        }
    });
}

function initSelectFields(): void {
    for (const select of document.querySelectorAll<HTMLSelectElement>('.more-info select')) {
        select.addEventListener('change', () => {
            document.location.href = select.value;
        });

        for (const option of select.options) {
            if (window.location.pathname === option.value) {
                select.value = option.value;
            }
        }
    }
}

window.addEventListener('load', () => {
    initMoreInfo();
    initSelectFields();
});
