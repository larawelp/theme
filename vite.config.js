import {defineConfig, splitVendorChunkPlugin} from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react'

import {NodeGlobalsPolyfillPlugin} from "@esbuild-plugins/node-globals-polyfill";
import inject from '@rollup/plugin-inject'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/react-app.tsx',
            ],
            valetTls: 'blog.test', // Your local Valet URL
            refresh: true,
        }),
        react(),
        splitVendorChunkPlugin(),
    ],
    define: {
        global: '({})'
    },
    build: {
        modulePreload: false,
        commonjsOptions: {
            transformMixedEsModules: true
        },
        rollupOptions: {
            plugins: [
                inject({Buffer: ['buffer', 'Buffer']})
            ]
        }
    },
    optimizeDeps: {
        esbuildOptions: {
            // Node.js global to browser globalThis
            define: {
                global: 'globalThis'
            },
            // Enable esbuild polyfill plugins
            plugins: [
                process.env.NODE_ENV === 'production' && NodeGlobalsPolyfillPlugin({
                    buffer: true
                })
            ].filter(Boolean)
        }
    },
});
