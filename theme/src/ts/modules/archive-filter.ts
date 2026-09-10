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

/**
 * Category chips: CSS cannot spread wrapped flex items evenly across rows,
 * so once the row breaks we insert zero-height full-width spacers to give
 * every row the same number of chips.
 */
function balanceChipRows(row: HTMLElement): void {
    for (const spacer of row.querySelectorAll('[data-row-break]')) {
        spacer.remove();
    }

    const chips = [...row.children] as HTMLElement[];

    if (chips.length < 3) {
        return;
    }

    let rows = 1;
    let rowTop = chips[0].offsetTop;

    for (const chip of chips) {
        if (chip.offsetTop > rowTop) {
            rows++;
            rowTop = chip.offsetTop;
        }
    }

    if (rows < 2) {
        return;
    }

    // Never exceeds what already fit on the widest row, so the chips cannot overflow.
    const perRow = Math.ceil(chips.length / rows);

    for (let i = perRow; i < chips.length; i += perRow) {
        const spacer = document.createElement('span');
        spacer.dataset.rowBreak = '';
        spacer.className = 'h-0 w-full';
        row.insertBefore(spacer, chips[i]);
    }
}

const chipRow = document.querySelector<HTMLElement>('[data-chip-row]');

if (chipRow) {
    let lastWidth = 0;

    new ResizeObserver(() => {
        if (chipRow.clientWidth === lastWidth) {
            return;
        }

        lastWidth = chipRow.clientWidth;
        balanceChipRows(chipRow);
    }).observe(chipRow);

    // Chip widths change once the webfont swaps in
    document.fonts.ready.then(() => balanceChipRows(chipRow));
}
