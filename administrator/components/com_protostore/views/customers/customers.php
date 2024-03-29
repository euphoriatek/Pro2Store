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
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Layout\LayoutHelper;

/** @var array $vars */
?>


<div id="p2s_customers" v-cloak>
    <div class="uk-margin-left">
        <div class="uk-grid" uk-grid="">
            <div class="uk-width-3-4">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        <div class="uk-grid uk-grid-small">
                            <div class="uk-width-expand">
                                <h3>
                                    <svg width="22px" aria-hidden="true" focusable="false" data-prefix="fad"
                                         data-icon="users" role="img" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 640 512" class="svg-inline--fa fa-users fa-w-20">
                                        <g class="fa-group">
                                            <path fill="currentColor"
                                                  d="M96 224a64 64 0 1 0-64-64 64.06 64.06 0 0 0 64 64zm480 32h-64a63.81 63.81 0 0 0-45.1 18.6A146.27 146.27 0 0 1 542 384h66a32 32 0 0 0 32-32v-32a64.06 64.06 0 0 0-64-64zm-512 0a64.06 64.06 0 0 0-64 64v32a32 32 0 0 0 32 32h65.9a146.64 146.64 0 0 1 75.2-109.4A63.81 63.81 0 0 0 128 256zm480-32a64 64 0 1 0-64-64 64.06 64.06 0 0 0 64 64z"
                                                  opacity="0.4" class="fa-secondary"></path>
                                            <path fill="currentColor"
                                                  d="M396.8 288h-8.3a157.53 157.53 0 0 1-68.5 16c-24.6 0-47.6-6-68.5-16h-8.3A115.23 115.23 0 0 0 128 403.2V432a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48v-28.8A115.23 115.23 0 0 0 396.8 288zM320 256a112 112 0 1 0-112-112 111.94 111.94 0 0 0 112 112z"
                                                  class="fa-primary"></path>
                                        </g>
                                    </svg>
									<?= Text::_('COM_PROTOSTORE_CUSTOMERS_TITLE'); ?>
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

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="uk-card-body  uk-animation-fade">

                        <table class="uk-table uk-table-striped uk-table-divider uk-table-hover uk-table-responsive  uk-table-middle">
                            <thead>
                            <tr>

                                <th class="uk-text-left">Joomla User Id
                                    <a href="#" @click="sort('id')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>
                                <th class="uk-text-left">Name
                                    <a href="#" @click="sort('name')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>
                                <th class="uk-text-left">Email
                                    <a href="#" @click="sort('email')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>

                                <th class="uk-text-left">No. of Orders
                                    <a href="#" @click="sort('total_orders')" class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>
                                <th class="uk-text-left">Order Total
                                    <a href="#" @click="sort('order_total_integer')"
                                       class="uk-margin-small-right uk-icon"
                                       uk-icon="triangle-down">
                                    </a>
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr class="el-item" v-for="item in itemsChunked[currentPage]">

                                <td>
                                    {{item.j_user_id}}
                                </td>
                                <td>
                                    <a :href="'index.php?option=com_protostore&view=customer&id=' + item.id">{{item.name}}</a>
                                </td>
                                <td>
                                    {{item.email}}
                                </td>
                                <td>
                                    {{item.total_orders}}
                                </td>
                                <td>
                                    {{item.order_total}}
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

            </div>
        </div>

    </div>
</div>
