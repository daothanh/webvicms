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

mix.setPublicPath('../../public')
  .js('resources/js/main.js', '../../public/themes/admin/js')
  .js('resources/js/media.js', '../../public/themes/admin/js')
  .js('resources/js/general-grid.js', '../../public/themes/admin/js')
  .js('resources/js/init-dropzone.js', '../../public/themes/admin/js')
  .sass('resources/scss/main.scss', '../../public/themes/admin/css/')
  .sass('resources/scss/pages/login.scss', '../../public/themes/admin/css/pages/');
