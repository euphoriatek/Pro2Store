<?php
/**
 * @package     Pro2Store
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

/** @var array $vars */
/** @var Protostore\Product\Product $item */
$item = $vars['item'];


?>


<div id="p2s_product_form">
    <form @submit.prevent="saveItem" v-cloak>
        <div class="uk-margin-left">
            <div class="uk-grid" uk-grid="">
                <div class="uk-width-1-1">

                    <div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; offset: 31">

                        <nav class="uk-navbar-container uk-padding-small" uk-navbar style="border-radius: 8px">

                            <div class="uk-navbar-left">

                                <span class="uk-navbar-item uk-logo" v-cloak>
                                    <span v-show="form.itemid"> <?= Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_EDITING'); ?>  {{form.jform_title}}</span>
                                    <span v-show="!form.itemid"> <?= Text::_('COM_PROTOSTORE_ADD_PRODUCT_TITLE'); ?>:  {{form.jform_title}}</span>

                                </span>

                            </div>

                            <div class="uk-navbar-right">

                                <button type="button"
                                        class="uk-button uk-button-primary uk-button-small uk-margin-right"
                                        @click="logIt">LogIt
                                </button>
                                <button type="submit" @click="andClose = false"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
                                    Save
                                </button>
                                <button type="submit" @click="andClose = true"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
                                    Save & Close
                                </button>
                                <a class="uk-button uk-button-default uk-button-small uk-margin-right"
                                   href="index.php?option=com_protostore&view=products">Cancel</a>
                                <button type="button" uk-toggle="target: #advancedOptions"
                                        class="uk-button uk-button-primary uk-button-small uk-margin-right">
                                    Advanced Options
                                    <span uk-icon="icon: settings"></span>
                                </button>

                            </div>

                        </nav>
                    </div>

                </div>

                <div class="uk-width-2-3">

					<?= LayoutHelper::render('product/card_details', array(
						'form'      => $vars['form'],
						'cardStyle' => 'default',
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_PRODUCT_DETAILS',
						'cardId'    => 'details',
						'fields'    => array('title', 'short_description', 'long_description')
					)); ?>

					<?= LayoutHelper::render('card', array(
						'form'             => $vars['form'],
						'cardTitle'        => 'COM_PROTOSTORE_ADD_PRODUCT_IMAGES',
						'cardStyle'        => 'default',
						'cardId'           => 'images',
						'fields'           => array('teaserimage', 'fullimage'),
						'field_grid_width' => '1-2',
					)); ?>
                    <span v-show="form.jform_product_type == 2">
						<?= LayoutHelper::render('product/card_digital', array(
							'form'      => $vars['form'],
							'cardTitle' => 'Digital Details',
							'cardStyle' => 'default',
							'cardId'    => 'digital',
						)); ?>
					</span>

                    <!--					--><?php //echo LayoutHelper::render('product/card_options', array(
					//						'form'             => $vars['form'],
					//						'cardTitle'        => 'COM_PROTOSTORE_ADD_PRODUCT_OPTIONS',
					//						'cardStyle'        => 'default',
					//						'cardId'           => 'options',
					//						'fields'           => array('options'),
					//						'field_grid_width' => '1-1',
					//					)); ?>

					<?= LayoutHelper::render('product/card_variant', array(
						'form'             => $vars['form'],
						'cardTitle'        => 'COM_PROTOSTORE_ADD_PRODUCT_VARIANTS',
						'cardStyle'        => 'default',
						'cardId'           => 'variants',
						'fields'           => array('variants'),
						'field_grid_width' => '1-1',
					)); ?>

					<?= LayoutHelper::render('product/card_custom_fields', array(
						'form'             => $vars['form'],
						'cardTitle'        => 'COM_PROTOSTORE_ADD_PRODUCT_JOOMLA_CUSTOM_FIELDS',
						'cardStyle'        => 'default',
						'cardId'           => 'custom_fields',
						'field_grid_width' => '1-1',
					)); ?>


                </div>


                <div class="uk-width-1-3">


					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_PRODUCT_ACCESS',
						'cardStyle' => 'default',
						'cardId'    => 'access',
						'fields'    => array('state', 'access', 'publish_up_date')
					)); ?>
					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_ORGANISATION',
						'cardStyle' => 'default',
						'cardId'    => 'organisation',
						'fields'    => array('category', 'featured', 'tags')
					)); ?>
					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_PRODUCT_PRICING',
						'cardStyle' => 'default',
						'cardId'    => 'pricing',
						'fields'    => array('base_price', 'taxable', 'show_discount', 'discount')
					)); ?>
                    <span v-show="form.jform_product_type == 1">
                        <?= LayoutHelper::render('card', array(
	                        'form'      => $vars['form'],
	                        'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_INVENTORY',
	                        'cardStyle' => 'default',
	                        'cardId'    => 'inventory',
	                        'fields'    => array('sku', 'manage_stock', 'stock')
                        )); ?>
                    </span>

                    <span v-show="form.jform_product_type == 1">
						<?= LayoutHelper::render('card', array(
							'form'      => $vars['form'],
							'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_SHIPPING',
							'cardStyle' => 'default',
							'cardId'    => 'shipping',
							'fields'    => array('shipping_mode', 'flatfee')
						)); ?>
					</span>


                </div>
            </div>

        </div>
    </form>

	<?= LayoutHelper::render('product/modals/advancedOptions'); ?>

