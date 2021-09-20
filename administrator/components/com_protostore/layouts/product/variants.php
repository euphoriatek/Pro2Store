<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
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
                    <a href="" class="uk-icon-button" uk-icon="info"></a>

                </div>
            </div>
        </div>

        <div class="uk-card-body">
            <div class="uk-card uk-card-body uk-card-default uk-margin-small-bottom"
                 v-for="(variant, index) in form.jform_variants">
                <div class="uk-position-absolute uk-position-top-right uk-margin-small-right uk-margin-small-top">
                    <span style="cursor: pointer; color: red;"  uk-icon="icon: minus-circle"
                          @click="removeVariant(index)"></span>
                </div>
                <div class="uk-grid" uk-grid>
                    <div class="uk-width-1-1 uk-grid-item-match uk-flex-middle">


                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Variant Type</label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-form-large" placeholder="Product Variant e.g. 'Colour' 'Size'"
                                       v-model="form.jform_variants[index].name">
                            </div>
                        </div>

                    </div>
                    <div class="uk-width-1-1">


                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Variant Options</label>
                            <div class="uk-form-controls">
                                <p-chips v-model="form.jform_variants[index].labels" @add="onAddNewLabel($event, form.jform_variants[index].id)" @remove="removeLabel($event, index, form.jform_variants[index].id)" :addOnBlur="true" :allowDuplicate="false" >
                                    <template #chip="slotProps">
                                        {{slotProps.value.name}}
                                    </template>
                                </p-chips>
                            </div>
                        </div>




                    </div>
                </div>
            </div>
            <div class="uk-grid" uk-grid>
                <div class="uk-width-expand"></div>
                <div class="uk-width-auto">
                    <button type="button"
                            class="uk-button uk-button-small uk-button-default button-success"
                            @click="addVariant">Add Variant Type
                        <span uk-icon="icon: plus-circle"></span>
                    </button>
                </div>
            </div>
        </div>


<!--        <div class="uk-card-footer">-->
<!--            <div class="uk-grid" uk-grid>-->
<!--                <div class="uk-width-expand"></div>-->
<!--                <div class="uk-width-auto">-->
<!--                    <button type="button"-->
<!--                            class="uk-button uk-button-small uk-button-primary" @click="setVariants">Set Variant Values-->
<!--                        <span uk-icon="icon: check"></span>-->
<!--                    </button>-->
<!--                </div>-->
<!--                <div class="uk-width-auto">-->
<!--                    <button type="button"-->
<!--                            class="uk-button uk-button-small uk-button-primary" @click="refreshVariants">Refresh Values-->
<!--                        <span uk-icon="icon: check"></span>-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>


    <div class="uk-card uk-card-default uk-margin-bottom">
        <div class="uk-card-header">
            <div class="uk-grid uk-grid-small">
                <div class="uk-width-expand">
                    <h5>
					  Variant Combination List
                    </h5>
                </div>
                <div class="uk-width-auto">
                    <span v-show="!variants_loading" :class="[setSavedClass ? 'uk-text-meta uk-text-success uk-animation-fade' : 'uk-animation-fade uk-hidden']">saved!</span>
                    <span v-show="variants_loading" class="uk-text-meta  uk-text-warning"  >
                    Saving
                  <i class="fal fa-spinner-third fa-spin fa-sm"></i>
              </span>
                </div>
            </div>
        </div>
        <div class="uk-card-body">
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
                                   v-model="item.price" :placeholder="form.jform_base_price" @input="updateVariantValues"></p-inputnumber>
                </td>
                <td class="">
                    <input class="uk-input uk-form-small " type="number" v-model="item.stock" placeholder="Stock" @input="updateVariantValues">
                </td>
                <td class="">
                    <input class="uk-input uk-form-small uk-width-3-5" type="text" v-model="item.sku" placeholder="SKU" @input="updateVariantValues">
                </td>
                <td class="">
                    <p-inputswitch v-model="item.active" @change="checkVariantDefault(index);updateVariantValues()" ></p-inputswitch>
                </td>
                <td class="">
                    <p-inputswitch v-model="item.default" @change="setVariantDefault(index);updateVariantValues()" ></p-inputswitch>
                </td>
            </tr>
            </tbody>
        </table>
        </div>
    </div>
</div>
