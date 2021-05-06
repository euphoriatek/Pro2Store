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

            });
        });
    });

})();
