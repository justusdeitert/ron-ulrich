/**
 * "More info" toggle + archive <select> sync, ported from the old
 * jQuery snippets in resources/assets/scripts/main.js.
 */
function deleteCookie(name: string): void {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
}

function setCookie(name: string, value: string, exdays = 12): void {
    const d = new Date();
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
    document.cookie = `${name}=${value};expires=${d.toUTCString()};path=/`;
}

function getCookie(name: string): string {
    const key = `${name}=`;
    const decodedCookie = decodeURIComponent(document.cookie);
    const parts = decodedCookie.split(';');

    for (let part of parts) {
        while (part.charAt(0) === ' ') {
            part = part.substring(1);
        }

        if (part.indexOf(key) === 0) {
            return part.substring(key.length);
        }
    }

    return '';
}

function initMoreInfo(): void {
    if (getCookie('more-info')) {
        document.body.classList.add('more-info');
    }

    document.querySelector('.more-info .info-left')?.addEventListener('click', () => {
        if (document.body.classList.contains('more-info')) {
            document.body.classList.remove('more-info');
            deleteCookie('more-info');
        } else {
            document.body.classList.add('more-info');
            setCookie('more-info', 'true');
        }
    });
}

function initSelectFields(): void {
    for (const select of document.querySelectorAll<HTMLSelectElement>('.more-info select')) {
        for (const option of Array.from(select.options)) {
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
