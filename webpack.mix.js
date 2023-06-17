const {
    mix
} = require('laravel-mix');
const MonocoEditorPlugin = require('monaco-editor-webpack-plugin')

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

mix.webpackConfig({
        plugins: [
            new MonocoEditorPlugin()
        ],
        resolve: {
            symlinks: false,
            alias: {
                // This is so we can override some of Laravel Horizon's javascript with our own so we can embed in our UI
                Horizon: path.resolve(__dirname, 'vendor/laravel/horizon/resources/assets/js/')
            }
        }
    }).js('resources/js/app-layout.js', 'public_html/js')
    .js('resources/js/processes/modeler/index.js', 'public_html/js/processes/modeler')
    .js('resources/js/processes/modeler/initialLoad.js', 'public_html/js/processes/modeler')
    .js('resources/js/admin/users/index.js', 'public_html/js/admin/users')
    .js('resources/js/admin/groups/index.js', 'public_html/js/admin/groups')
    .js('resources/js/admin/queues/index.js', 'public_html/js/admin/queues')

    .js('resources/js/processes/index.js', 'public_html/js/processes')
    .js('resources/js/processes/categories/index.js', 'public_html/js/processes/categories')
    .js('resources/js/processes/scripts/index.js', 'public_html/js/processes/scripts')
    .js('resources/js/processes/scripts/edit.js', 'public_html/js/processes/scripts')
    .js('resources/js/processes/environment-variables/index.js', 'public_html/js/processes/environment-variables')
    .js('resources/js/processes/screens/index.js', 'public_html/js/processes/screens')
    .js('resources/js/processes/screen-builder/main.js', 'public_html/js/processes/screen-builder')
    .js('resources/js/processes/screen-builder/typeForm.js', 'public_html/js/processes/screen-builder')
    .js('resources/js/processes/screen-builder/typeDisplay.js', 'public_html/js/processes/screen-builder')

    .js('resources/js/requests/index.js', 'public_html/js/requests')


    .js('resources/js/nayra/start.js', 'public_html/js/nayra')

    .js('resources/js/requests/show.js', 'public_html/js/requests')
    .js('resources/js/tasks/index.js', 'public_html/js/tasks/index.js')
    .js('resources/js/tasks/show.js', 'public_html/js/tasks/show.js')




    // Note, that this should go last for the extract to properly put the manifest and vendor in the right location
    // See: https://github.com/JeffreyWay/laravel-mix/issues/1118
    .js('resources/js/app.js', 'public_html/js')

    .extract(['vue', 'jquery', 'bootstrap-vue', 'axios', 'popper.js', 'lodash', 'bootstrap'])
    .copy('resources/img/*', 'public_html/img')
    .sass('resources/sass/sidebar/sidebar.scss', 'public_html/css')
    .sass('resources/sass/app.scss', 'public_html/css')
    .sass('resources/sass/admin/queues.scss', 'public_html/css/admin')
    .copy('node_modules/snapsvg/dist/snap.svg.js', 'public_html/js')
    .copy('resources/js/components/CustomActions.vue', 'public_html/js')
    .copy('resources/js/components/DetailRow.vue', 'public_html/js')
    .copy('resources/fonts/Open_Sans/', 'public_html/fonts')
    .copy('resources/js/components/FilterBar.vue', 'public_html/js')
    // Copy files necessary for images for the designer/modeler to it's own img directory
    .copy('node_modules/@processmaker/modeler/dist/img', 'public_html/js/processes/modeler/img')

    .version()
    .setPublicPath('public_html')
