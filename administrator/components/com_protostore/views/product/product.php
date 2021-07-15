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
use Joomla\CMS\Uri\Uri;


HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');


$item = $vars['item'];


?>


<div id="p2s_product_form">
    <form @submit.prevent="saveItem">
        <div class="uk-margin-left">
            <div class="uk-grid" uk-grid="">
                <div class="uk-width-1-1">

                    <div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; offset: 31">

                        <nav class="uk-navbar-container uk-padding-small" uk-navbar style="border-radius: 8px">

                            <div class="uk-navbar-left">

                                <span class="uk-navbar-item uk-logo">

	                                    <?= Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_EDITING'); ?>  {{form.jform_title}}

                                </span>

                            </div>

                            <div class="uk-navbar-right">


                                <button type="submit" @click="andClose = false"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
                                    Save
                                </button>
                                <button type="submit" @click="andClose = true"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
                                    Save & Close
                                </button>
                                <a class="uk-button uk-button-default uk-button-small uk-margin-right"
                                   href="index.php?option=com_protostore&view=products">Cancel</a>
                                <button type="button" uk-toggle="target: #advancedOptions"
                                        class="uk-button uk-button-primary uk-button-small uk-margin-right">
                                    Advanced Options
                                    <span uk-icon="icon: settings"></span>
                                </button>

                            </div>

                        </nav>
                    </div>

                </div>
                <div class="uk-width-2-3">

					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardStyle' => 'default',
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_PRODUCT_DETAILS',
						'cardId'    => 'details',
						'fields'    => array('title', 'short_description', 'long_description')
					)); ?>

					<?= LayoutHelper::render('card', array(
						'form'             => $vars['form'],
						'cardTitle'        => 'COM_PROTOSTORE_ADD_PRODUCT_IMAGES',
						'cardStyle'        => 'default',
						'cardId'           => 'images',
						'fields'           => array('teaserimage', 'fullimage'),
						'field_grid_width' => '1-2',
					)); ?>

					<?= LayoutHelper::render('card_options', array(
						'form'             => $vars['form'],
						'cardTitle'        => 'COM_PROTOSTORE_ADD_PRODUCT_OPTIONS',
						'cardStyle'        => 'default',
						'cardId'           => 'options',
						'fields'           => array('options'),
						'field_grid_width' => '1-1',
					)); ?>

					<?= LayoutHelper::render('card_variant', array(
						'form'             => $vars['form'],
						'cardTitle'        => 'COM_PROTOSTORE_ADD_PRODUCT_VARIANTS',
						'cardStyle'        => 'default',
						'cardId'           => 'variants',
						'fields'           => array('variants'),
						'field_grid_width' => '1-1',
					)); ?>


                </div>


                <div class="uk-width-1-3">


					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_PRODUCT_ACCESS',
						'cardStyle' => 'default',
						'cardId'    => 'access',
						'fields'    => array('state', 'access', 'publish_up_date')
					)); ?>
					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_ORGANISATION',
						'cardStyle' => 'default',
						'cardId'    => 'organisation',
						'fields'    => array('category', 'featured', 'tags')
					)); ?>
					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_PRODUCT_PRICING',
						'cardStyle' => 'default',
						'cardId'    => 'pricing',
						'fields'    => array('base_price', 'taxable', 'show_discount', 'discount')
					)); ?>

					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_INVENTORY',
						'cardStyle' => 'default',
						'cardId'    => 'inventory',
						'fields'    => array('sku', 'manage_stock', 'stock')
					)); ?>
					<?php if ($item->product_type == 0) : ?>
						<?= LayoutHelper::render('card', array(
							'form'      => $vars['form'],
							'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_SHIPPING',
							'cardStyle' => 'default',
							'cardId'    => 'shipping',
							'fields'    => array('shipping_mode', 'flatfee')
						)); ?>
					<?php endif; ?>

                </div>
            </div>

        </div>
    </form>


    <div id="advancedOptions" class="uk-modal-container" uk-modal>
        <div class="uk-modal-dialog">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h2 class="uk-modal-title">Advanced Options</h2>
            </div>
            <div class="uk-modal-body">
                <div class="uk-grid uk-child-width-1-3@m">

                    <div>
                        <div class="uk-card uk-card-default">
                            <div class="uk-card-header">
                                <h5>Variants</h5>
                            </div>
                            <div class="uk-card-body">

                            </div>
                            <div class="uk-card-footer">
                                <button type="button" class="uk-button uk-button-primary">Copy Variants <span uk-icon="icon: copy"></span></button>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            </div>
        </div>
    </div>
</div>
