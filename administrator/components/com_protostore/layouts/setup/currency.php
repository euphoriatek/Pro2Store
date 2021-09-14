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

<div class="uk-card uk-card-default uk-margin-bottom">
    <div class="uk-card-header">
        <h3><?= Text::_('COM_PROTOSTORE_SETUP_SETUPACURRENCY'); ?></h3></div>
    <div class="uk-card-body">
        <div class="uk-grid" uk-grid="">
        <div class="uk-width-1-2">
            <div class="uk-margin">
                <div class="uk-alert-primary" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
			        <?= Text::_('COM_PROTOSTORE_SETUP_CHOOSE_A_DEFAULT_CURRENCY_INFO'); ?>
                </div>

            </div>
        </div>
        <div class="uk-width-1-2"></div>
        </div>


        <div class="uk-margin">
            <div class="uk-grid uk-child-width-1-3@m" uk-grid>
                <div>
                    <div :class="[selectedCurrency == 14 ? 'uk-card uk-card-primary uk-card-small uk-card-hover' : 'uk-card uk-card-default uk-card-small uk-card-hover']"
                         style="cursor: pointer" @click="selectedCurrency = 14">
                        <div class="uk-card-body">
                            <h1 class="uk-heading-large">€</h1>
                            <p>Euro</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div :class="[selectedCurrency == 117 ? 'uk-card uk-card-primary uk-card-small uk-card-hover' : 'uk-card uk-card-default uk-card-small uk-card-hover']"
                         style="cursor: pointer" @click="selectedCurrency = 117">
                        <div class="uk-card-body">
                            <h1 class="uk-heading-large">£</h1>
                            <p>Pound Sterling</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div :class="[selectedCurrency == 5 ? 'uk-card uk-card-primary uk-card-small uk-card-hover' : 'uk-card uk-card-default uk-card-small uk-card-hover']"
                         style="cursor: pointer" @click="selectedCurrency = 5">
                        <div class="uk-card-body">
                            <h1 class="uk-heading-large">$</h1>
                            <p>US Dollar</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="uk-margin">
            <label class="uk-form-label"
                   for="form-stacked-select"><?= Text::_('COM_PROTOSTORE_SETUP_CHOOSE_A_DEFAULT_CURRENCY_OTHER'); ?></label>
            <div class="uk-form-controls">
                <select class="uk-select" v-model="selectedCurrency">
                    <option v-for="currency in currencies" :value="currency.id">
                        {{currency.name}}
                        ({{currency.iso}})
                    </option>
                </select>
            </div>
        </div>


    </div>
</div>
