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

$item = $vars['item'];


?>

<script id="jform_title_data" type="application/json"><?= $item->joomlaItem->title; ?></script>
<script id="jform_manage_stock_data"
        type="application/json"><?= ($item->manage_stock == 1 ? 'true' : 'false'); ?></script>
<script id="jform_category_data" type="application/json"><?= $item->joomlaItem->catid; ?></script>
<script id="jform_featured_data"
        type="application/json"><?= ($item->joomlaItem->featured == 1 ? 'true' : 'false'); ?></script>
<script id="jform_taxable_data" type="application/json"><?= ($item->taxable == 1 ? 'true' : 'false'); ?></script>
<script id="jform_discount_data" type="application/json"><?= ($item->discount > 0 ? 'true' : 'false'); ?></script>
<script id="jform_shipping_mode_data" type="application/json"><?= $item->shipping_mode; ?></script>
<script id="jform_base_price_data" type="application/json"><?= $item->basepricefloat; ?></script>

<div id="p2s_product_form">
    <form @submit.prevent="submitForm">
        <div class="uk-margin-left">
            <div class="uk-grid" uk-grid="">
                <div class="uk-width-1-1">

                    <div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; offset: 31">

                        <nav class="uk-navbar-container uk-padding-small" uk-navbar style="border-radius: 8px">

                            <div class="uk-navbar-left">

                                <span class="uk-navbar-item uk-logo">
                                    <?php if ($vars['item']) : ?>
	                                    <?= Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_EDITING'); ?>  {{form.jform_title}}
                                    <?php else : ?>
	                                    <?= Text::_('COM_PROTOSTORE_ADD_PRODUCT_TITLE'); ?>
                                    <?php endif; ?>
                                </span>

                            </div>

                            <div class="uk-navbar-right">


                                <button type="submit"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
                                    Save
                                </button>
                                <button type="submit"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
                                    Save & Close
                                </button>
                                <a class="uk-button uk-button-default uk-button-small "
                                   href="index.php?option=com_protostore&view=products">Cancel</a>

                            </div>

                        </nav>
                    </div>

                </div>
                <div class="uk-width-2-3">

					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardStyle' => 'default',
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_PRODUCT_DETAILS',
						'fields'    => array('title', 'short_description', 'long_description')
					)); ?>

					<?= LayoutHelper::render('card', array(
						'form'             => $vars['form'],
						'cardTitle'        => 'COM_PROTOSTORE_ADD_PRODUCT_IMAGES',
						'cardStyle'        => 'default',
						'fields'           => array('teaserimage', 'fullimage'),
						'field_grid_width' => '1-2',
					)); ?>

					<?php if ($item->product_type = 0) : ?>
						<?= LayoutHelper::render('card', array(
							'form'      => $vars['form'],
							'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_SHIPPING',
							'cardStyle' => 'default',
							'fields'    => array('shipping_mode')
						)); ?>
					<?php endif; ?>

                </div>


                <div class="uk-width-1-3">
					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_PRODUCT_ACCESS',
						'cardStyle' => 'default',
						'fields'    => array('access', 'publish_up_date')
					)); ?>

					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_PRODUCT_PRICING',
						'cardStyle' => 'default',
						'fields'    => array('base_price', 'taxable', 'discount')
					)); ?>

					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_INVENTORY',
						'cardStyle' => 'default',
						'fields'    => array('sku', 'manage_stock', 'stock')
					)); ?>

					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_ORGANISATION',
						'cardStyle' => 'default',
						'fields'    => array('category', 'featured', 'tags')
					)); ?>

                </div>
            </div>

        </div>
    </form>
</div>

<script>
    jQuery(document).ready(function () {
        jQuery('#jform_short_description').attr("v-model", "jform_short_description");
    })

</script>
