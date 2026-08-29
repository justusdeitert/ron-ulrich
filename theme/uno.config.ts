import { defineConfig, presetWind3 } from 'unocss';

export default defineConfig({
    presets: [presetWind3()],
    // The site has its own .container (with Bootstrap's max-widths) in SCSS;
    // suppress the preset's built-in container utility
    blocklist: ['container'],
    theme: {
        // Match the breakpoints the site used under Bootstrap 4
        breakpoints: {
            sm: '576px',
            md: '768px',
            lg: '992px',
            xl: '1200px',
        },
    },
    shortcuts: {
        // Replacements for the Bootstrap 4 grid classes used in templates
        row: 'flex flex-wrap -mx-[15px]',
        col: 'relative w-full px-[15px] grow basis-0 max-w-full',
        'col-12': 'relative w-full px-[15px] grow-0 shrink-0 basis-full max-w-full',
        'col-sm-4': 'sm:(grow-0 shrink-0 basis-1/3 max-w-1/3)',
    },
    content: {
        pipeline: {
            include: [/\.(php|ts|scss|html)($|\?)/],
        },
        // Templates live outside the Vite root (src/), so scan them explicitly.
        // Globs are resolved against the Vite root, hence the ../ prefixes.
        filesystem: ['../*.php', '../template-parts/**/*.php', '../inc/**/*.php'],
    },
});
