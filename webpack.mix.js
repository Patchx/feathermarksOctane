const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

 // --------
 // - SASS -
 // --------

 mix.sass('resources/sass/app.scss', 'public/wp/css').version();

 // ------
 // - JS -
 // ------

 mix.js('resources/js/app.js', 'public/wp/js').version();

 mix.js('resources/js/pages/home-page.js', 'public/wp/js')
    .version();
