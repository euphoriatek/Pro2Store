const {copy, remove, exists, mkdir, readFile, emptyDir, writeFile, outputFile} = require('fs-extra');

const minify = require('@node-minify/core');
const terser = require('@node-minify/terser');
const stringreplace = require('replace-in-file');

(async function exec() {
    const timeElapsed = Date.now();
    const today = new Date(timeElapsed);
    const theTime = today.toUTCString();

    emptyDir('./media/com_protostore/js/vue/product').then(() => {
        remove('./media/com_protostore/js/vue/product').then(() => {
            mkdir('./media/com_protostore/js/vue/product').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/product.js',
                    output: './media/com_protostore/js/vue/product/product.min.js'
                }).then(function (min) {
                    console.log('Product done on: ' + theTime);
                });
                minify({
                    compressor: terser,
                    input: './vuefiles/add_product.js',
                    output: './media/com_protostore/js/vue/product/add_product.min.js'
                }).then(function (min) {
                    console.log('Add Product done on: ' + theTime);
                });

            });
        });
    });

    emptyDir('./media/com_protostore/js/vue/currency').then(() => {
        remove('./media/com_protostore/js/vue/currency').then(() => {
            mkdir('./media/com_protostore/js/vue/currency').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/currency.js',
                    output: './media/com_protostore/js/vue/currency/currency.min.js'
                }).then(function (min) {
                    console.log('currency done on: ' + theTime);
                });
                minify({
                    compressor: terser,
                    input: './vuefiles/add_currency.js',
                    output: './media/com_protostore/js/vue/currency/add_currency.min.js'
                }).then(function (min) {
                    console.log('Add currency done on: ' + theTime);
                });

            });
        });
    });


    emptyDir('./media/com_protostore/js/vue/products').then(() => {
        remove('./media/com_protostore/js/vue/products').then(() => {
            mkdir('./media/com_protostore/js/vue/products').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/products.js',
                    output: './media/com_protostore/js/vue/products/products.min.js'
                }).then(function (min) {
                    console.log('Products done on: ' + theTime);
                });
            });
        });
    });

    emptyDir('./media/com_protostore/js/vue/orders').then(() => {
        remove('./media/com_protostore/js/vue/orders').then(() => {
            mkdir('./media/com_protostore/js/vue/orders').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/orders.js',
                    output: './media/com_protostore/js/vue/orders/orders.min.js'
                }).then(function (min) {
                    console.log('Orders done on: ' + theTime);
                });
            });
        });
    });

    emptyDir('./media/com_protostore/js/vue/currencies').then(() => {
        remove('./media/com_protostore/js/vue/currencies').then(() => {
            mkdir('./media/com_protostore/js/vue/currencies').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/currencies.js',
                    output: './media/com_protostore/js/vue/currencies/currencies.min.js'
                }).then(function (min) {
                    console.log('Currencies done on: ' + theTime);
                });
            });
        });
    });
    emptyDir('./media/com_protostore/js/vue/customers').then(() => {
        remove('./media/com_protostore/js/vue/customers').then(() => {
            mkdir('./media/com_protostore/js/vue/customers').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/customers.js',
                    output: './media/com_protostore/js/vue/customers/customers.min.js'
                }).then(function (min) {
                    console.log('Customers done on: ' + theTime);
                });
            });
        });
    });

    emptyDir('./media/com_protostore/js/vue/countries').then(() => {
        remove('./media/com_protostore/js/vue/countries').then(() => {
            mkdir('./media/com_protostore/js/vue/countries').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/countries.js',
                    output: './media/com_protostore/js/vue/countries/countries.min.js'
                }).then(function (min) {
                    console.log('Countries done on: ' + theTime);
                });
            });
        });
    });
    emptyDir('./media/com_protostore/js/vue/zones').then(() => {
        remove('./media/com_protostore/js/vue/zones').then(() => {
            mkdir('./media/com_protostore/js/vue/zones').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/zones.js',
                    output: './media/com_protostore/js/vue/zones/zones.min.js'
                }).then(function (min) {
                    console.log('zones done on: ' + theTime);
                });
            });
        });
    });



    emptyDir('./media/com_protostore/js/vue/dashboard').then(() => {
        remove('./media/com_protostore/js/vue/dashboard').then(() => {
            mkdir('./media/com_protostore/js/vue/dashboard').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/dashboard.js',
                    output: './media/com_protostore/js/vue/dashboard/dashboard.min.js'
                }).then(function (min) {
                    console.log('Dashboard done on: ' + theTime);
                });

            });
        });
    });
    emptyDir('./media/com_protostore/js/vue/modules/latestorders').then(() => {
        remove('./media/com_protostore/js/vue/modules/latestorders').then(() => {
            mkdir('./media/com_protostore/js/vue/modules/latestorders').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/latestorders.js',
                    output: './media/com_protostore/js/vue/modules/latestorders/latestorders.min.js'
                }).then(function (min) {
                    console.log('Latestorders done on: ' + theTime);
                });

            });
        });
    });

})();
