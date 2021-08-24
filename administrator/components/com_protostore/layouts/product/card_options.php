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

$data = $displayData;
$form = $data['form'];

?>

<div class="uk-card uk-card-<?= $data['cardStyle']; ?> uk-margin-bottom uk-animation-fade">
    <div class="uk-card-header">
        <div class="uk-grid uk-grid-small">
            <div class="uk-width-expand">
                <h3>
					<?= Text::_($data['cardTitle']); ?>
                </h3>
            </div>
            <div class="uk-width-auto">
                <div class="uk-inline uk-text-left">
                    <button class="uk-button uk-button-small button-success" type="button">
                        <span uk-icon="plus-circle"></span>
                        <?= Text::_('COM_PROTOSTORE_ADD_PRODUCT_OPTIONS_ADD_BUTTON'); ?>
                    </button>
                    <div uk-dropdown="mode: click">
                        <ul class="uk-nav uk-dropdown-nav">
                            <li v-for="(option, index) in available_options">
                                <button type="button" class="uk-button uk-button-text" @click="addOptionOfType(index)"><?= Text::_('COM_PROTOSTORE_ADD_PRODUCT_OPTIONS_ADD'); ?> {{option.name}}</button>
                            </li>
                            <li class="uk-nav-divider"></li>
                            <li>
                                <button class="uk-button uk-button-small" type="button" @click="addNewOptionType">
                                  <span uk-icon="plus-circle"></span>
                                    <?= Text::_('COM_PROTOSTORE_ADD_PRODUCT_OPTIONS_ADD_PRODUCT_OPTION_TYPE'); ?>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="uk-card-body" >
	    <?= LayoutHelper::render('product/options', ''); ?>
    </div>
    <div class="uk-card-footer"></div>
</div>


<?= LayoutHelper::render('product/add_option_type_modal', ''); ?>
