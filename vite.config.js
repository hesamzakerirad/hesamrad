import jigsaw from '@tighten/jigsaw-vite-plugin';
import { defineConfig } from 'vite';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    base: '/assets/build',
    build: {
        assetsDir: '',
        rollupOptions: {
            output: {
                assetFileNames: '[name]-[hash][extname]',  // No 'build/' prefix
                chunkFileNames: '[name]-[hash].js',
                entryFileNames: '[name]-[hash].js',
            }
        }
    },
    plugins: [
        jigsaw({
            input: [
                'source/_assets/js/main.js',
                'source/_assets/css/main.css',
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'source/_assets/images',
                    dest: '',  // Empty = root of base path
                    rename: {stripBase: 2}
                },
            ]
        })
    ],
});