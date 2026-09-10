/**
 * Mobile off-canvas sidebar, replicating the simpler-sidebar plugin
 * (unpublished from npm). Behaviour:
 *  - sidebar sits fixed on the left, off-canvas by default
 *  - #toggle-sidebar opens it, #close-sidebar closes it
 *  - a dimmed overlay covers the page while open, fading in/out with the
 *    sidebar's transition; clicking it (or anything outside the sidebar,
 *    which it covers) closes it
 *  - scrolling the page past a short threshold closes it too
 */
const toggle = document.getElementById('toggle-sidebar');

if (toggle) {
    const body = document.body;
    /** Page offset when the sidebar was opened, so a stray pixel does not close it */
    let openedAt = 0;

    const setOpen = (open: boolean) => {
        if (open) {
            openedAt = window.scrollY;
        }

        body.classList.toggle('sidebar-open', open);
        toggle.setAttribute('aria-expanded', String(open));
    };

    const overlay = document.createElement('div');
    // pointer-events-none + opacity-0 while closed keeps it clickable only when visible
    overlay.className =
        'pointer-events-none fixed inset-0 z-[2990] bg-black/30 opacity-0 transition-opacity duration-300 [.sidebar-open_&]:pointer-events-auto [.sidebar-open_&]:opacity-100';
    overlay.addEventListener('click', () => setOpen(false));
    body.appendChild(overlay);

    toggle.addEventListener('click', () => {
        setOpen(!body.classList.contains('sidebar-open'));
    });

    document.getElementById('close-sidebar')?.addEventListener('click', () => setOpen(false));

    window.addEventListener(
        'scroll',
        () => {
            if (body.classList.contains('sidebar-open') && Math.abs(window.scrollY - openedAt) > 40) {
                setOpen(false);
            }
        },
        { passive: true },
    );
}
