/**
 * Fade teasers and content images in as they scroll into view. Only elements
 * below the fold at load are hidden, so nothing on the first screen flickers.
 * Elements entering together are staggered.
 */
const elements = document.querySelectorAll<HTMLElement>('[data-reveal], main .wp-block-image');

if (elements.length && window.matchMedia('(prefers-reduced-motion: no-preference)').matches) {
    const hidden = ['opacity-0', 'translate-y-6'];

    const observer = new IntersectionObserver(
        (entries) => {
            const entering = entries.filter((entry) => entry.isIntersecting);

            entering.forEach((entry, index) => {
                const element = entry.target as HTMLElement;
                element.style.transitionDelay = `${index * 120}ms`;
                element.classList.remove(...hidden);
                observer.unobserve(element);
            });
        },
        { rootMargin: '0px 0px -10% 0px' },
    );

    for (const element of elements) {
        if (element.getBoundingClientRect().top > window.innerHeight) {
            element.classList.add(
                ...hidden,
                'transition-[opacity,transform]',
                'duration-700',
                'ease-[cubic-bezier(0.2,0.7,0.2,1)]',
            );
            observer.observe(element);
        }
    }
}
