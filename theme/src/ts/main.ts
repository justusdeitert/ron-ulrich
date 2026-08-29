import 'virtual:uno.css';

import '../scss/main.scss';

import './modules/sidebar';
import './modules/more-info';

window.addEventListener('load', () => {
    for (const brandImage of document.querySelectorAll<HTMLElement>('.brand-image')) {
        brandImage.style.width = `${brandImage.offsetHeight}px`;
    }
});
