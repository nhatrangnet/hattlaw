const mix = require('laravel-mix');
let webpack = require('webpack');

mix.webpackConfig({
    plugins: [
        new webpack.IgnorePlugin(/^codemirror$/)
    ]
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.disableNotifications()
   .js('resources/js/app.js', 'public/js')
   .js('resources/js/frontend.js', 'public/js')
   .js('resources/js/datatable.js', 'public/js')
   .js('resources/js/backend.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/frontend.scss', 'public/css')
   .sass('resources/sass/backend.scss', 'public/css');