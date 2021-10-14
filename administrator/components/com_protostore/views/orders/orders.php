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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

/** @var array $vars */

?>


<div id="p2s_orders">
    <div class="uk-margin-left">
        <div class="uk-grid" uk-grid="">
            <div class="uk-width-3-4">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        <div class="uk-grid uk-grid-small">
                            <div class="uk-width-expand">
                                <h3>
                                    <svg width="18px" aria-hidden="true" focusable="false" data-prefix="fal"
                                         data-icon="box-check" role="img" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 640 512" class="svg-inline--fa fa-box-check fa-w-16">
                                        <path _ngcontent-yfu-c67="" fill="currentColor"
                                              d="M492.5 133.4L458.9 32.8C452.4 13.2 434.1 0 413.4 0H98.6c-20.7 0-39 13.2-45.5 32.8L2.5 184.6c-1.6 4.9-2.5 10-2.5 15.2V464c0 26.5 21.5 48 48 48h400c106 0 192-86 192-192 0-90.7-63-166.5-147.5-186.6zM272 32h141.4c6.9 0 13 4.4 15.2 10.9l28.5 85.5c-3-.1-6-.5-9.1-.5-56.8 0-107.7 24.8-142.8 64H272V32zM83.4 42.9C85.6 36.4 91.7 32 98.6 32H240v160H33.7L83.4 42.9zM48 480c-8.8 0-16-7.2-16-16V224h249.9c-16.4 28.3-25.9 61-25.9 96 0 66.8 34.2 125.6 86 160H48zm400 0c-88.2 0-160-71.8-160-160s71.8-160 160-160 160 71.8 160 160-71.8 160-160 160zm64.6-221.7c-3.1-3.1-8.1-3.1-11.2 0l-69.9 69.3-30.3-30.6c-3.1-3.1-8.1-3.1-11.2 0l-18.7 18.6c-3.1 3.1-3.1 8.1 0 11.2l54.4 54.9c3.1 3.1 8.1 3.1 11.2 0l94.2-93.5c3.1-3.1 3.1-8.1 0-11.2l-18.5-18.7z"></path>
                                    </svg>&nbsp;
									<?= Text::_('COM_PROTOSTORE_ORDERS'); ?>
                                </h3>
                            </div>

                            <div class="uk-width-auto uk-text-right">
                                <div class="uk-grid uk-grid-small " uk-grid="">

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
                                            <span @click="cleartext" v-show="enteredText" style="cursor: pointer"
                                                  uk-icon="icon: close"></span>
                                                </span>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="uk-width-auto">
                                        <select class="uk-select" v-model="selectedStatus" @change="filter">
                                            <option value="0">
                                                -- <?= Text::_('COM_PROTOSTORE_ORDERS_SELECT_A_STATUS'); ?> --
                                            </option>
                                            <option v-for="status in statuses" :value="status.id">
                                                {{status.title}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="uk-width-auto">

                                        <button class="uk-icon-button" :class="{ 'uk-button-primary': dateActive }"
                                                uk-icon="calendar"></button>
                                        <div id="ordersDateDrop" class="uk-width-xlarge"
                                             uk-drop="mode: click; pos: bottom-right; boundary: .boundary">
                                            <div class="uk-card uk-card-body uk-card-default">

                                                <div class="uk-grid" uk-grid>

                                                    <div class="uk-width-1-2">
                                                        <div class="uk-margin">
                                                            <label class="uk-form-label" for="date_from"><?= Text::_('COM_PROTOSTORE_DATE_FROM'); ?></label>
                                                            <div class="uk-form-controls">
                                                                <input type="date" id="date_from" v-model="dateFrom"
                                                                       value="<?= HtmlHelper::date($vars['now'], 'Y-m-d'); ?>"
                                                                       min="2020-01-01">
                                                            </div>
                                                        </div>
                                                        <div class="uk-text-left">
                                                            <?= Text::_('COM_PROTOSTORE_PREVIOUS'); ?>:<br/>
                                                            <button type="button"
                                                                    class="uk-button uk-button-default uk-button-small"
                                                                    @click="setDateBand(7)"> <?= Text::_('COM_PROTOSTORE_PREVIOUS_7_DAYS'); ?>
                                                            </button>
                                                            <button type="button"
                                                                    class="uk-button uk-button-default uk-button-small uk-margin-small-left"
                                                                    @click="setDateBand(30)"> <?= Text::_('COM_PROTOSTORE_PREVIOUS_30_DAYS'); ?>
                                                            </button>

                                                        </div>
                                                    </div>
                                                    <div class="uk-width-1-2">
                                                        <div class="uk-margin">
                                                            <label class="uk-form-label" for="date_to"><?= Text::_('COM_PROTOSTORE_DATE_TO'); ?></label>
                                                            <div class="uk-form-controls">
                                                                <input type="date" id="date_to" name="date_to"
                                                                       v-model="dateTo"
                                                                       value="<?= HtmlHelper::date($vars['now'], 'Y-m-d'); ?>"
                                                                       min="2020-01-01">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="uk-width-1-1">
                                                        <div class="uk-grid" uk-grid>
                                                            <div class="uk-width-expand">
                                                                <div class="uk-margin">
                                                                    <button class="uk-button uk-button-small uk-button-default"
                                                                            @click="clearDates"><?= Text::_('COM_PROTOSTORE_TABLE_CLEAR_SEARCH'); ?>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="uk-width-auto">
                                                                <div class="uk-margin">
                                                                    <button class="uk-button uk-button-small uk-button-primary"
                                                                            @click="filter"><?= Text::_('COM_PROTOSTORE_TABLE_SEARCH_PLACEHOLDER'); ?>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>


                                                </div>


                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="uk-card-body">

                        <table v-show="itemsChunked.length > 0"
                               class="uk-table uk-table-striped uk-table-divider uk-table-hover uk-table-responsive  uk-table-middle">
                            <thead>
                            <tr>

                                <th class="uk-text-left">
                                </th>
                                <th class="uk-text-left"><?= Text::_('COM_PROTOSTORE_ORDERS_TABLE_ORDER_NUMBER'); ?>
                                    <a href="#" @click="sort('order_number')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>
                                <th class="uk-text-left"><?= Text::_('COM_PROTOSTORE_ORDERS_TABLE_CUSTOMER'); ?>
                                    <a href="#" @click="sort('customer')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>
                                <th class="uk-text-left"><?= Text::_('COM_PROTOSTORE_ORDERS_TABLE_STATUS'); ?>
                                    <a href="#" @click="sort('status')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                <th class="uk-text-left"><?= Text::_('COM_PROTOSTORE_ORDERS_TABLE_DATE'); ?>
                                    <a href="#" @click="sort('order_date')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>

                                <th class="uk-text-left"><?= Text::_('COM_PROTOSTORE_ORDERS_TABLE_PAID'); ?>
                                    <a href="#" @click="sort('paid')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>
                                <th class="uk-text-left"><?= Text::_('COM_PROTOSTORE_ORDERS_TABLE_TOTAL'); ?>
                                    <a href="#" @click="sort('order_total')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>

                                <th class="uk-text-left@m uk-text-nowrap">
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="order in itemsChunked[currentPage]">
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <a :href="'index.php?option=com_protostore&view=order&id=' + order.id">{{order.order_number}}</a>
                                </td>
                                <td>
                                    {{order.customer_name}}
                                </td>
                                <td>
                                    <div :class="'uk-label uk-label-'+ order.order_status.toLowerCase()">
                                        {{order.order_status_formatted}}
                                    </div>
                                </td>
                                <td>
                                    <div>{{order.order_date}}</div>
                                </td>
                                <td>
                                    <span v-if="order.order_paid == '1'"
                                          @click="togglePaid(order)"
                                          style="font-size: 18px; color: green; cursor: pointer;">
                                      <i class="fal fa-check-circle"></i>
                                  </span>
                                    <span
                                            v-if="order.order_paid == '0'"
                                            @click="togglePaid(order)"
                                            style="font-size: 18px; color: red; cursor: pointer;">
                                        <i class="fal fa-times-circle"></i>
                                    </span>
                                </td>
                                <td>
                                    <div>{{order.order_total_formatted}}</div>
                                </td>
                                <td>

                                </td>


                            </tr>


                            </tbody>

                        </table>
                        <h5 v-show="itemsChunked.length == 0"><?= Text::_('COM_PROTOSTORE_ORDERS_EMPTY_TABLE'); ?></h5>

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
                            <h4> <?= Text::_('COM_PROTOSTORE_FILTERS'); ?></h4>
                        </div>
                        <div class="uk-card-body">
                            <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>

                                <li>
                                    <a class="uk-text-emphasis" @click="setDateBand(1)">
                                        <i class="uk-margin-small-right fal fa-calendar-day fa-2x"></i>
                                        <?= Text::_('COM_PROTOSTORE_PREVIOUS_TODAY'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a class="uk-text-emphasis" @click="setDateBand(7)">
                                        <i class="uk-margin-small-right fal fa-calendar-week fa-2x"></i>
                                        <?= Text::_('COM_PROTOSTORE_PREVIOUS'); ?> <?= Text::_('COM_PROTOSTORE_PREVIOUS_7_DAYS'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a class="uk-text-emphasis" @click="setDateBand(30)">
                                        <i class="uk-margin-small-right fal fa-calendar-alt fa-2x"></i>
                                        <?= Text::_('COM_PROTOSTORE_PREVIOUS'); ?> <?= Text::_('COM_PROTOSTORE_PREVIOUS_30_DAYS'); ?>
                                    </a>
                                </li>
                                <li class="uk-nav-divider"></li>
                                <li>
                                    <a class="uk-text-emphasis" @click="clearSearch">
                                        <span class="uk-margin-small-right" uk-icon="icon: minus-circle"></span>
			                            <?= Text::_('COM_PROTOSTORE_TABLE_CLEAR_SEARCH'); ?>
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
