const mix = require('laravel-mix');

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

// mix.js('resources/assets/js/app.js', 'public/js').vue({ version: 2 });
// var bootstrap_sass = './node_modules/bootstrap-sass/';
// mix.copy(bootstrap_sass + 'assets/fonts/bootstrap', 'public/fonts/vendor/bootstrap-sass/bootstrap');
mix.js('resources/assets/js/app.js', 'public/js');
mix.sass('resources/assets/sass/app.scss', 'public/css').options({
  processCssUrls: false
});
mix.postCss('public/css/app.css', 'public/css');
