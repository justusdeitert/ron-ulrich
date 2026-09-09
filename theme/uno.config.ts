import { defineConfig, presetWind3, transformerDirectives } from 'unocss';

export default defineConfig({
    presets: [presetWind3()],
    transformers: [transformerDirectives()],
    theme: {
        // Match the breakpoints the site used under Bootstrap 4
        breakpoints: {
            sm: '576px',
            md: '768px',
            lg: '992px',
            xl: '1200px',
        },
        fontFamily: {
            sans: 'Helvetica, Arial, sans-serif',
        },
        colors: {
            // Near-black for headings, links and rules, lightening to muted gray for meta text
            ink: {
                DEFAULT: '#212529',
                950: '#000000',
                800: '#3e3e3e',
                700: '#495057',
                600: '#6c757d',
            },
            // Hairlines and input strokes
            line: {
                DEFAULT: 'rgba(204, 204, 204, 0.6)',
                strong: '#ced4da',
            },
            // Filled state for tags and the current page number
            highlight: '#dbdbdb',
            warning: {
                DEFAULT: '#fff3cd',
                border: '#ffeeba',
                text: '#856404',
            },
            danger: 'rgba(255, 0, 0, 0.6)',
        },
        boxShadow: {
            sidebar: '2px 0 8px rgba(0, 0, 0, 0.15)',
        },
    },
    shortcuts: {
        // Bootstrap's .container with the site's max-widths (md 720px, lg 960px)
        container: 'w-full px-4 mx-auto md:max-w-[720px] lg:max-w-[960px]',
        // Tag pill on posts
        tag: 'mr-2.5 inline-block border-2 border-solid border-ink-950 px-1.5 py-0.5 text-base hover:bg-highlight',
        // Warning box on 404 and empty search pages (Bootstrap's .alert-warning)
        'alert-warning': 'relative mb-4 rounded border border-warning-border bg-warning px-5 py-3 text-warning-text',
    },
    content: {
        pipeline: {
            include: [/\.(php|ts|html)($|\?)/],
        },
        // Templates live outside the Vite root (src/), so scan them explicitly.
        // Globs are resolved against the Vite root, hence the ../ prefixes.
        filesystem: ['../*.php', '../template-parts/**/*.php', '../inc/**/*.php'],
    },
});
