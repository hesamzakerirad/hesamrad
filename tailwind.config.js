const defaults = require('tailwindcss/defaultTheme');

module.exports = {
    content: require('fast-glob').sync([
        'source/**/*.html',
        'source/**/*.md',
        'source/**/*.js',
        'source/**/*.php',
        'source/**/*.vue',
    ]),
    options: {
        safelist: [
            /language/,
            /hljs/,
            /mce/,
        ],
    },
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#FFF0E6',
                    100: '#FFD9BF',
                    200: '#FFB380',
                    300: '#FF8C40',
                    400: '#FF6B1A',
                    500: '#FF4D00',
                    600: '#E64500',
                    700: '#CC3D00',
                    800: '#B33600',
                    900: '#992E00',
                },
            },
        },
        fontSize: {
            xs: '.8rem',
            sm: '.925rem',
            base: '1rem',
            lg: '1.125rem',
            xl: '1.25rem',
            '2xl': '1.5rem',
            '3xl': '1.75rem',
            '4xl': '2.125rem',
            '5xl': '2.625rem',
            '6xl': '10rem',
        },
    },
};
