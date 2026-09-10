import { defineConfig, presetWind3, transformerDirectives } from 'unocss';
import { boxShadow, breakpoints, colors, fontFamily } from './tokens.ts';

export default defineConfig({
    presets: [presetWind3()],
    transformers: [transformerDirectives()],
    theme: {
        breakpoints,
        fontFamily,
        colors,
        boxShadow,
    },
    shortcuts: {
        // Bootstrap's .container with the site's max-widths (md 720px, lg 960px)
        container: 'w-full px-5 mx-auto md:px-6 md:max-w-[720px] lg:max-w-[960px]',
        // Small caps label for meta lines, section labels and UI links
        kicker: 'font-sans text-sm font-semibold tracking-[0.12em] uppercase text-ink-600',
        // Tag links on posts and the category filter buttons above the post list.
        // Not named `tag`: WordPress puts that class on <body> for tag archives.
        chip: 'inline-block whitespace-nowrap border border-solid border-line-strong px-3.5 py-1.5 font-sans text-sm font-semibold tracking-[0.08em] uppercase text-ink-600 transition-colors hover:border-ink-900 hover:bg-highlight hover:text-ink-900',
        'chip-active':
            'inline-block whitespace-nowrap border border-solid border-ink-900 bg-ink-900 px-3.5 py-1.5 font-sans text-sm font-semibold tracking-[0.08em] uppercase text-paper-raised hover:text-paper-raised',
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