</div>


<script>
    const p2s_product_form = {
        data() {
            return {
                base_url: '',
                form: {
                    itemid: '',
                    jform_title: '',
                    jform_catid: '',
                    jform_short_description: '',
                    jform_long_description: '',
                    jform_access: '',
                    jform_base_price: 0,
                    jform_sku: '',
                    jform_category: '',
                    jform_manage_stock: true,
                    jform_stock: '',
                    jform_featured: false,
                    jform_state: false,
                    jform_taxable: false,
                    jform_show_discount: false,
                    jform_discount: 0,
                    jform_discount_type: 1,
                    jform_teaserimage: '',
                    jform_fullimage: '',
                    jform_shipping_mode: '',
                    jform_flatfee: 0,
                    jform_publish_up_date: '',
                    jform_product_type: '',
                    jform_tags: [],
                    jform_variants: [],
                    jform_variantList: [],
                    files: []
                },
                product_id: 0,
                product_type: 1,
                available_custom_fields: [],
                available_tags: [],
                // available_options: [],
                option_for_edit: [],
                p2s_currency: [],
                p2s_local: '',
                discount_type: 1,
                andClose: false,
                variantsSet: false,
                successMessage: '',
                file_for_edit: {},
                newOptionTypeName: '',
                newOptionTypeType: 'Dropdown',
                showNewOptionTypeNameWarning: false,
                sellPrice: 0,
                variants_loading: false,
                setSavedClass: false,
            }

        },
        created() {
            emitter.on('p2s_product_file_upload', this.fileUploaded)
        },
        mounted() {

        },
        computed: {
            modifierValueInputType() {
                if (this.option_for_edit.modifiertype === "perc") {
                    return "[0-9]*";
                } else if (this.option_for_edit.modifiertype === "amount") {
                    return "^[0-9]+(\.[0-9]{1,2})?$"
                }
            }
        },
        async beforeMount() {

            const base_url = document.getElementById('base_url');
            if (base_url != null) {
                try {
                    this.base_url = base_url.innerText;
                    base_url.remove();
                } catch (err) {
                }
            }

            const p2s_currency = document.getElementById('currency');
            if (p2s_currency != null) {
                try {
                    this.p2s_currency = JSON.parse(p2s_currency.innerText);
                    p2s_currency.remove();
                } catch (err) {
                }
            }


            const p2s_locale = document.getElementById('locale');
            if (p2s_locale != null) {
                try {
                    this.p2s_local = p2s_locale.innerText;
                    p2s_locale.remove();
                } catch (err) {
                }
            }
            const default_category = document.getElementById('default_category_data');
            if (default_category != null) {
                try {
                    this.form.jform_category = default_category.innerText;
                    default_category.remove();
                } catch (err) {
                }
            }

            const itemid = document.getElementById('jform_joomla_item_id_data');
            if (itemid != null) {
                // for product edit... do everything inside this if block since we have an item id
                try {
                    this.form.itemid = itemid.innerText;
                    // itemid.remove();
                } catch (err) {
                }

                this.setData();

                const successMessage = document.getElementById('successMessage');
                if (successMessage != null) {
                    try {
                        this.successMessage = successMessage.innerText;
                        // successMessage.remove();
                    } catch (err) {
                    }
                }
            }
            const available_tags = document.getElementById('available_tags_data');
            if (available_tags != null) {
                try {
                    this.available_tags = JSON.parse(available_tags.innerText);
                    // available_tags.remove();
                } catch (err) {
                }
            }
        },
        methods: {

            getSellPrice() {

                const options = {
                    maximumFractionDigits: 2,
                    currency: this.p2s_currency.iso,
                    style: "currency",
                    currencyDisplay: "symbol"
                }


                if (this.discount_type == 1) {

                    this.sellPrice = this.localStringToNumber(this.form.jform_base_price - this.form.jform_discount).toLocaleString(undefined, options);
                } else {

                    // work out the percentage
                    const discount = (this.form.jform_base_price / 100) * this.form.jform_discount;

                    this.sellPrice = this.localStringToNumber(this.form.jform_base_price - discount).toLocaleString(undefined, options);
                }
            },

            logIt() {
                console.log(this.form);
            },

            async addLabel(e, variant_id) {


                // get the array of current labels
                let loc_array = e.value;

                console.log(loc_array);

                // get the last entered label
                let enteredValue = loc_array[loc_array.length - 1];

                // chop off the last label, since it only contains the entered text
                loc_array.splice(-1);

                // now push a new object into the array with the id as zero etc.
                loc_array.push({
                    id: 0,
                    name: enteredValue,
                    product_id: this.form.itemid,
                    variant_id: variant_id
                });


            },

            async onAddNewLabel(e, variant_id) {
                await this.addLabel(e, variant_id);
                await this.setVariants();
                await this.saveItem();
            },
            async removeLabel(event, index, variant_id) {

                this.form.jform_variants[index].labels.push(event.value[0]);
                await UIkit.modal.confirm('Are you sure? This action cannot be undone!');
                this.form.jform_variants[index].labels.splice(-1);
                await this.setVariants();
                await this.saveItem();
            },

            /**
             * TAGS
             */

            addTagFromChip(tag, i) {
                this.form.jform_tags.push(tag);
                this.available_tags.splice(i, 1);
            },

            addBackToAvailable(e) {
                this.available_tags.push(e.value[0]);
            },

            /**
             * VARIANTS
             */
            addVariant() {

                let newVariant = {
                    id: 0,
                    product_id: this.form.itemid,
                    name: '',
                    labels: []
                }

                this.form.jform_variants.push(newVariant);
            },
            async removeVariant(i) {
                await UIkit.modal.confirm('Are you sure? This action cannot be undone!');
                this.form.jform_variants[i].labels = [];
                this.form.jform_variants.splice(i, 1);
                await this.setVariants();
                await this.saveItem();
            },


            async setVariants() {
                this.variants_loading = true;
                const params = {
                    'variants': this.form.jform_variants,
                    'itemid': this.form.itemid,
                    'base_price': this.form.jform_base_price,
                };


                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.saveVariants&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: JSON.stringify(params)
                });

                const response = await request.json();

                if (response.success) {
                    this.variants_loading = false;
                    this.setSavedClass = true;
                    setTimeout(() => {
                        this.setSavedClass = false;

                    }, 3000)
                    return await this.refreshVariants();
                }


            },

            async refreshVariants() {

                const params = {
                    'j_item_id': this.form.itemid
                };


                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.refreshVariants&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: JSON.stringify(params)
                });

                const response = await request.json();

                if (response.success) {
                    this.form.jform_variantList = response.data.variantList;
                    this.form.jform_variants = response.data.variants;
                    return true;
                }
            },


            setVariantDefault(itemIndex) {

                this.form.jform_variantList.forEach((variant, index) => {
                    variant.default = false;
                    if (itemIndex === index) {
                        variant.default = true;
                        if (!variant.active) {
                            variant.active = true;
                        }
                    }
                });
            },

            checkVariantDefault(itemIndex) {
                this.form.jform_variantList.forEach((variant, index) => {
                    if (itemIndex === index) {
                        if (variant.default) {
                            variant.active = true;
                            return false;
                        }
                    }
                });
            },

            formatToCurrency(itemPrice) {

                const value = itemPrice;
                const options = {
                    maximumFractionDigits: 2,
                    currency: this.p2s_currency.iso,
                    style: "currency",
                    currencyDisplay: "symbol"
                }

                itemPrice = this.localStringToNumber(value).toLocaleString(undefined, options);


            },
            localStringToNumber(s) {
                return Number(String(s).replace(/[^0-9.-]+/g, ""))
            },


            /**
             * FILE EDIT
             */

            openFileEdit(file) {
                this.file_for_edit = file;
                this.openAddFile();
            },
            openAddFile() {
                UIkit.modal("#fileEditModal").show();
            },
            removeFile() {
                this.file_for_edit.filename_obscured = false;
            },
            fileUploaded(data) {
                console.log("Data is: ", data)
                this.file_for_edit.filename_obscured = data.path;
                this.file_for_edit.filename = data.filename;
            },
            async saveFile() {

                const params = {
                    'fileid': this.file_for_edit.id,
                    'created': this.file_for_edit.created,
                    'download_access': this.file_for_edit.download_access,
                    'filename': this.file_for_edit.filename,
                    'filename_obscured': this.file_for_edit.filename_obscured,
                    'isjoomla': (this.file_for_edit.isjoomla ? 1 : 0),
                    'php_min': this.file_for_edit.php_min,
                    'published': (this.file_for_edit.published ? 1 : 0),
                    'stability_level': this.file_for_edit.stability_level,
                    'type': this.file_for_edit.type,
                    'version': this.file_for_edit.version,
                    'product_id': this.product_id
                };


                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=file.save&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: JSON.stringify(params)
                });


                const response = await request.json();

                if (response.success) {

                    UIkit.notification({
                        message: this.successMessage,
                        status: 'success',
                        pos: 'top-center',
                        timeout: 5000
                    });


                } else {
                    UIkit.notification({
                        message: 'There was an error.',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }

            },

            cancelFile() {
                this.file_for_edit = {};
            },


            /**
             * SAVE AND UTILITIES
             */


            async saveItem() {

                this.form.jform_long_description = this.getFrameContents('jform_long_description');
                this.form.jform_short_description = this.getFrameContents('jform_short_description');
                this.form.jform_teaserimage = document.getElementById("jform_teaserimage").value;
                this.form.jform_fullimage = document.getElementById("jform_fullimage").value;
                this.form.jform_publish_up_date = document.getElementById("jform_publish_up_date").value;
                this.form.jform_access = document.getElementById("jform_access").value;


                console.log(this.form);


                // return;

                const params = {
                    'itemid': this.form.itemid,
                    'title': this.form.jform_title,
                    'introtext': this.form.jform_short_description,
                    'fulltext': this.form.jform_long_description,
                    'category': this.form.jform_category,
                    'access': this.form.jform_access,
                    'base_price': this.form.jform_base_price,
                    'discount': this.form.jform_discount,
                    'tags': this.form.jform_tags,
                    'sku': this.form.jform_sku,
                    'stock': this.form.jform_stock,
                    'manage_stock': (this.form.jform_manage_stock ? 1 : 0),
                    'featured': (this.form.jform_featured ? 1 : 0),
                    'state': (this.form.jform_state ? 1 : 0),
                    'taxable': (this.form.jform_taxable ? 1 : 0),
                    'teaserimage': this.form.jform_teaserimage,
                    'fullimage': this.form.jform_fullimage,
                    'shipping_mode': this.form.jform_shipping_mode,
                    'flatfee': this.form.jform_flatfee,
                    'publish_up_date': this.form.jform_publish_up_date,
                    'product_type': this.form.jform_product_type,
                    'variants': this.form.jform_variants,
                    'variantList': this.form.jform_variantList
                };

                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.save&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: JSON.stringify(params)
                });


                const response = await request.json();

                if (response.success) {

                    UIkit.notification({
                        message: this.successMessage,
                        status: 'success',
                        pos: 'bottom-right',
                        timeout: 5000
                    });


                    if (this.andClose) {
                        // if 'andClose' is true, redirect back to the list page
                        window.location.href = this.base_url + 'index.php?option=com_protostore&view=products';
                    } else {
                        // if 'andClose' is still false, the user wants to stay on the page.
                        // this line makes sure that a new item gets the ID appended to the URL
                        const url = window.location.href;
                        if (url.indexOf('&id=') == -1) {
                            history.replaceState('', '', url + '&id=' + response.data.joomlaItem.id);
                        }

                        // we also need to make sure that the next save action doesn't trigger a create... we do this by adding the id to the form array
                        this.form.itemid = response.data.joomlaItem.id;

                    }

                } else {
                    UIkit.notification({
                        message: 'There was an error.',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }


            },
            getFrameContents(elementId) {
                const iFrame = document.getElementById(elementId + '_ifr');
                let iFrameBody;
                if (iFrame.contentDocument) { // FF
                    iFrameBody = iFrame.contentDocument.getElementById('tinymce');
                } else if (iFrame.contentWindow) { // IE
                    iFrameBody = iFrame.contentWindow.document.getElementById('tinymce');
                }
                return iFrameBody.innerHTML;
            },
            setData() {
                const keys = Object.keys(this.form);
                keys.forEach((jfrom) => {
                    let theInput = document.getElementById(jfrom + '_data');
                    if (theInput) {

                        if (this.hasJsonStructure(theInput.innerText)) {
                            this.form[jfrom] = JSON.parse(theInput.innerText);
                        } else {


                            this.form[jfrom] = theInput.innerText;

                            if (theInput.innerText == 1) {
                                this.form[jfrom] = true;
                            }
                            if (theInput.innerText == 0) {
                                this.form[jfrom] = false;
                            }
                            if (theInput.id === 'jform_base_price_data' || theInput.id === 'jform_discount_data' || theInput.id === 'jform_flatfee_data') {
                                this.form[jfrom] = (Number(theInput.innerText) / 100);
                            }


                        }
                        // theInput.remove();
                    }

                });
            },
            hasJsonStructure(str) {
                if (typeof str !== 'string') return false;
                try {
                    const result = JSON.parse(str);
                    const type = Object.prototype.toString.call(result);
                    return type === '[object Object]'
                        || type === '[object Array]';
                } catch (err) {
                    return false;
                }
            },
            serialize(obj) {
                var str = [];
                for (var p in obj)
                    if (obj.hasOwnProperty(p)) {
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    }
                return str.join("&");
            }
        },
        components: {
            'p-inputswitch': primevue.inputswitch,
            'p-chips': primevue.chips,
            'p-chip': primevue.chip,
            'p-inputnumber': primevue.inputnumber,
            'p-multiselect': primevue.multiselect
        }
    }
    Vue.createApp(p2s_product_form).mount('#p2s_product_form');

</script>


<script>

    var bar = document.getElementById('js-progressbar');


    UIkit.upload('.p2s_file_upload', {

        url: '',
        multiple: false,
        beforeAll: function () {
            this.url = '<?= Uri::base(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=file.upload&format=raw';
            console.log('beforeAll', arguments);
        },

        loadStart: function (e) {

            bar.removeAttribute('hidden');
            bar.max = e.total;
            bar.value = e.loaded;
        },

        progress: function (e) {

            bar.max = e.total;
            bar.value = e.loaded;
        },

        loadEnd: function (e) {

            bar.max = e.total;
            bar.value = e.loaded;
        },

        completeAll: function () {


            const response = JSON.parse(arguments[0].response);

            if (response.success) {
                setTimeout(function () {
                    bar.setAttribute('hidden', 'hidden');
                }, 1000);
                emitter.emit('p2s_product_file_upload', response.data);
                UIkit.notification({
                    message: 'Uploaded',
                    status: 'success',
                    pos: 'top-right',
                    timeout: 5000
                });
            } else {
                UIkit.notification({
                    message: 'There was an error',
                    status: 'danger',
                    pos: 'top-right',
                    timeout: 5000
                });
            }
        }


    });

</script>
