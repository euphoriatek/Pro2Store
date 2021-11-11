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
    await rimRaf('./dist');
    await rimRaf('./build/output/');

    //set up the output folders
    if (!(await exists('./build/output'))) {
        await mkdir('./build/output');
    }

    if (!(await exists('./build/output/package'))) {
        await mkdir('./build/output/package');
    }
    if (!(await exists('./build/output/package/packages'))) {
        await mkdir('./build/output/package/packages');
    }


    // component
    if (!(await exists('./build/output/the_component_folder'))) {
        await mkdir('./build/output/the_component_folder');
    }
    // create the directories for the component
    if (!(await exists('./build/output/the_component_folder/admin'))) {
        await mkdir('./build/output/the_component_folder/admin');
    }
    if (!(await exists('./build/output/the_component_folder/site'))) {
        await mkdir('./build/output/the_component_folder/site');
    }
    if (!(await exists('./build/output/the_component_folder/media'))) {
        await mkdir('./build/output/the_component_folder/media');
    }
    // insert the version number in the component XML
    let xmla = await readFile('./build/packagefiles/component/protostore.xml', {encoding: 'utf8'});
    xmla = xmla.replace(/{{version}}/g, version);

    // write the XML for the component
    await writeFile('./build/output/the_component_folder/protostore.xml', xmla, {encoding: 'utf8'});


    // copy over the code from the administrator folder, media folder and site folder.
    await copy('./administrator/components/com_protostore', './build/output/the_component_folder/admin');
    await copy('./components/com_protostore', './build/output/the_component_folder/site');
    await copy('./media/com_protostore', './build/output/the_component_folder/media');


    // clean out GIT
    await remove('./build/output/the_component_folder/admin/.git');


    // copy over language files
    if (!(await exists('./build/output/the_component_folder/admin/language'))) {
        await mkdir('./build/output/the_component_folder/admin/language');
    }

    if (!(await exists('./build/output/the_component_folder/admin/language/en-GB'))) {
        await mkdir('./build/output/the_component_folder/admin/language/en-GB');
    }

    await copy('./administrator/language/en-GB/en-GB.com_protostore.ini', './build/output/the_component_folder/admin/language/en-GB/en-GB.com_protostore.ini');
    await copy('./administrator/language/en-GB/en-GB.com_protostore.sys.ini', './build/output/the_component_folder/admin/language/en-GB/en-GB.com_protostore.sys.ini');

    let zip = new (require('adm-zip'));
    zip.addLocalFolder('./build/output/the_component_folder', false);
    zip.writeZip(`./build/output/package/packages/com_protostore.zip`);
    zip.deleteFile(`./build/output/the_component_folder`);

    await rimRaf('./build/output/the_component_folder');

    /** COMPONENT DONE **/


    // // system plugin
    zip = new (require('adm-zip'));
    zip.addLocalFolder('./plugins/system/protostore', false);
    zip.writeZip(`./build/output/package/packages/plg_system_protostore.zip`);

    /** SYSTEM PLUGIN DONE **/


    // system emailer plugin
    zip = new (require('adm-zip'));
    zip.addLocalFolder('./plugins/protostoresystem/protostore_emailer', false);
    zip.writeZip(`./build/output/package/packages/plg_protostoresystem_protostore_emailer.zip`);

    /** SYSTEM EMAILER PLUGIN DONE **/


    // system shipping plugin
    zip = new (require('adm-zip'));
    zip.addLocalFolder('./plugins/protostoreshipping/defaultshipping', false);
    zip.writeZip(`./build/output/package/packages/plg_protostoreshipping_defaultshipping.zip`);

    /** SYSTEM SHIPPING PLUGIN DONE **/

    // offlinepay plugins
    zip = new (require('adm-zip'));
    zip.addLocalFolder('./plugins/protostorepayment/offlinepay', false);
    zip.writeZip(`./build/output/package/packages/plg_protostorepayment_offlinepay.zip`);


    zip = new (require('adm-zip'));
    zip.addLocalFolder('./plugins/system/protostore_offlinepay', false);
    zip.writeZip(`./build/output/package/packages/plg_system_protostore_offlinepay.zip`);

    /** OFFLINE PAY PLUGINS DONE **/


    // Content plugin
    zip = new (require('adm-zip'));
    zip.addLocalFolder('./plugins/content/protostore_fields', false);
    zip.writeZip(`./build/output/package/packages/plg_content_protostore_fields.zip`);


    //
    // Content plugin
    zip = new (require('adm-zip'));
    zip.addLocalFolder('./plugins/content/protostore_shortcodes', false);
    zip.writeZip(`./build/output/package/packages/plg_content_protostore_shortcodes.zip`);
    /** CONTENT PLUGINS DONE **/

    // User plugin
    zip = new (require('adm-zip'));
    zip.addLocalFolder('./plugins/user/protostore', false);
    zip.writeZip(`./build/output/package/packages/plg_user_protostore.zip`);

    /** USER PLUGIN DONE **/

    // Quickicon plugin
    zip = new (require('adm-zip'));
    zip.addLocalFolder('./plugins/quickicon/protostore', false);
    zip.writeZip(`./build/output/package/packages/plg_quickicon_protostore.zip`);

    /** USER PLUGIN DONE **/


        // sort out AJAX plugin
    const replaceErrorReporting = {
            files: './plugins/ajax/protostore_ajaxhelper/protostore_ajaxhelper.php',
            from: /\/\/ error_reporting\(0\);/g,
            to: 'error_reporting(0);',
        };

    try {
        const results = await stringreplace(replaceErrorReporting)
        console.log('Replacement results:', results);
    } catch (error) {
        console.error('Error occurred:', error);
    }

    // ajax helper
    zip = new (require('adm-zip'));
    zip.addLocalFolder('./plugins/ajax/protostore_ajaxhelper', false);
    zip.writeZip(`./build/output/package/packages/plg_ajax_protostore_ajaxhelper.zip`);


    const replaceErrorReportingBackToDev = {
        files: 'plugins/ajax/protostore_ajaxhelper/protostore_ajaxhelper.php',
        from: /error_reporting\(0\);/g,
        to: '// error_reporting(0);',
    };

    try {
        const results = await stringreplace(replaceErrorReportingBackToDev)
        console.log('Replacement results:', results);
    } catch (error) {
        console.error('Error occurred in replacing error reporting replacement:', error);
    }

    /** AJAX PLUGIN DONE **/


    /** MODULES **/


    // cart module
    zip = new (require('adm-zip'));
    zip.addLocalFolder('./modules/mod_protostorecart', false);
    zip.writeZip(`./build/output/package/packages/mod_protostorecart.zip`);

    // cart Fab module
    const zip6fab = new (require('adm-zip'));
    zip6fab.addLocalFolder('modules/mod_protostorecartfab', false);
    zip6fab.writeZip(`package/packages/mod_protostorecartfab.zip`);


    // currency switcher module
    zip = new (require('adm-zip'));
    zip.addLocalFolder('./modules/mod_protostorecurrencyswitcher', false);
    zip.writeZip(`./build/output/package/packages/mod_protostorecurrencyswitcher.zip`);




    // Customer Addresses module
    const zip8e = new (require('adm-zip'));
    zip8e.addLocalFolder('modules/mod_protostorecustomeraddresses', false);
    zip8e.writeZip(`package/packages/mod_protostorecustomeraddresses.zip`);

    // Customer Orders module
    const zip8f = new (require('adm-zip'));
    zip8f.addLocalFolder('modules/mod_protostorecustomerorders', false);
    zip8f.writeZip(`package/packages/mod_protostorecustomerorders.zip`);


    await rimRaf('./build/output/package/protostore.xml');

    await copy('./build/packagefiles/pkg_protostore.xml', './build/output/package/pkg_protostore.xml');
    await copy('./build/packagefiles/script.php', './build/output/package/script.php');

    if (!(await exists('./dist'))) {
        await mkdir('./dist');
    }

    let xml = await readFile('./build/output/package/pkg_protostore.xml', {encoding: 'utf8'});
    xml = xml.replace(/{{version}}/g, version);

    await writeFile('./build/output/package/pkg_protostore.xml', xml, {encoding: 'utf8'});


    // Package it
    zip = new (require('adm-zip'));
    zip.addLocalFolder('./build/output/package', false);
    zip.writeZip(`dist/pkg_protostore_${version}.zip`);

    await rimRaf('./build/output');


})();
