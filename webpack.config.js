const Encore = require('@symfony/webpack-encore');

// Enable Stimulus bridge
Encore.enableStimulusBridge('./assets/controllers.json');

// Other Webpack Encore configuration
Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .enablePostCssLoader() // Enable PostCSS for Tailwind
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();