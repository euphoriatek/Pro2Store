////////////////////////////////////////////////////////////////////////////////
// @package   Pro2Store
// @author    Ray Lawlor - pro2.store
// @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
// @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
//

const {copy, remove, exists, mkdir, readFile, emptyDir, writeFile, outputFile} = require('fs-extra');
const util = require('util');
// const path = require('path');
// const fs = require('fs');
const stringreplace = require('replace-in-file');
const rimRaf = util.promisify(require('rimraf'));
const find = require('find');
const {version} = require('../package.json');

(async function exec() {

    // // system plugin
    zip = new (require('adm-zip'));
    zip.addLocalFolder('./plugins/protostore_extended/p2sfaker', false);
    zip.writeZip(`./build/output/p2sfaker/plg_protostore_extended_p2sfaker.zip`);

    /** SYSTEM PLUGIN DONE **/


})();
