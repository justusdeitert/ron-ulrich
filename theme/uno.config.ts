import { defineConfig, presetWind3 } from 'unocss';

export default defineConfig({
    presets: [presetWind3()],
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
    },
    shortcuts: {
        // Bootstrap's .container with the site's max-widths (md 720px, lg 960px)
        container: 'w-full px-4 mx-auto md:max-w-[720px] lg:max-w-[960px]',
        // Tag pill on posts
        tag: 'mr-2.5 inline-block border-2 border-solid border-black px-1.5 py-0.5 text-base hover:bg-[#DBDBDB]',
        // Warning box on 404 and empty search pages (Bootstrap's .alert-warning)
        'alert-warning': 'relative mb-4 rounded border border-[#ffeeba] bg-[#fff3cd] px-5 py-3 text-[#856404]',
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
