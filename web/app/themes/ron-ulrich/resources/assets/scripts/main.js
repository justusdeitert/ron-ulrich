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

jQuery(window).load(function () {
    let brandImageHeight = $('.brand-image').height();
    $('.brand-image').width(brandImageHeight);

    // $('.more-info').toggle(function() {
    //
    // });
    let toggleMoreInfo = function() {
        $('.more-info').toggleClass( "active" );
        $('.article-overview .post-info').toggleClass( "active" );

        if($('.more-info').hasClass('active')) {
            window.location.hash = '#more-info';
        } else {
            window.location.hash = '';
        }
    };

    if(window.location.hash === '#more-info') {
        // console.log('lol');
        toggleMoreInfo()
    }

    $('.more-info .info-left').click(function() {
        toggleMoreInfo()
    });
});
