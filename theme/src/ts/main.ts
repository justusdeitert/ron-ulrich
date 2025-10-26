import 'jquery';
import 'bootstrap';

import '../scss/main.scss';

import './modules/sidebar';
import './modules/more-info';

declare const jQuery: JQueryStatic;

jQuery(window).on('load', () => {
    const brandImageHeight = jQuery('.brand-image').height();
    jQuery('.brand-image').width(brandImageHeight);
});
