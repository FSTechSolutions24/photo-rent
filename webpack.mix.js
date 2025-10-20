const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .vue() // Make sure you have Vue installed
   .sass('resources/sass/app.scss', 'public/css');
