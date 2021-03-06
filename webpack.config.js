var Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .addEntry('app', './assets/js/app.js')
  .splitEntryChunks()
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .configureBabelPresetEnv((config) => {
      config.useBuiltIns = 'usage';
      config.corejs = 3;
  })
  .configureBabel(function(babelConfig) {

    //This is needed.

    babelConfig.plugins = ["@babel/plugin-proposal-object-rest-spread"]
  })
  .enableSassLoader()
  .enableReactPreset()
;

module.exports = Encore.getWebpackConfig();
