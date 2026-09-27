/**
 * Year archive <select>: navigate on change and pre-select the
 * option matching the current archive URL.
 */
for (const select of document.querySelectorAll<HTMLSelectElement>('select[name="archive-year"]')) {
    select.addEventListener('change', () => {
        window.location.href = select.value;
    });

    const current = [...select.options].find(
        (option) => new URL(option.value, window.location.href).pathname === window.location.pathname,
    );

    if (current) {
        select.value = current.value;
    }
}
