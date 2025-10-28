/**
 * Mobile off-canvas sidebar, replicating the simpler-sidebar plugin
 * (unpublished from npm). Behaviour:
 *  - sidebar sits fixed on the right, off-canvas by default
 *  - #toggle-sidebar opens/closes it
 *  - clicking anywhere outside closes it
 *  - a dimmed overlay covers the page while open
 */
import $ from 'jquery';

jQuery(document).ready(() => {
    const $body = $('body');
    const $sidebar = $('#sidebar');

    $('<div id="sidebar-overlay" />')
        .appendTo('body')
        .on('click', () => {
            $body.removeClass('sidebar-open');
        });

    $('#toggle-sidebar').on('click', (event) => {
        event.stopPropagation();
        $body.toggleClass('sidebar-open');
    });

    $(document).on('click', (event) => {
        if ($body.hasClass('sidebar-open') && !$(event.target).closest('#sidebar, #toggle-sidebar').length) {
            $body.removeClass('sidebar-open');
        }
    });
});
