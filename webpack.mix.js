const mix = require('laravel-mix');

mix
  .js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css', {
    implementation: require('sass')
  });

// PWA de reservas de alumnos
mix.js('resources/js/pwa/main.js', 'public/pwa/js/app.js');

if (mix.inProduction()) {
  mix.version();
}
