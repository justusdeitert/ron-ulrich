/**
 * Mobile off-canvas sidebar, replicating the simpler-sidebar plugin
 * (unpublished from npm). Behaviour:
 *  - sidebar sits fixed on the left, off-canvas by default
 *  - #toggle-sidebar opens/closes it
 *  - a dimmed overlay covers the page while open, fading in/out with the
 *    sidebar's transition; clicking it (or anything outside the sidebar,
 *    which it covers) closes it
 */
const toggle = document.getElementById('toggle-sidebar');

if (toggle) {
    const body = document.body;

    const overlay = document.createElement('div');
    // pointer-events-none + opacity-0 while closed keeps it clickable only when visible
    overlay.className =
        'pointer-events-none fixed inset-0 z-[2990] bg-black/30 opacity-0 transition-opacity duration-300 [.sidebar-open_&]:pointer-events-auto [.sidebar-open_&]:opacity-100';
    overlay.addEventListener('click', () => {
        body.classList.remove('sidebar-open');
    });
    body.appendChild(overlay);

    toggle.addEventListener('click', () => {
        body.classList.toggle('sidebar-open');
    });
}
