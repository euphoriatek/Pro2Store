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


?>
<div v-show="!form.itemid">
    <h5>Please save the product before adding variants </h5>
</div>
<div v-show="form.itemid">
    <div class="uk-card uk-card-default  uk-margin-bottom">
        <div class="uk-card-header">
            <div class="uk-grid" uk-grid>
                <div class="uk-width-expand"><h5>Variant Types</h5></div>
                <div class="uk-width-auto">
                    <button type="button"
                            class="uk-button uk-button-small uk-button-default button-success"
                            @click="addVariant">Add Variant Type
                        <span uk-icon="icon: plus-circle"></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="uk-card-body">
            <div class="uk-card uk-card-body uk-card-default uk-margin-small-bottom"
                 v-for="(variant, index) in form.jform_variants">
                <div class="uk-position-absolute uk-position-top-right uk-margin-small-right uk-margin-small-top">
                    <span style="cursor: pointer; color: red;" uk-icon="icon: minus-circle"
                          @click="removeVariant(index)"></span>
                </div>
                <div class="uk-grid" uk-grid>
                    <div class="uk-width-1-2 uk-grid-item-match uk-flex-middle">

                        <input class="uk-input" type="text"
                               placeholder="Product Variant e.g. 'Colour' 'Size'"
                               v-model="form.jform_variants[index].name">

                    </div>
                    <div class="uk-width-1-2">
                        <p-chips v-model="form.jform_variants[index].labels" @add="addLabel($event, form.jform_variants[index].id)" :addOnBlur="true" :allowDuplicate="false">
                            <template #chip="slotProps">
                                {{slotProps.value.name}}
                            </template>
                        </p-chips>
                    </div>
                </div>
            </div>
        </div>


        <div class="uk-card-footer">
            <div class="uk-grid" uk-grid>
                <div class="uk-width-expand"></div>
                <div class="uk-width-auto">
                    <button type="button"
                            class="uk-button uk-button-small uk-button-primary" @click="setVariants">Set Variant Values
                        <span uk-icon="icon: check"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="uk-card uk-card-default uk-card-body uk-margin-bottom">
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
            <tr v-for="(item, index) in form.jform_variantList">
                <td>
                    {{item.namedLabel}}
                </td>
                <td class="">
                    <p-inputnumber mode="currency" :currency="p2s_currency.iso" :locale="p2s_locale"
                                   v-model="item.price" :placeholder="form.jform_base_price"></p-inputnumber>
                </td>
                <td class="">
                    <input class="uk-input uk-form-small " type="number" v-model="item.stock" placeholder="Stock">
                </td>
                <td class="">
                    <input class="uk-input uk-form-small uk-width-3-5" type="text" v-model="item.sku" placeholder="SKU">
                </td>
                <td class="">
                    <p-inputswitch v-model="item.active" @change="checkVariantDefault(index)"></p-inputswitch>
                </td>
                <td class="">
                    <p-inputswitch v-model="item.default" @change="setVariantDefault(index)"></p-inputswitch>
                </td>
            </tr>
            </tbody>
        </table>

    </div>
</div>
