var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('js/main', './assets/js/main.js')
    .addEntry('js/Components/category', './assets/js/Components/category.js')
    .addEntry('js/Components/goal', './assets/js/Components/goal.js')
    .addEntry('js/Components/stage', './assets/js/Components/stage.js')

    .addStyleEntry('css/app', './assets/css/main.css')

    // uncomment if you use Sass/SCSS files
    // .enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    // .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
