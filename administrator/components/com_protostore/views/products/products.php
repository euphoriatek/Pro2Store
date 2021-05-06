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
use Joomla\CMS\Uri\Uri;

// init vars
$id = uniqid('p2s_products');


?>


<div id="<?= $id; ?>">
    <div class="uk-margin-left">
        <div class="uk-grid" uk-grid="">
            <div class="uk-width-3-4">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        <div class="uk-grid uk-grid-small">
                            <div class="uk-width-expand">
                                <h3>
                                    <svg width="16px" class="svg-inline--fa fa-boxes fa-w-16" aria-hidden="true"
                                         focusable="false"
                                         data-prefix="fad"
                                         data-icon="boxes" role="img" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 576 512"
                                         data-fa-i2svg="">
                                        <g class="fa-group">
                                            <path class="fa-secondary" fill="currentColor"
                                                  d="M480 288v96l-32-21.3-32 21.3v-96zM320 0v96l-32-21.3L256 96V0zM160 288v96l-32-21.3L96 384v-96z">
                                            </path>
                                            <path class="fa-primary" fill="currentColor"
                                                  d="M560 288h-80v96l-32-21.3-32 21.3v-96h-80a16 16 0 0 0-16 16v192a16 16 0 0 0 16 16h224a16 16 0 0 0 16-16V304a16 16 0 0 0-16-16zm-384-64h224a16 16 0 0 0 16-16V16a16 16 0 0 0-16-16h-80v96l-32-21.3L256 96V0h-80a16 16 0 0 0-16 16v192a16 16 0 0 0 16 16zm64 64h-80v96l-32-21.3L96 384v-96H16a16 16 0 0 0-16 16v192a16 16 0 0 0 16 16h224a16 16 0 0 0 16-16V304a16 16 0 0 0-16-16z">
                                            </path>
                                        </g>
                                    </svg> &nbsp; <?= Text::_('COM_PROTOSTORE_PRODUCTS_TITLE'); ?></h3>
                            </div>
                            <div class="uk-width-auto uk-text-right">
                                <div class="uk-grid uk-grid-small" uk-grid="">
                                    <div class="uk-width-auto">
                                        <input  @input="doTextSearch($event)" type="text" placeholder="Search...">
                                    </div>
                                    <div class="uk-width-auto">
                                        <select class="uk-select" v-model="selectedCategory" @change="filter">
                                            <option value="0"> -- Filter Category --</option>
                                            <option v-for="category in categories" :value="category.id">
                                                {{category.title}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="uk-card-body">

                        <table class="uk-table uk-table-striped uk-table-divider uk-table-hover uk-table-responsive  uk-table-middle">
                            <thead>
                            <tr>

                                <th class="uk-text-left">
                                </th>
                                <th class="uk-text-left">Name
                                    <a href="#" @click="sort('title')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>
                                <th class="uk-text-left">Image
                                </th>
                                <th class="uk-text-left">Category
                                    <a href="#" @click="sort('category')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                <th class="uk-text-left">Base Price
                                    <a href="#" @click="sort('base_price')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>

                                <th class="uk-text-left">Stock
                                    <a href="#" @click="sort('stock')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>
                                <th class="uk-text-left">Published
                                    <a href="#" @click="sort('published')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>

                                <th class="uk-text-left@m uk-text-nowrap">
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr class="el-item" v-for="product in productsChunked[currentPage]">
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <a :href="'index.php?option=com_protostore&view=product&id=' + product.joomla_item_id">{{product.title}}</a>
                                </td>
                                <td>
                                    <div><img :src="product.teaserImagePath" width="100"/></div>
                                </td>
                                <td>
                                    <div>{{product.category}}</div>
                                </td>
                                <td>
                                    <div>{{product.baseprice_formatted}}</div>
                                </td>
                                <td>
                                    <div>{{product.stock}}</div>
                                </td>
                                <td class="uk-text-center">
                                  <span v-if="product.published == '1'" class="yps_currency_published_icon" @click=""
                                        style="font-size: 18px; color: green; cursor: pointer;">
                                      <i class="fal fa-check-circle"></i>
                                  </span>
                                    <span id="unpublished{{product.itemid}}"
                                          v-if="product.published == '0'"
                                          class="yps_currency_published_icon"
                                          @click=""
                                          style="font-size: 18px; color: red; cursor: pointer;">
                                        <i class="fal fa-times-circle"></i>
                                    </span>
                                </td>


                            </tr>


                            </tbody>

                        </table>


                    </div>
                    <div class="uk-card-footer"></div>
                </div>
            </div>
            <div class="uk-width-1-4">
                <div>
                    <div class="uk-card uk-card-default" uk-sticky="offset: 100">
                        <div class="uk-card-header">
                            <h3>Options</h3>
                        </div>
                        <div class="uk-card-body">
                            <button @click="newProduct"
                                    class="uk-button uk-button-primary"><?= Text::_('COM_PROTOSTORE_ADD_PRODUCT_TITLE'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<script>

    const <?= $id; ?> = {
        data() {
            return {
                products: <?= json_encode($vars['items']); ?>,
                productsChunked: [],
                categories: <?= json_encode($vars['categories']); ?>,
                selectedCategory: '',
                currentSort: 'title',
                currentSortDir: 'asc',
                currentPage: 0,
                pages: [],
                pagesizes: [5, 10, 20, 30, 50, 100],
                show: 25,
            };
        },
        mounted: function () {
            this.changeShow();
        },
        computed: {},

        methods: {

            async updateList() {
                const request = await fetch("index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=products.updatelist&format=raw&limit=0", {
                    method: 'post'
                });

                const response = await request.json();

                if (response.success) {


                } else {
                    UIkit.notification({
                        message: 'There was an error.',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }
            },
            async filter() {

                this.loading = true;

                const params = {
                    'limit': this.show,
                    'offset': (this.currentPage * this.show),
                    'category': this.selectedCategory,
                    'searchTerm': this.enteredText,
                };

                const URLparams = this.serialize(params);

                const request = await fetch('<?= Uri::base(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=products.filter&format=raw&' + URLparams, {method: 'post'});

                const response = await request.json();

                if (response.success) {
                    this.products = response.data.products;
                    this.loading = false;

                    if (this.products) {
                        this.changeShow();
                    } else {
                        this.productsChunked = [];
                        this.pages = 1;
                        this.currentPage = 0;
                    }
                }

            },
            changeShow() {

                this.productsChunked = this.products.reduce((resultArray, item, index) => {
                    const chunkIndex = Math.floor(index / this.show)
                    if (!resultArray[chunkIndex]) {
                        resultArray[chunkIndex] = []
                    }
                    resultArray[chunkIndex].push(item)
                    return resultArray
                }, []);
                this.pages = this.productsChunked.length;
                this.currentPage = 0;
                console.log(this.productsChunked);
            },
            changePage(i) {
                this.currentPage = i;
            },
            async doTextSearch(event) {
                this.enteredText = null
                clearTimeout(this.debounce)
                this.debounce = setTimeout(() => {
                    this.enteredText = event.target.value
                    this.filter();
                }, 600)
            },
            sort(s) {
                //if s == current sort, reverse
                if (s === this.currentSort) {
                    this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
                }
                this.currentSort = s;
                return this.productsChunked[this.currentPage].sort((a, b) => {
                    let modifier = 1;
                    if (this.currentSortDir === 'desc') modifier = -1;
                    if (a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
                    if (a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
                    return 0;
                });
            },
            serialize(obj) {
                var str = [];
                for (var p in obj)
                    if (obj.hasOwnProperty(p)) {
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    }
                return str.join("&");
            }

        }
    }

    Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>')


</script>
