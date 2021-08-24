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
use Joomla\CMS\Layout\LayoutHelper;

?>

<div id="p2s_discounts">
    <div class="uk-margin-left">
        <div class="uk-grid" uk-grid="">
            <div class="uk-width-3-4">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        <div class="uk-grid uk-grid-small">
                            <div class="uk-width-expand">
                                <h3>
                                    <svg width="16px" aria-hidden="true" focusable="false" data-prefix="fal"
                                         data-icon="tags"
                                         class="svg-inline--fa fa-tags fa-w-20" role="img"
                                         xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 640 512">
                                        <path fill="currentColor"
                                              d="M625.941 293.823L421.823 497.941c-18.746 18.746-49.138 18.745-67.882 0l-1.775-1.775 22.627-22.627 1.775 1.775c6.253 6.253 16.384 6.243 22.627 0l204.118-204.118c6.238-6.239 6.238-16.389 0-22.627L391.431 36.686A15.895 15.895 0 0 0 380.117 32h-19.549l-32-32h51.549a48 48 0 0 1 33.941 14.059L625.94 225.941c18.746 18.745 18.746 49.137.001 67.882zM252.118 32H48c-8.822 0-16 7.178-16 16v204.118c0 4.274 1.664 8.292 4.686 11.314l211.882 211.882c6.253 6.253 16.384 6.243 22.627 0l204.118-204.118c6.238-6.239 6.238-16.389 0-22.627L263.431 36.686A15.895 15.895 0 0 0 252.118 32m0-32a48 48 0 0 1 33.941 14.059l211.882 211.882c18.745 18.745 18.745 49.137 0 67.882L293.823 497.941c-18.746 18.746-49.138 18.745-67.882 0L14.059 286.059A48 48 0 0 1 0 252.118V48C0 21.49 21.49 0 48 0h204.118zM144 124c-11.028 0-20 8.972-20 20s8.972 20 20 20 20-8.972 20-20-8.972-20-20-20m0-28c26.51 0 48 21.49 48 48s-21.49 48-48 48-48-21.49-48-48 21.49-48 48-48z">
                                        </path>
                                    </svg>
									<?= Text::_('COM_PROTOSTORE_DISCOUNTS_TITLE'); ?>
                                </h3>
                            </div>
                            <div class="uk-width-auto uk-text-right">
                                <div class="uk-grid uk-grid-small " uk-grid="">
                                    <div class="uk-width-auto uk-grid-item-match uk-flex-middle">
                                        <div class="uk-grid uk-grid-small" uk-grid="">
                                            <div class="uk-width-expand uk-grid-item-match uk-flex-middle ">  <?= Text::_('COM_PROTOSTORE_SHOW_ONLY_PUBLISHED'); ?></div>
                                            <div class="uk-width-auto">
                                                <p-inputswitch v-model="publishedOnly" @change="filter"></p-inputswitch>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-width-auto">

                                        <div class="uk-grid uk-grid-small" uk-grid="">
                                            <div class="uk-width-expand  ">
                                                <input v-model="enteredText"
                                                       @input="doTextSearch($event)"
                                                       type="text"
                                                       placeholder="<?= Text::_('COM_PROTOSTORE_TABLE_SEARCH_PLACEHOLDER'); ?>">
                                            </div>
                                            <div class="uk-width-auto uk-grid-item-match uk-flex-middle">
                                            <span style="width: 20px">
                                            <span @click="cleartext" v-show="enteredText" style="cursor: pointer" uk-icon="icon: close"></span>
                                                </span>
                                            </div>
                                        </div>


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
                                    <input @change="selectAll($event)" type="checkbox">
                                </th>

                                <th class="uk-text-left"><?= Text::_('COM_PROTOSTORE_DISCOUNTS_TABLE_NAME'); ?>
                                    <a href="#" @click="sort('name')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>
                                <th class="uk-text-left"><?= Text::_('COM_PROTOSTORE_DISCOUNTS_TABLE_COUPON_CODE'); ?>
                                    <a href="#" @click="sort('coupon_code')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>

                                <th class="uk-text-left"><?= Text::_('COM_PROTOSTORE_DISCOUNTS_TABLE_DISCOUNT_TYPE'); ?>
                                    <a href="#" @click="sort('discount_type')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>
                                <th class="uk-text-left"><?= Text::_('COM_PROTOSTORE_DISCOUNTS_TABLE_AMOUNT'); ?>
                                    <a href="#" @click="sort('amount')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>
                                <th class="uk-text-left"><?= Text::_('COM_PROTOSTORE_DISCOUNTS_TABLE_EXPIRY_DATE'); ?>
                                    <a href="#" @click="sort('expiry_date')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>

                                <th class="uk-text-left@m uk-text-nowrap">
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr class="el-item" v-for="item in itemsChunked[currentPage]">
                                <td>
                                    <div><input v-model="selected" :value="item" type="checkbox"></div>
                                </td>
                                <td>
                                    <a :href="'index.php?option=com_protostore&view=discount&id=' + item.id">{{item.name}}</a>
                                </td>
                                <td>
                                    {{item.coupon_code}}
                                </td>
                                <td>
                                    {{item.discount_type_string}}
                                </td>
                                <td>
                                    {{item.amount_formatted}}
                                </td>
                                <td>
                                    {{item.expiry_date}}
                                </td>
                                <td class="uk-text-center">
                                  <span v-if="item.published == '1'"
                                        @click="togglePublished(item)"
                                        style="font-size: 18px; color: green; cursor: pointer;">
                                      <i class="fal fa-check-circle"></i>
                                  </span>
                                    <span v-if="item.published == '0'"
                                          @click="togglePublished(item)"
                                          style="font-size: 18px; color: red; cursor: pointer;">
								            <i class="fal fa-times-circle"></i>
								      </span>
                                </td>


                            </tr>


                            </tbody>

                        </table>


                    </div>
                    <div class="uk-card-footer">
                        <div class="uk-grid uk-grid-small">
                            <div class="uk-width-expand">
                                <p class="uk-text-meta">

                                </p>
                            </div>
                            <div class="uk-width-auto">
								<?= LayoutHelper::render('pagination'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-4">
                <div>
                    <div class="uk-card uk-card-default ">

                        <div class="uk-card-header">
                            <h4> Controls</h4>
                        </div>
                        <div class="uk-card-body">
                            <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>

                                <li>
                                    <a class="uk-text-emphasis" href="index.php?option=com_protostore&view=discount">
                                        <span class="uk-margin-small-right" uk-icon="icon: plus-circle"></span>
                                        Add Discount
                                    </a>
                                </li>

                                <li class="uk-nav-divider"></li>
                                <li>
                                    <a  @click="trashSelected"  :class="[selected.length == 0 ? 'uk-disabled' : ' uk-text-bold uk-text-emphasis']">
                                        <span class="uk-margin-small-right" uk-icon="icon: trash"></span>
                                        Trash Selected
                                    </a>
                                </li>
                                <li>
                                    <a  @click="toggleSelected"  :class="[selected.length == 0 ? 'uk-disabled' : ' uk-text-bold uk-text-emphasis']">
                                        <span class="uk-margin-small-right" uk-icon="icon: check"></span>
                                        Toggle Published
                                    </a>

                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
