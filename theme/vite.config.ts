import path from 'node:path';
import UnoCSS from 'unocss/vite';
import type { UserConfig } from 'vite';
import { defineConfig } from 'vite';
import fullReload from 'vite-plugin-full-reload';
import unoConfig from './uno.config.ts';

export default defineConfig(async ({ mode }) => {
    const isProduction = mode === 'production';
    const assetsPath = path.resolve(import.meta.dirname, 'assets');

    if (!isProduction) {
        const fs = await import('node:fs');

        // Delete the assets folder in development mode so the theme
        // falls back to the Vite dev server (see theme/functions.php)
        if (fs.existsSync(assetsPath)) {
            fs.rmSync(assetsPath, { recursive: true, force: true });
            console.log('Assets folder deleted during development mode.');
        }
    }

    return {
        root: 'src',
        publicDir: path.resolve(import.meta.dirname, 'public'),
        base: isProduction ? '/wp-content/themes/ron-ulrich-theme/assets/' : '/',
        build: {
            outDir: path.resolve(import.meta.dirname, 'assets'),
            emptyOutDir: true,
            sourcemap: false,
            // Vite 8 (rolldown) default minifier is oxc; 'esbuild' requires esbuild as a separate dependency
            minify: true,
            manifest: true,
            rollupOptions: {
                input: {
                    main: 'src/ts/main.ts',
                },
                output: {
                    entryFileNames: 'js/[name]-[hash].js',
                    chunkFileNames: 'js/[name]-[hash].js',
                    assetFileNames: (assetInfo) => {
                        const name = assetInfo.name ?? '';

                        if (name.endsWith('.css')) {
                            return 'css/[name]-[hash][extname]';
                        }

                        if (['.ttf', '.woff', '.woff2'].some((ext) => name.endsWith(ext))) {
                            return 'fonts/[name]-[hash][extname]';
                        }

                        if (['.png', '.jpg', '.jpeg', '.svg', '.ico'].some((ext) => name.endsWith(ext))) {
                            return 'images/[name]-[hash][extname]';
                        }

                        return '[name]-[hash][extname]';
                    },
                },
            },
        },
        css: {
            devSourcemap: true,
        },
        plugins: [
            UnoCSS(unoConfig),
            // PHP templates are outside the Vite module graph (root is
            // src/), so reload the full page when one changes.
            fullReload(['**/*.php'], { root: import.meta.dirname }),
        ],
        server: {
            host: '0.0.0.0',
            port: 5173,
            strictPort: true,
            cors: true,
            // The container publishes 5174 -> 5173 (5173 is used by another project).
            origin: `http://${process.env.HOST_LAN_IP || 'localhost'}:5174`,
            hmr: {
                // The WebSocket server stays on 5173 inside the container;
                // the browser must dial the published host port 5174.
                clientPort: 5174,
            },
        },
        resolve: {
            alias: {
                '@': path.resolve(import.meta.dirname, 'src'),
            },
        },
    } satisfies UserConfig;
});
