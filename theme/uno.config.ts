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
            // Editorial pairing: serif for headlines and article copy, sans for UI and meta
            serif: '"Newsreader", Georgia, "Times New Roman", serif',
            sans: '"Source Sans 3", -apple-system, "Segoe UI", Helvetica, Arial, sans-serif',
        },
        colors: {
            // Warm off-white page ground, white for raised surfaces (sidebar, inputs)
            paper: {
                DEFAULT: '#fbfaf8',
                raised: '#ffffff',
                sunken: '#f2f0ec',
            },
            // Warm near-black for headlines and rules, lightening to muted gray for meta text
            ink: {
                DEFAULT: '#1c1b19',
                900: '#1c1b19',
                800: '#312f2c',
                700: '#4a4744',
                600: '#6b6762',
                500: '#8a8681',
                400: '#a8a49e',
            },
            // Restrained editorial red, used sparingly for links and active states
            accent: {
                DEFAULT: '#9c3025',
                soft: '#f6ece9',
            },
            // Hairlines and input strokes
            line: {
                DEFAULT: 'rgba(28, 27, 25, 0.14)',
                strong: 'rgba(28, 27, 25, 0.32)',
            },
            // Filled state for tags and the current page number
            highlight: '#e8e4dc',
            warning: {
                DEFAULT: '#fdf6e3',
                border: '#efdfae',
                text: '#7a5c17',
            },
            danger: '#a3231b',
        },
        boxShadow: {
            sidebar: '2px 0 24px rgba(28, 27, 25, 0.18)',
        },
    },
    shortcuts: {
        // Bootstrap's .container with the site's max-widths (md 720px, lg 960px)
        container: 'w-full px-5 mx-auto md:px-6 md:max-w-[720px] lg:max-w-[960px]',
        // Small caps label for meta lines, section labels and UI links
        kicker: 'font-sans text-xs font-semibold tracking-[0.12em] uppercase text-ink-600',
        // Category filter buttons above the post list, in both states
        chip: 'inline-block whitespace-nowrap border border-solid border-line-strong px-3 py-1 font-sans text-xs font-semibold tracking-[0.08em] uppercase text-ink-600 transition-colors hover:border-ink-900 hover:bg-highlight hover:text-ink-900',
        'chip-active':
            'inline-block whitespace-nowrap border border-solid border-ink-900 bg-ink-900 px-3 py-1 font-sans text-xs font-semibold tracking-[0.08em] uppercase text-paper-raised hover:text-paper-raised',
        // Tag pill on posts. Not named `tag`: WordPress puts that class on <body> for tag archives.
        'post-tag':
            'inline-block border border-solid border-line-strong px-2 py-0.5 font-sans text-xs font-semibold tracking-[0.08em] uppercase text-ink-700 transition-colors hover:border-ink-900 hover:bg-highlight hover:text-ink-900',
        // Warning box on 404 and empty search pages (Bootstrap's .alert-warning)
        'alert-warning':
            'relative mb-4 rounded border border-solid border-warning-border bg-warning px-5 py-3 font-sans text-warning-text',
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
