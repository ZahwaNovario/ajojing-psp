import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');

    return {
        server: {
            host: env.VITE_HOST || '127.0.0.1',
            port: parseInt(env.VITE_PORT) || 5173,
            strictPort: true,
            cors: true,
            hmr: {
                host: env.VITE_HMR_HOST || 'localhost',
            },
        },
        plugins: [
            laravel([
                'resources/css/app.css',
                'resources/js/app.js',
            ]),
        ],
    };
});
