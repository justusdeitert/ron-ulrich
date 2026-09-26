/**
 * Design tokens shared by UnoCSS (uno.config.ts) and the block editor
 * configuration in theme.json, which is generated from this file by
 * `yarn gen:theme-json`. Change a value here and both stay in sync.
 */

/** Match the breakpoints the site used under Bootstrap 4 */
export const breakpoints = {
    sm: '576px',
    md: '768px',
    lg: '992px',
    xl: '1200px',
};

/** Editorial pairing: serif for headlines and article copy, sans for UI and meta.
 * The "Fallback" families are metric-matched system fonts from src/css/_fonts.scss. */
export const fontFamily = {
    serif: '"Newsreader", "Newsreader Fallback", Georgia, "Times New Roman", serif',
    sans: '"Source Sans 3", "Source Sans 3 Fallback", -apple-system, "Segoe UI", Helvetica, Arial, sans-serif',
};

export const colors = {
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
};

export const boxShadow = {
    sidebar: '2px 0 24px rgba(28, 27, 25, 0.18)',
};

/**
 * Width of the editor canvas, so a block is as wide as it will be on the
 * frontend: the `container` shortcut caps at 960px and carries 24px of
 * horizontal padding on each side from `md:px-6`.
 */
export const contentSize = '912px';

/**
 * Colours offered in the block editor. Deliberately a short, named subset
 * of the palette above: the intermediate ink shades are layout tools, not
 * choices an editor should have to make.
 */
export const editorPalette = [
    { slug: 'ink', name: 'Ink', color: colors.ink.DEFAULT },
    { slug: 'ink-muted', name: 'Ink muted', color: colors.ink[600] },
    { slug: 'accent', name: 'Accent', color: colors.accent.DEFAULT },
    { slug: 'paper', name: 'Paper', color: colors.paper.DEFAULT },
    { slug: 'paper-raised', name: 'Paper raised', color: colors.paper.raised },
    { slug: 'highlight', name: 'Highlight', color: colors.highlight },
];
