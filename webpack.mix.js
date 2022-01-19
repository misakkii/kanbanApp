const mix = require('laravel-mix');

require('vuetifyjs-mix-extension')

mix.js('resources/js/app.js', 'public/js').vuetify('vuetify-loader').vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
}
