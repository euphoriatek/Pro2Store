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

?>


<script id="base_url" type="application/json"><?= Uri::base(); ?></script>
<script id="currencies_data" type="application/json"><?= json_encode($vars['items']); ?></script>

<div id="p2s_currencies">
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
									</svg> &nbsp; <?= Text::_('COM_PROTOSTORE_CURRENCIES_TITLE'); ?></h3>
							</div>
							<div class="uk-width-auto uk-text-right">
								<div class="uk-grid uk-grid-small" uk-grid="">
									<div class="uk-width-auto">
										<input  @input="doTextSearch($event)" type="text" placeholder="Search...">
									</div>
									<div class="uk-width-auto">

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
									<a href="#" @click="sort('name')" class="uk-margin-small-right uk-icon"
									   uk-icon="triangle-down">
									</a>
								</th>
								<th class="uk-text-left">ISO
									<a href="#" @click="sort('iso')" class="uk-margin-small-right uk-icon"
									   uk-icon="triangle-down">
									</a>
								<th class="uk-text-left">Symbol
									<a href="#" @click="sort('currencysymbol')" class="uk-margin-small-right uk-icon"
									   uk-icon="triangle-down">
									</a>
								</th>

								<th class="uk-text-left">Rate
									<a href="#" @click="sort('rate')" class="uk-margin-small-right uk-icon"
									   uk-icon="triangle-down">
									</a>
								</th>
								<th class="uk-text-left">Published
									<a href="#" @click="sort('published')" class="uk-margin-small-right uk-icon"
									   uk-icon="triangle-down">
									</a>
								</th>
								<th class="uk-text-left">Default
									<a href="#" @click="sort('default')" class="uk-margin-small-right uk-icon"
									   uk-icon="triangle-down">
									</a>
								</th>

								<th class="uk-text-left@m uk-text-nowrap">
								</th>
							</tr>
							</thead>

							<tbody>
							<tr class="el-item" v-for="currency in currenciesChunked[currentPage]">
								<td>
									<div><input type="checkbox"></div>
								</td>
								<td>
									<a :href="'index.php?option=com_protostore&view=currency&id=' + currency.id">{{currency.name}}</a>
								</td>
								<td>
                                    {{currency.iso}}
								</td>
								<td>
                                    {{currency.currencysymbol}}
								</td>
								<td>
                                    {{currency.rate}}
								</td>
								<td class="uk-text-center">
                                  <span v-if="currency.published == '1'" class="yps_currency_published_icon" @click="togglePublished(currency)"
                                        style="font-size: 18px; color: green; cursor: pointer;">
                                      <i class="fal fa-check-circle"></i>
                                  </span>
									<span
										v-if="currency.published == '0'"
										class="yps_currency_published_icon"
										@click="togglePublished(currency)"
										style="font-size: 18px; color: red; cursor: pointer;">
                                        <i class="fal fa-times-circle"></i>
                                    </span>
								</td>
                                <td>
                                    {{currency.default}}
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
