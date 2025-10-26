/**
 * Mobile off-canvas sidebar.
 *
 * Replaces the `simpler-sidebar` jQuery plugin (unpublished from npm)
 * with a small equivalent: right-aligned sidebar, toggled via
 * #toggle-sidebar, closed by clicking anywhere outside of it.
 */
import $ from 'jquery';

jQuery(document).ready(() => {
    const $body = $('body');

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
