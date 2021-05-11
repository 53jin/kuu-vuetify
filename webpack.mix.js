const mix = require('laravel-mix');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
require("@pp-spaces/laravel-mix-graphql");
require('laravel-mix-versionhash');

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
mix.disableNotifications();
mix.webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.webpackConfig({
        plugins: [
            new CleanWebpackPlugin({
                cleanOnceBeforeBuildPatterns: [],
                cleanAfterEveryBuildPatterns: [
                    "**/*.js.LICENSE",
                    "**/*.js.LICENSE.*",
                ],
            }),
        ],
    });
    mix.options({
        terser: {
            terserOptions: {
                sourceMap: false,
                compress: {
                    warnings: false,
                    drop_console: true
                }
            }
        }
    });
} else {
    mix.webpackConfig({
        devServer: {
            disableHostCheck: true,
        },
    });
    mix.options({
        hmrOptions: {
            host: 'localhost',
            port: 8087,
        }
    });
}
if (mix.inProduction()) {
    mix.version();
}

mix.options({
    extractVueStyles: 'public/css/style.css',
    processCssUrls: true,
});

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/auth.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .extract().graphql();
