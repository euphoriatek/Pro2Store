////////////////////////////////////////////////////////////////////////////////
// @package   Pro2Store
// @author    Ray Lawlor - pro2.store
// @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
// @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
//

const {remove, mkdir, emptyDir} = require('fs-extra');
const fs = require('fs');
const minify = require('@node-minify/core');
const terser = require('@node-minify/terser');

(async function exec() {

    console.clear()
    const timeElapsed = Date.now();
    const today = new Date(timeElapsed);
    const theTime = today.toUTCString();

    fs.readdir('vuefiles', (err, files) => {
        files.forEach(file => {
            let filename = file.replace(".js", "")
            emptyDir('./media/com_protostore/js/vue/' + filename).then(() => {
                remove('./media/com_protostore/js/vue/' + filename).then(() => {
                    mkdir('./media/com_protostore/js/vue/' + filename).then(() => {
                        minify({
                            compressor: terser,
                            input: './vuefiles/' + file,
                            output: './media/com_protostore/js/vue/' + filename + '/' + filename + '.min.js'
                        }).then(function () {
                            console.log(file + ' done on: ' + theTime);
                        });

                    });
                });
            });


        });
    });


})();
