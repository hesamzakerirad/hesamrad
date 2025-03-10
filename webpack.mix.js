const mix = require('laravel-mix');
require('laravel-mix-jigsaw');

mix.disableSuccessNotifications();
mix.setPublicPath('source/assets/build');

mix.jigsaw()
    .js('source/_assets/js/main.js', 'js')
    .css('source/_assets/css/main.css', 'css/main.css', [
        require('postcss-import'),
        require('tailwindcss/nesting'),
        require('tailwindcss'),
    ])
    .copyDirectory('source/_assets/fonts', 'source/assets/build/fonts')
    .copyDirectory('source/_assets/svg', 'source/assets/build/svg')
    .copyDirectory('source/_assets/images', 'source/assets/build/images')
    .options({ processCssUrls: false })
    .browserSync({
        server: 'build_local',
        files: ['build_local/**'],
    })
    .sourceMaps()
    .version();
