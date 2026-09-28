/**
 * Share buttons on single posts: the native share sheet where the browser has one,
 * otherwise copy the link to the clipboard.
 */
for (const button of document.querySelectorAll<HTMLButtonElement>('.share-button')) {
    const label = button.lastElementChild;
    const labelText = label?.textContent ?? '';
    let resetTimer: number | undefined;

    button.addEventListener('click', async () => {
        const { url = '', title = '', copied = '' } = button.dataset;

        if (navigator.share) {
            await navigator.share({ title, url }).catch(() => {});
            return;
        }

        await navigator.clipboard.writeText(url);

        if (label) {
            label.textContent = copied;
            clearTimeout(resetTimer);
            resetTimer = window.setTimeout(() => {
                label.textContent = labelText;
            }, 2000);
        }
    });
}
