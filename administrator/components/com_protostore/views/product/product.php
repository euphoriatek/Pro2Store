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


$item = $vars['item'];


?>


<div id="p2s_product_form">
    <form @submit.prevent="saveItem">
        <div class="uk-margin-left">
            <div class="uk-grid" uk-grid="">
                <div class="uk-width-1-1">

                    <div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; offset: 31">

                        <nav class="uk-navbar-container uk-padding-small" uk-navbar style="border-radius: 8px">

                            <div class="uk-navbar-left">

                                <span class="uk-navbar-item uk-logo">
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

					<?= LayoutHelper::render('product/card_options', array(
						'form'             => $vars['form'],
						'cardTitle'        => 'COM_PROTOSTORE_ADD_PRODUCT_OPTIONS',
						'cardStyle'        => 'default',
						'cardId'           => 'options',
						'fields'           => array('options'),
						'field_grid_width' => '1-1',
					)); ?>

					<?= LayoutHelper::render('product/card_variant', array(
						'form'             => $vars['form'],
						'cardTitle'        => 'COM_PROTOSTORE_ADD_PRODUCT_VARIANTS',
						'cardStyle'        => 'default',
						'cardId'           => 'variants',
						'fields'           => array('variants'),
						'field_grid_width' => '1-1',
					)); ?>

					<?= LayoutHelper::render('product/card_custom_fields', array(
						'form'      => $vars['form'],
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


    <div id="advancedOptions" class="uk-modal-container" uk-modal>
        <div class="uk-modal-dialog">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h2 class="uk-modal-title">Advanced Options</h2>
            </div>
            <div class="uk-modal-body">
                <div class="uk-grid uk-child-width-1-3@m">

                    <div>
                        <div class="uk-card uk-card-default">
                            <div class="uk-card-header">
                                <h5>Variants</h5>
                            </div>
                            <div class="uk-card-body">
                                <button type="button" class="uk-button uk-button-primary uk-width-1-1 uk-margin-bottom">
                                    Copy Variants <span
                                            uk-icon="icon: copy"></span></button>
                            </div>

                        </div>
                    </div>
                    <div>
                        <div class="uk-card uk-card-default">
                            <div class="uk-card-header">
                                <h5>Data</h5>
                            </div>
                            <div class="uk-card-body">
                                <button type="button" class="uk-button uk-button-primary uk-width-1-1 uk-margin-bottom">
                                    Export Data <span
                                            uk-icon="icon: copy"></span></button>
                                <button type="button" class="uk-button uk-button-primary uk-width-1-1 uk-margin-bottom">
                                    Import Data <span
                                            uk-icon="icon: copy"></span></button>
                            </div>

                        </div>
                    </div>
                    <div>
                        <div class="uk-card uk-card-default">
                            <div class="uk-card-header">
                                <h5>Variants</h5>
                            </div>
                            <div class="uk-card-body">
                                <button type="button" class="uk-button uk-button-primary uk-width-1-1 uk-margin-bottom">
                                    Copy Variants <span
                                            uk-icon="icon: copy"></span></button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            </div>
        </div>
    </div>
</div>


<script>
    const p2s_product_form = {
        data() {
            return {
                base_url: '',
                form: {
                    itemid: '',
                    jform_title: '',
                    jform_short_description: '',
                    jform_long_description: '',
                    jform_access: '',
                    jform_base_price: '',
                    jform_sku: '',
                    jform_category: '',
                    jform_manage_stock: true,
                    jform_stock: '',
                    jform_featured: false,
                    jform_state: false,
                    jform_taxable: false,
                    jform_show_discount: false,
                    jform_discount: '',
                    jform_teaserimage: '',
                    jform_fullimage: '',
                    jform_shipping_mode: '',
                    jform_flatfee: '',
                    jform_publish_up_date: '',
                    jform_product_type: '',
                    jform_tags: [],
                    jform_options: [],
                    jform_variants: [],
                    variantLabels: [],
                    variantsList: [],
                    variantsListLocal: [],
                    files: []
                },
                product_id: 0,
                product_type: 1,
                available_custom_fields: [],
                available_tags: [],
                available_options: [],
                option_for_edit: [],
                p2s_currency: [],
                p2s_local: '',
                andClose: false,
                variantsSet: false,
                successMessage: '',
                file_for_edit: {},
                newOptionTypeName: '',
                newOptionTypeType: 'Dropdown',
                showNewOptionTypeNameWarning: false,
            }

        },
        created() {
            emitter.on('p2s_product_file_upload', this.fileUploaded)
        },
        mounted() {
            // console.log(this.form.variantsListLocal);
            // if (this.form.jform_variants.length > 0) {
            //     this.variantsSet = true;
            // }
            //
            // if (this.form.variantLabels.length > 0) {
            //     this.runCartesian();
            // }
        },
        computed: {
            showVariantItemsBlock() {

                if (this.form.variantsListLocal.length > 0) {
                    return true;
                } else {
                    return false;
                }

            },
            sellPrice() {

                const options = {
                    maximumFractionDigits: 2,
                    currency: this.p2s_currency.iso,
                    style: "currency",
                    currencyDisplay: "symbol"
                }

                return this.localStringToNumber(this.form.jform_base_price - this.form.jform_discount).toLocaleString(undefined, options);

            },
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
            try {
                this.base_url = base_url.innerText;
                // base_url.remove();
            } catch (err) {
            }

            const jform_title = document.getElementById('jform_title_data');
            try {
                this.form.jform_title = jform_title.innerText;
                // jform_title.remove();
            } catch (err) {
            }

            const itemid = document.getElementById('jform_joomla_item_id_data');
            try {
                this.form.itemid = itemid.innerText;
                // itemid.remove();
            } catch (err) {
            }

            const productid = document.getElementById('jform_product_id_data');
            try {
                this.product_id = productid.innerText;
                // itemid.remove();
            } catch (err) {
            }

            const product_type = document.getElementById('jform_product_type_data');
            try {
                this.form.jform_product_type = product_type.innerText;
                // product_type.remove();
            } catch (err) {
                this.form.jform_product_type = 1;
            }

            const jform_manage_stock = document.getElementById('jform_manage_stock_data');
            try {
                this.form.jform_manage_stock = (jform_manage_stock.innerText == 1);
                //  jform_manage_stock.remove();
            } catch (err) {
            }

            const jform_stock = document.getElementById('jform_stock_data');
            try {
                this.form.jform_stock = jform_stock.innerText;
                //  jform_stock.remove();
            } catch (err) {
            }

            const jform_taxable = document.getElementById('jform_taxable_data');
            try {
                this.form.jform_taxable = (jform_taxable.innerText == 1);
                //  jform_taxable.remove();
            } catch (err) {
            }

            const jform_show_discount = document.getElementById('jform_show_discount_data');
            try {
                this.form.jform_show_discount = (jform_show_discount.innerText == 1);
                //   jform_show_discount.remove();
            } catch (err) {
            }

            const jform_sku = document.getElementById('jform_sku_data');
            try {
                this.form.jform_sku = jform_sku.innerText;
                //  jform_sku.remove();
            } catch (err) {
            }

            const jform_category = document.getElementById('jform_catid_data');
            try {
                this.form.jform_category = jform_category.innerText;
                //  jform_category.remove();
            } catch (err) {
            }

            const jform_featured = document.getElementById('jform_featured_data');
            try {
                this.form.jform_featured = (jform_featured.innerText == 1);
                //  jform_featured.remove();
            } catch (err) {
            }

            const jform_state = document.getElementById('jform_state_data');
            try {
                this.form.jform_state = (jform_state.innerText == 1);
                //  jform_state.remove();
            } catch (err) {
            }

            const jform_shipping_mode = document.getElementById('jform_shipping_mode_data');
            try {
                this.form.jform_shipping_mode = jform_shipping_mode.innerText;
                //  jform_shipping_mode.remove();
            } catch (err) {
            }

            const jform_flatfee = document.getElementById('jform_flatfee_data');
            try {
                this.form.jform_flatfee = jform_flatfee.innerText / 100
                //  jform_flatfee.remove();
            } catch (err) {
            }

            const jform_base_price = document.getElementById('jform_base_price_data');
            try {
                this.form.jform_base_price = jform_base_price.innerText / 100;
                //  jform_base_price.remove();
            } catch (err) {
            }

            const jform_discount = document.getElementById('jform_discount_formatted_data');
            try {
                this.form.jform_discount = parseFloat(jform_discount.innerText);
                //  jform_discount.remove();
            } catch (err) {
            }
            const jform_tags = document.getElementById('jform_tags_data');
            try {
                this.form.jform_tags = JSON.parse(jform_tags.innerText);
                //  jform_tags.remove();
            } catch (err) {
            }


            const jform_options = document.getElementById('jform_options_data');
            try {

                if (jform_options.innerText === 'false') {
                    this.form.jform_options = [];
                } else {
                    this.form.jform_options = JSON.parse(jform_options.innerText);
                }


            } catch (err) {
                this.form.jform_options = [];
            }

            const available_custom_fields = document.getElementById('available_custom_fields_data');
            try {
                this.available_custom_fields = JSON.parse(available_custom_fields.innerText);
                //  available_custom_fields.remove();
            } catch (err) {
            }
            const available_options = document.getElementById('available_options_data');
            try {
                this.available_options = JSON.parse(available_options.innerText);
                //  available_options.remove();
            } catch (err) {
            }

            const jform_variants = document.getElementById('jform_variants_data');
            try {
                if (jform_variants.innerText != 'null') {
                    this.form.jform_variants = JSON.parse(jform_variants.innerText);
                } else {
                    this.form.jform_variants = new Array(0);
                }

                //  jform_variants.remove();
            } catch (err) {
                this.form.jform_variants = new Array(0);
            }


            const jform_variantLabels = document.getElementById('jform_variantLabels_data');
            try {

                if (jform_variantLabels.innerText != 'null') {
                    this.form.variantLabels = JSON.parse(jform_variantLabels.innerText);
                } else {
                    this.form.jform_variants = new Array(0);
                }
                // jform_variantLabels.remove();
            } catch (err) {
                this.form.variantLabels = new Array(0);
            }


            const jform_variantsListLocal = document.getElementById('jform_variantList_data');
            try {

                if (jform_variantLabels.innerText != 'null') {
                    this.form.variantsListLocal = JSON.parse(jform_variantsListLocal.innerText);
                } else {
                    this.form.variantsListLocal = new Array(0);
                }
                //  jform_variantsListLocal.remove();
            } catch (err) {
                this.form.variantsListLocal = new Array(0);
            }

            const p2s_currency = document.getElementById('currency');
            try {
                this.p2s_currency = JSON.parse(p2s_currency.innerText);
                // p2s_currency.remove();
            } catch (err) {
            }


            const p2s_locale = document.getElementById('locale');
            try {
                this.p2s_local = p2s_locale.innerText;
                // p2s_local.remove();
            } catch (err) {
            }

            const available_tags = document.getElementById('available_tags_data');
            try {
                this.available_tags = JSON.parse(available_tags.innerText);
                // available_tags.remove();
            } catch (err) {
            }

            const files = document.getElementById('jform_files_data');
            try {
                this.form.files = JSON.parse(files.innerText);
                // files.remove();
            } catch (err) {
            }

            const successMessage = document.getElementById('successMessage');
            try {
                this.successMessage = successMessage.innerText;
                // successMessage.remove();
            } catch (err) {
            }

        },
        methods: {


            logIt() {
                console.log(this.available_custom_fields);
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
                this.form.jform_variants.push('');
            },
            removeVariant(i) {
                this.form.jform_variants.splice(i, 1);
            },
            async variantsStartOver() {

                await UIkit.modal.confirm('Are You sure? This will reset all variant data!');

                this.form.jform_variants = [];
                this.form.variantsList = [];
                this.form.variantsListLocal = [];
                this.form.variantLabels = [];

            },
            async editVariantValues() {

                await UIkit.modal.confirm('Are You sure? This will reset all variant data!');

                this.form.variantsList = [];
                this.form.variantsListLocal = [];

            },
            runCartesian() {
                this.form.variantsListLocal = [];
                this.form.variantsList = this.cartesianProduct(this.form.variantLabels);

                this.form.variantsList.forEach((variant, index) => {

                    let newName = variant.join(' / ');
                    let itsDefault = false;
                    if (index === 0) {
                        itsDefault = true;
                    }
                    this.form.variantsListLocal.push({
                        identifier: variant,
                        name: newName,
                        active: true,
                        default: itsDefault,
                        price: this.form.base_price,
                        stock: 0,
                        sku: '',
                    })
                });

                if (this.form.variantsListLocal.length > 0) {
                    this.showVariantItemsBlock = true;
                }

            },
            cartesianProduct(arr) {
                return arr.reduce(function (a, b) {
                    return a.map(function (x) {
                        return b.map(function (y) {
                            return x.concat([y]);
                        })
                    }).reduce(function (a, b) {
                        return a.concat(b)
                    }, [])
                }, [[]])
            },
            setVariantDefault(itemIndex) {

                this.form.variantsListLocal.forEach((variant, index) => {
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
                this.form.variantsListLocal.forEach((variant, index) => {
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
             * OPTIONS
             */

            addNewOptionType() {
                UIkit.modal("#addOptionTypeModal").show();
            },
            async saveNewOptionType() {
                if (this.newOptionTypeName === '') {
                    this.showNewOptionTypeNameWarning = true;
                    return;
                }

                const params = {
                    'optionTypeName': this.newOptionTypeName,
                    'optionType': this.newOptionTypeType,

                };

                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.createoptiontype&format=raw", {
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
                    UIkit.modal("#addOptionTypeModal").hide();
                    this.updateOptionType();


                } else {
                    UIkit.notification({
                        message: 'There was an error.',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }


            },
            async updateOptionType() {
                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.getoptiontypes&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: ''
                });

                const response = await request.json();

                if (response.success) {
                    this.available_options = response.data;

                } else {
                    UIkit.notification({
                        message: 'There was an error.',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }
            },
            addOptionOfType(i) {
                this.form.jform_options.push({
                    optiontype: this.available_options[i].id,
                    optiontypename: this.available_options[i].name,
                    modifier: '',
                    modifiervalue_translated: '',
                    optionsku: '',
                })
            },
            openEditoptionModal(option) {
                this.option_for_edit = option;
                UIkit.modal('#editProductOption').show();
            },
            processModifierValue(option) {
                if (option.modifiertype === "perc") {
                    option.modifiervalue_translated = option.modifiervalueFloat + '%';
                } else if (option.modifiertype === "amount") {
                    const formatter = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: this.p2s_currency.iso
                    });
                    option.modifiervalue_translated = formatter.format(option.modifiervalueFloat); /* $2,500.00 */
                }
            },
            async removeOption(i) {
                // todo - translate
                await UIkit.modal.confirm('Are you sure?');
                this.form.jform_options.splice(i, 1);
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
                    'options': JSON.stringify(this.form.jform_options),
                    'variants': JSON.stringify(this.form.jform_variants),
                    'variantLabels': JSON.stringify(this.form.variantLabels),
                    'variantList': JSON.stringify(this.form.variantsListLocal)
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
                        pos: 'top-center',
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
