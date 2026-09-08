/**
 * Mobile off-canvas sidebar, replicating the simpler-sidebar plugin
 * (unpublished from npm). Behaviour:
 *  - sidebar sits fixed on the left, off-canvas by default
 *  - #toggle-sidebar opens/closes it
 *  - clicking anywhere outside closes it
 *  - a dimmed overlay covers the page while open
 */
const toggle = document.getElementById('toggle-sidebar');

if (toggle) {
    const body = document.body;

    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 z-[2990] hidden bg-black/30 [.sidebar-open_&]:block';
    overlay.addEventListener('click', () => {
        body.classList.remove('sidebar-open');
    });
    body.appendChild(overlay);

    toggle.addEventListener('click', (event) => {
        event.stopPropagation();
        body.classList.toggle('sidebar-open');
    });

    document.addEventListener('click', (event) => {
        if (
            body.classList.contains('sidebar-open') &&
            !(event.target as Element).closest('#sidebar, #toggle-sidebar')
        ) {
            body.classList.remove('sidebar-open');
        }
    });
}
