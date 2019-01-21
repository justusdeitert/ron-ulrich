// Import external dependencies
import 'jquery';

// Import everything from autoload
import './autoload/**/*';

// Import local dependencies
import Router from './util/Router';
import common from './routes/common';
import home from './routes/home';
import aboutUs from './routes/about';

// Populate Router instance with DOM routes
const routes = new Router({
    // All pages
    common,
    // Home page
    home,
    // About Us page, note the change from about-us to aboutUs.
    aboutUs,
});

// Load Events
jQuery(document).ready(
    () => routes.loadEvents()
);

jQuery(document).ready(function() {
    // console.log($('.brand-image').height());
    // let brandImageWidth = $('.brand-image').innerWidth();
    // $('.brand-image').innerHeight(brandImageWidth);
    // console.log($('.brand-image').innerWidth());
});

const moreInfo = () => {
    if(getCookie('more-info')) {
        $('body').addClass('more-info');
    }

    $('.more-info .info-left').click(() => {
        toggleMoreInfo();
    });

    let toggleMoreInfo = function() {
        if($('body').hasClass('more-info')) {
            $('body').removeClass('more-info');
            deleteCookie('more-info');
        } else {
            $('body').addClass('more-info');
            setCookie('more-info', true);
        }
    };
};

const setSelectfields = () => {
    $('.more-info select').each(function() {
        let options = $(this).children();

        $(options).each(function() {
            // console.log(this.value);
            if(window.location.pathname == this.value) {
                $(this).parent().val(this.value);
            }
        });
    });
};

jQuery(window).load(function () {
    let brandImageHeight = $('.brand-image').height();
    $('.brand-image').width(brandImageHeight);

    moreInfo();
    setSelectfields()
});

// ------------------------->
// Base Functions Set Cookies
// https://www.w3schools.com/js/js_cookies.asp

function deleteCookie(name) {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}

function setCookie(name, value, exdays) {
    if (exdays === undefined) {
        exdays = 12;
    }
    let d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = 'expires='+ d.toUTCString();
    document.cookie = name + '=' + value + ';' + expires + ';path=/';
}

function getCookie(name) {
    name = name + '=';
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];

        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }

        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return '';
}
