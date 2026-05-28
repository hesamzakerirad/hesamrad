import jigsaw from '@tighten/jigsaw-vite-plugin';
import { defineConfig } from 'vite';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        jigsaw({
            input: [
                'source/_assets/js/main.js',
                'source/_assets/css/main.css',
                'source/_assets/css/fontawesome/fontawesome.min.css',
                'source/_assets/css/fontawesome/solid.min.css',
                'source/_assets/css/highlight/github.min.css',
                'source/_assets/css/highlight/github-dark.min.css'
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'source/_assets',  // Copy contents of _assets folder
                    dest: '.',              // into assets/build folder
                    rename: {stripBase: 2}  // strip source/_assets from path when naming
                },
            ]
        })
    ],
});