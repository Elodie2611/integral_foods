var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */

    .addEntry('ban', './assets/images/ban.jpg')
    .addEntry('ban_small', './assets/images/ban_small.jpg')
    .addEntry('ban2', './assets/images/ban2.jpg')
    .addEntry('brigade', './assets/images/brigade.png')
    .addEntry('brigadesmall', './assets/images/brigadesmall.png')
    .addEntry('logo_small', './assets/images/logo_small.png')
    .addEntry('oil', './assets/images/oil.jpg')
    .addEntry('oil2', './assets/images/oil2.jpg')
    .addEntry('sauces', './assets/images/sauces.jpg')
    .addEntry('sauces2', './assets/images/sauces2.jpg')
    .addEntry('spice', './assets/images/spice.jpg')
    .addEntry('spice2', './assets/images/spice2.jpg')
    .addEntry('spices', './assets/images/spices.jpg')
    .addEntry('logo_co', './assets/images/logo_co.png')
    .addEntry('categories', './assets/images/categories.png')
    .addEntry('products', './assets/images/products.png')
    .addEntry('users', './assets/images/users.png')
    .addEntry('prices', './assets/images/prices.png')
    .addEntry('orders', './assets/images/orders.png')


    //.addEntry('page1', './assets/js/page1.js')
    //.addEntry('page2', './assets/js/page2.js')

    // allow sass/scss files to be processed
    .enableSassLoader()

;

module.exports = Encore.getWebpackConfig();
