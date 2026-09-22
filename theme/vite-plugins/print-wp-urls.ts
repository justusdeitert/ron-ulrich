import type { Plugin } from 'vite';

interface PrintWpUrlsOptions {
    port?: number;
    hostLanIp?: string;
}

/**
 * Prints the WordPress URLs above Vite's default URL output so developers
 * know where to open the site (and not just the Vite asset server).
 */
export function printWpUrls(options: PrintWpUrlsOptions = {}): Plugin {
    const port = options.port ?? 8090;
    const hostLanIp = options.hostLanIp ?? process.env.HOST_LAN_IP;

    return {
        name: 'ron-ulrich:print-wp-urls',
        apply: 'serve',
        configureServer(server) {
            const originalPrintUrls = server.printUrls.bind(server);
            server.printUrls = () => {
                const c = {
                    blue: '\x1b[34m',
                    cyan: '\x1b[36m',
                    reset: '\x1b[0m',
                };
                const arrow = `  ${c.blue}➜${c.reset}`;
                const lines = [`${arrow}  WP Local:   ${c.cyan}http://localhost:${port}/${c.reset}`];
                if (hostLanIp) {
                    lines.push(`${arrow}  WP Network: ${c.cyan}http://${hostLanIp}:${port}/${c.reset}`);
                }
                console.log(`\n${lines.join('\n')}`);

                originalPrintUrls();
            };
        },
    };
}
