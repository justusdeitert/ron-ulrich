/**
 * "More info" toggle + archive <select> sync, ported from the old
 * jQuery snippets in resources/assets/scripts/main.js.
 */
import $ from 'jquery';

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
        $('body').addClass('more-info');
    }

    $('.more-info .info-left').on('click', () => {
        if ($('body').hasClass('more-info')) {
            $('body').removeClass('more-info');
            deleteCookie('more-info');
        } else {
            $('body').addClass('more-info');
            setCookie('more-info', 'true');
        }
    });
}

function initSelectFields(): void {
    $('.more-info select').each(function () {
        $(this)
            .children()
            .each(function () {
                if (window.location.pathname === (this as HTMLOptionElement).value) {
                    $(this)
                        .parent()
                        .val((this as HTMLOptionElement).value);
                }
            });
    });
}

jQuery(window).on('load', () => {
    initMoreInfo();
    initSelectFields();
});
