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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;


HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

/** @var array $vars */
$item = $vars['item'];


?>

<div id="p2s_customer_form">
    <form @submit.prevent="saveItem">
        <div class="uk-margin-left">
            <div class="uk-grid" uk-grid="">
                <div class="uk-width-1-1">

                    <div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; offset: 31">

                        <nav class="uk-navbar-container uk-padding-small" uk-navbar style="border-radius: 8px">

                            <div class="uk-navbar-left">

                                <span class="uk-navbar-item uk-logo">

	                                    <?= Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_EDITING'); ?>  {{form.jform_name}}

                                </span>

                            </div>

                            <div class="uk-navbar-right">

                                <button type="submit"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
                                    Save
                                </button>
                                <button type="submit" @click="andClose = true"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
                                    Save & Close
                                </button>
                                <a class="uk-button uk-button-default uk-button-small "
                                   href="index.php?option=com_protostore&view=customers">Cancel</a>

                            </div>

                        </nav>
                    </div>

                </div>
                <div class="uk-width-1-2 uk-animation-fade">

					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardStyle' => 'default',
						'cardTitle' => 'COM_PROTOSTORE_ORDER_CUSTOMER_DETAILS',
						'cardId'    => 'details',
						'fields'    => array('name', 'email', 'j_user_id', 'published'),
						'field_grid_width' => '1-2',
					)); ?>



					<?= LayoutHelper::render('customer/address_grid_card', [
						'addresses'      => ($item->addresses ? $item->addresses : ''),
						'cardStyle' => 'default',
						'cardTitle' => 'Addresses',
						'cardId'    => 'customer_addresses',
					]); ?>

                </div>



                <div class="uk-width-1-2">
	                <?= LayoutHelper::render('customer/order_grid_card', array(
		                'form'      => $vars['form'],
		                'cardStyle' => 'default',
		                'cardTitle' => 'Orders',
		                'cardId'    => 'orders'
	                )); ?>

                </div>
            </div>

        </div>
    </form>
</div>


