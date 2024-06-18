const mix = require('laravel-mix');
mix.webpackConfig({
    stats: {
        children: true,
    },
});
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


mix.options({processCssUrls: false});

/**
 * Backend
 */

mix.copy('node_modules/dropzone/dist/min/dropzone.min.js', 'public/assets/backend/js/vendors/dropzone.min.js');
mix.copyDirectory('node_modules/bootstrap-icons/font/fonts', 'public/assets/backend/css/fonts');
mix.copy('node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js', 'public/assets/backend/js/vendors/ckeditor.js');
mix.copy('node_modules/formBuilder/dist/form-builder.min.js', 'public/assets/backend/js/vendors/form-builder.min.js');
mix.copy('node_modules/formBuilder/dist/form-render.min.js', 'public/assets/backend/js/vendors/form-render.min.js');
mix.copy('resources/assets/vendors/jquery.min.js', 'public/assets/backend/js/jquery.min.js');
mix.copy('resources/assets/backend/js/media.js', 'public/assets/backend/js/media.js');

mix.scripts([
    'node_modules/jquery-ui-dist/jquery-ui.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
    'node_modules/select2/dist/js/select2.min.js',
    'node_modules/cloneya/dist/jquery-cloneya.min.js',
    'node_modules/handlebars/dist/handlebars.min.js',
    'node_modules/codemirror/lib/codemirror.js',
], 'public/assets/backend/js/vendor.js');

mix.js('resources/assets/backend/js/datatables.js', 'public/assets/backend/js/datatables.js');
mix.js('resources/assets/backend/js/main.js', 'public/assets/backend/js/main.js');
mix.sass('resources/assets/backend/css/app.scss', 'public/assets/backend/css/');

/**
 * Frontend
 */
mix.copy('resources/assets/vendors/jquery.min.js', 'public/assets/themes/default/js/jquery.min.js');
mix.copy('node_modules/@fortawesome/fontawesome-free/webfonts/', 'public/assets/themes/default/webfonts');
mix.copy('node_modules/axios/dist/axios.min.js', 'public/assets/themes/default/js/axios.min.js');
mix.copy('resources/assets/frontend/default/js/cart.js', 'public/assets/themes/default/js/cart.js');
mix.copy('resources/assets/frontend/default/js/chat.js', 'public/assets/themes/default/js/chat.js');
mix.copy('node_modules/moment/min/moment.min.js', 'public/assets/themes/default/js/moment.min.js');
mix.copy('node_modules/vue/dist/vue.global.prod.js', 'public/assets/themes/default/js/vue.min.js');
mix.copy('resources/assets/frontend/img/facebook.png', 'public/assets/themes/default/img/facebook.png');
mix.copy('resources/assets/frontend/img/flags.png', 'public/assets/themes/default/img/flags.png');
mix.copy('resources/assets/frontend/img/thumb.png', 'public/assets/img/thumb.png');

mix.sass('resources/assets/frontend/default/sass/app.scss', 'public/assets/themes/default/css')
    .scripts([
        'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
        'node_modules/superfish/dist/js/superfish.min.js',
        'node_modules/slicknav/dist/jquery.slicknav.min.js',
        'node_modules/handlebars/dist/handlebars.js',
        'node_modules/jquery-bar-rating/dist/jquery.barrating.min.js',
        'resources/assets/frontend/default/js/main.js'
    ], 'public/assets/themes/default/js/app.js');

mix.styles([
    'node_modules/dropzone/dist/dropzone.css',
    'node_modules/@fortawesome/fontawesome-free/css/all.min.css',
    'resources/assets/vendors/flags.css',
    'public/assets/themes/default/css/app.css',
], 'public/assets/themes/default/css/app.css');

if (mix.inProduction()) {
    mix.version();
}
