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

$data = $displayData;

?>

<div class="uk-card uk-card-default  uk-margin-bottom">
    <div class="uk-card-header">
        <div class="uk-grid" uk-grid>
            <div class="uk-width-expand"><h5>Variant Types</h5></div>
            <div class="uk-width-auto">
                <button v-show="!showVariantValuesBlock" type="button"
                        class="uk-button uk-button-small uk-button-default"
                        @click="addVariant">Add Variant
                    <span uk-icon="icon: plus-circle"></span>
                </button>
                <button v-show="showVariantValuesBlock" type="button"
                        class="uk-button uk-button-small uk-button-default"
                        @click="editVariants">Edit Variants
                    <span uk-icon="icon: pencil"></span>
                </button>
            </div>
        </div>
    </div>

    <div class="uk-card-body">
        <div class="uk-grid uk-child-width-1-3" uk-grid v-show="!showVariantValuesBlock">
            <div v-for="(variant, index) in form.jform_variants">
                <div class="uk-grid uk-grid-small uk-flex" uk-grid>
                    <div class="uk-width-expand">
                        <input class="uk-input" type="text"
                               placeholder="Product Variant e.g. 'Colour' 'Size'"
                               v-model="form.jform_variants[index]">
                    </div>
                    <div class="uk-width-auto uk-grid-item-match uk-flex-middle">
                        <span uk-icon="icon: minus-circle" @click="removeVariant(index)"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="uk-grid uk-child-width-1-3" uk-grid v-show="showVariantValuesBlock">
            <ul class="uk-list uk-list-bullet">
                <li v-for="(variant, index) in form.jform_variants">
                    {{variant}}
                </li>
            </ul>
        </div>
    </div>
    <div class="uk-card-footer">
        <div class="uk-grid" uk-grid>
            <div class="uk-width-expand"></div>
            <div class="uk-width-auto">
                <button v-show="!showVariantValuesBlock" type="button"
                        class="uk-button uk-button-small uk-button-primary"
                        @click="setVariants">Set Variants
                    <span uk-icon="icon: check"></span>
                </button>
            </div>
        </div>
    </div>
</div>


<div class="uk-card uk-card-default uk-margin-bottom" v-show="showVariantValuesBlock">


    <div class="uk-card-header">
        <div class="uk-grid" uk-grid>
            <div class="uk-width-expand"><h5>Variant Values</h5></div>
            <div class="uk-width-auto">
                <button v-show="showVariantItemsBlock" type="button"
                        class="uk-button uk-button-small uk-button-default"
                        @click="editVariantValues">Edit Variant Values
                    <span uk-icon="icon: pencil"></span>
                </button>
            </div>
        </div>
    </div>


    <div class="uk-card-body">
        <div class="uk-margin-bottom" v-for="(variant, index) in form.jform_variants" v-show="!showVariantItemsBlock">
            <div class="uk-grid" uk-grid v-show="variant.length > 0">
                <div class="uk-width-1-6 uk-grid-item-match  uk-flex-middle">
                    {{variant}}
                </div>
                <div class="uk-width-expand">
                    <p-chips v-model="form.variantLabels[index]"></p-chips>
                </div>
            </div>
        </div>
        <div class="uk-grid uk-child-width-1-3@m" v-show="showVariantItemsBlock">
            <div v-for="(variant, index) in form.jform_variants">
                <span class="uk-text-bold">{{variant}}</span>
                <ul class="uk-list uk-list-bullet">
                    <li v-for="item in form.variantLabels[index]">{{item}}</li>
                </ul>
            </div>
        </div>
    </div>


    <div class="uk-card-footer">
        <div class="uk-grid" uk-grid>
            <div class="uk-width-expand"></div>
            <div class="uk-width-auto">
                <button type="button" v-show="!showVariantItemsBlock"
                        class="uk-button uk-button-small uk-button-primary" @click="runCartesian">Set Variant Values
                    <span uk-icon="icon: check"></span>
                </button>
            </div>
        </div>
    </div>

</div>


<div class="uk-card uk-card-default uk-card-body uk-margin-bottom" v-show="showVariantItemsBlock">
    <table class="uk-table uk-table-hover uk-table-striped uk-table-divider uk-table-middle">
        <thead>
        <tr>
            <th class="">Variant</th>
            <th class="uk-width-1-5">Price</th>
            <th class="uk-width-1-5">Stock</th>
            <th class="uk-width-1-5">SKU</th>
            <th class="uk-table-shrink">Active</th>
            <th class="uk-table-shrink">Default</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in form.variantsListLocal">
            <td>
                {{item.name}}
            </td>
            <td class="">
                <input class="uk-input" :placeholder="form.jform_base_price" v-model="item.price">
            </td>
            <td class="">
                <input class="uk-input uk-form-small " type="number" v-model="item.stock" placeholder="Stock">
            </td>
            <td class="">
                <input class="uk-input uk-form-small uk-width-3-5" v-model="item.sku" placeholder="SKU">
            </td>
            <td class="">
                <p-inputswitch v-model="item.active"></p-inputswitch>
            </td>
            <td class="">
                <p-inputswitch v-model="item.default"></p-inputswitch>
            </td>
        </tr>
        </tbody>
    </table>

</div>
