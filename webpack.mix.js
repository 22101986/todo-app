const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/css/app.scss', 'public/css')
   .vue() // Si vous utilisez Vue.js
   .postCss('resources/css/app.css', 'public/css', [
       require('tailwindcss'), // Si vous utilisez Tailwind
   ]);