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
                    src: 'source/_assets/images/*',
                    dest: 'images',
                    rename: {
                        stripBase: true
                    }
                },
                {
                    src: 'source/_assets/fonts/*',
                    dest: 'fonts',
                    rename: {
                        stripBase: true
                    }
                },
                {
                    src: 'source/_assets/svg/*',
                    dest: 'svg',
                    rename: {
                        stripBase: true
                    }
                },
                {
                    src: 'source/_assets/css/highlight/*',
                    dest: 'css/highlight',
                    rename: {
                        stripBase: true
                    }
                },
                {
                    src: 'source/_assets/css/fontawesome/*',
                    dest: 'css/fontawesome',
                    rename: {
                        stripBase: true
                    }
                },
            ]
        })
    ],
});