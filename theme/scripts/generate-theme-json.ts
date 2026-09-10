/**
 * Generates theme.json from the design tokens in tokens.ts, so the block
 * editor offers exactly the palette and content width the theme styles.
 *
 * Run with `yarn gen:theme-json`; `yarn build` does it automatically.
 * The output is committed, because WordPress reads it at runtime.
 */

import { writeFileSync } from 'node:fs';
import path from 'node:path';
import { contentSize, editorPalette } from '../tokens.ts';

const themeJson = {
    $schema: 'https://schemas.wp.org/trunk/theme.json',
    version: 3,
    settings: {
        // The theme styles content with UnoCSS and SCSS, not with global styles,
        // so the editor should not offer the appearance tools that write inline CSS.
        appearanceTools: false,
        layout: {
            // Sets the width of the editor canvas. `alignwide` is not styled on the
            // frontend, so wide is deliberately the same width as regular content.
            contentSize,
            wideSize: contentSize,
        },
        color: {
            palette: editorPalette,
            defaultPalette: false,
            custom: false,
            text: true,
            background: false,
            link: false,
            gradients: [],
            defaultGradients: false,
            customGradient: false,
            duotone: [],
            defaultDuotone: false,
            customDuotone: false,
        },
        typography: {
            // One reading size, set by the theme stylesheet. No size picker.
            fontSizes: [],
            defaultFontSizes: false,
            customFontSize: false,
            fluid: false,
            dropCap: false,
            fontStyle: false,
            fontWeight: false,
            letterSpacing: false,
            lineHeight: false,
            textDecoration: false,
            textTransform: false,
            writingMode: false,
        },
        spacing: {
            margin: false,
            padding: false,
            defaultSpacingSizes: false,
            units: ['px', 'rem'],
        },
        border: {
            color: false,
            radius: false,
            style: false,
            width: false,
        },
        dimensions: {
            aspectRatio: false,
            minHeight: false,
        },
        position: {
            sticky: false,
        },
        shadow: {
            presets: [],
            defaultPresets: false,
        },
    },
};

const target = path.resolve(import.meta.dirname, '../theme.json');

writeFileSync(target, `${JSON.stringify(themeJson, null, 4)}\n`);

console.log(`Wrote ${path.relative(process.cwd(), target)}`);
