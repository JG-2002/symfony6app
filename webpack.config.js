const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    .addLoader({ test: /\.(cur)$/, loader: 'file-loader' })
    .cleanupOutputBeforeBuild(["public"], (options) => {
        options.verbose = true;
        options.root = __dirname;
        options.exclude = [
            "index.php",
            "manifest.json",
            'site.webmanifest',
            'favicon.ico',
            'robots.txt',
            "dl",
            "images"
        ];
    })
    .copyFiles([
        {
            from: "./node_modules/ckeditor4/",
            to: "ckeditor/[path][name].[ext]",
            pattern: /\.(js|css)$/,
            includeSubdirectories: false,
        },
        {
            from: "./node_modules/ckeditor4/adapters",
            to: "ckeditor/adapters/[path][name].[ext]",
        },
        {
            from: "./node_modules/ckeditor4/lang",
            to: "ckeditor/lang/[path][name].[ext]",
        },
        {
            from: "./node_modules/ckeditor4/plugins",
            to: "ckeditor/plugins/[path][name].[ext]",
        },
        {
            from: "./node_modules/ckeditor4/skins",
            to: "ckeditor/skins/[path][name].[ext]",
        },
        {
            from: "./node_modules/ckeditor4/vendor",
            to: "ckeditor/vendor/[path][name].[ext]",
        },
    ])

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', [
        './assets/app.js',
        './assets/images.js'
    ])
    .addEntry('js/front-home', [
        './assets/js/bootstrap.bundle.min.js',
        './assets/js/tiny-slider.js',
        './assets/js/custom.js',
    ])
    .addEntry('js/front', [
        './assets/js/bootstrap.bundle.min.js',
        './assets/js/custom.js',
    ])
    .addStyleEntry("css/front/bootstrap", ["./assets/styles/bootstrap.min.css"])
    .addStyleEntry('css/front/tiny-slider', [
        './assets/styles/tiny-slider.css'
    ])
    .addStyleEntry('css/front/style', [
        './assets/styles/style.css'
    ])

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // configure Babel
    // .configureBabel((config) => {
    //     config.plugins.push('@babel/a-babel-plugin');
    // })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()
    ;

var config = Encore.getWebpackConfig();

var path = require('path');
config.resolve.alias = {
    'jquery': path.join(__dirname, 'node_modules/jquery/src/jquery')
};
module.exports = Encore.getWebpackConfig();
