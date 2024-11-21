const mix = require('laravel-mix');

mix.js('resources/js/charts/user-statistics.js', 'public/js/charts')
   .version();