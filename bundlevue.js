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
                }).then(function () {
                    console.log('Product done on: ' + theTime);
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
                }).then(function () {
                    console.log('currency done on: ' + theTime);
                });

            });
        });
    });

    emptyDir('./media/com_protostore/js/vue/customer').then(() => {
        remove('./media/com_protostore/js/vue/customer').then(() => {
            mkdir('./media/com_protostore/js/vue/customer').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/customer.js',
                    output: './media/com_protostore/js/vue/customer/customer.min.js'
                }).then(function () {
                    console.log('customer done on: ' + theTime);
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
                }).then(function () {
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
                }).then(function () {
                    console.log('Orders done on: ' + theTime);
                });
            });
        });
    });
    emptyDir('./media/com_protostore/js/vue/order').then(() => {
        remove('./media/com_protostore/js/vue/order').then(() => {
            mkdir('./media/com_protostore/js/vue/order').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/order.js',
                    output: './media/com_protostore/js/vue/order/order.min.js'
                }).then(function () {
                    console.log('Order done on: ' + theTime);
                });
            });
        });
    });
    emptyDir('./media/com_protostore/js/vue/productoptions').then(() => {
        remove('./media/com_protostore/js/vue/productoptions').then(() => {
            mkdir('./media/com_protostore/js/vue/productoptions').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/productoptions.js',
                    output: './media/com_protostore/js/vue/productoptions/productoptions.min.js'
                }).then(function () {
                    console.log('Productoptions done on: ' + theTime);
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
                }).then(function () {
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
                }).then(function () {
                    console.log('Customers done on: ' + theTime);
                });
            });
        });
    });
    emptyDir('./media/com_protostore/js/vue/discounts').then(() => {
        remove('./media/com_protostore/js/vue/discounts').then(() => {
            mkdir('./media/com_protostore/js/vue/discounts').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/discounts.js',
                    output: './media/com_protostore/js/vue/discounts/discounts.min.js'
                }).then(function () {
                    console.log('Discounts done on: ' + theTime);
                });
            });
        });
    });
    emptyDir('./media/com_protostore/js/vue/discount').then(() => {
        remove('./media/com_protostore/js/vue/discount').then(() => {
            mkdir('./media/com_protostore/js/vue/discount').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/discount.js',
                    output: './media/com_protostore/js/vue/discount/discount.min.js'
                }).then(function () {
                    console.log('Discount done on: ' + theTime);
                });
            });
        });
    });
    emptyDir('./media/com_protostore/js/vue/email').then(() => {
        remove('./media/com_protostore/js/vue/email').then(() => {
            mkdir('./media/com_protostore/js/vue/email').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/email.js',
                    output: './media/com_protostore/js/vue/email/email.min.js'
                }).then(function () {
                    console.log('Email done on: ' + theTime);
                });
            });
        });
    });
    emptyDir('./media/com_protostore/js/vue/emaillogs').then(() => {
        remove('./media/com_protostore/js/vue/emaillogs').then(() => {
            mkdir('./media/com_protostore/js/vue/emaillogs').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/emaillogs.js',
                    output: './media/com_protostore/js/vue/emaillogs/emaillogs.min.js'
                }).then(function () {
                    console.log('Email Logs done on: ' + theTime);
                });
            });
        });
    });
    emptyDir('./media/com_protostore/js/vue/shippingratescountry').then(() => {
        remove('./media/com_protostore/js/vue/shippingratescountry').then(() => {
            mkdir('./media/com_protostore/js/vue/shippingratescountry').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/shippingratescountry.js',
                    output: './media/com_protostore/js/vue/shippingratescountry/shippingratescountry.min.js'
                }).then(function () {
                    console.log('Shippingratescountry done on: ' + theTime);
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
                }).then(function () {
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
                }).then(function () {
                    console.log('zones done on: ' + theTime);
                });
            });
        });
    });

    emptyDir('./media/com_protostore/js/vue/emailmanager').then(() => {
        remove('./media/com_protostore/js/vue/emailmanager').then(() => {
            mkdir('./media/com_protostore/js/vue/emailmanager').then(() => {
                minify({
                    compressor: terser,
                    input: './vuefiles/emailmanager.js',
                    output: './media/com_protostore/js/vue/emailmanager/emailmanager.min.js'
                }).then(function () {
                    console.log('emailmanager done on: ' + theTime);
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
                }).then(function () {
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
                }).then(function () {
                    console.log('Latestorders done on: ' + theTime);
                });

            });
        });
    });

})();
