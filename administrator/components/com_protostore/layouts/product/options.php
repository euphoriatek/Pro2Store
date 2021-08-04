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

?>

<table class="uk-table uk-table-striped uk-table-hover">
    <thead>
    <tr>
        <th><?= Text::_('COM_PROTOSTORE_ADD_PRODUCT_OPTIONS_TABLE_TYPE'); ?></th>
        <th><?= Text::_('COM_PROTOSTORE_ADD_PRODUCT_OPTIONS_TABLE_NAME'); ?></th>
        <th><?= Text::_('COM_PROTOSTORE_ADD_PRODUCT_OPTIONS_TABLE_MODIFIER'); ?></th>
        <th><?= Text::_('COM_PROTOSTORE_ADD_PRODUCT_OPTIONS_TABLE_VALUE'); ?></th>
        <th><?= Text::_('COM_PROTOSTORE_ADD_PRODUCT_OPTIONS_TABLE_SKU'); ?></th>
        <th></th>
    </tr>
    </thead>
    <tbody uk-sortable="handle: .uk-sortable-handle; cls-custom: uk-box-shadow-small uk-flex uk-flex-middle uk-background">

    <tr v-for="(option, index) in form.jform_options" >
        <td>{{option.optiontypename}}</td>
        <td>{{option.optionname}}</td>
        <td>{{option.modifier}}</td>
        <td>{{option.modifiervalue_translated}}</td>
        <td>{{option.optionsku}}</td>
        <td class="uk-text-right">
            <ul class="uk-iconnav uk-text-right">
                <li>
                    <a @click="openEditoptionModal(option)"><i class="pi pi-pencil"></i></a>
                </li>
                <li>
                    <a><i @click="removeOption(index)" class="pi pi-trash"></i></a>
                </li>
                <li class="uk-text-right">
                    <span class="uk-sortable-handle uk-margin-small-right" uk-icon="icon: table"></span>
                </li>
            </ul>
        </td>
    </tr>

    </tbody>
</table>



<?= LayoutHelper::render('options_modal', array()); ?>
