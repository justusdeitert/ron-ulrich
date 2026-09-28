/**
 * "Back" links on single posts: navigate back in history when the visitor
 * came from this site, otherwise fall through to the href (blog overview).
 */
for (const link of document.querySelectorAll<HTMLAnchorElement>('.back-link')) {
    link.addEventListener('click', (event) => {
        if (document.referrer.startsWith(window.location.origin) && window.history.length > 1) {
            event.preventDefault();
            window.history.back();
        }
    });
}
