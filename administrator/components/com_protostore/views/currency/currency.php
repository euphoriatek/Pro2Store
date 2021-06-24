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
use Joomla\CMS\Form\Field;


HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

$item = $vars['item'];
?>

<?php if ($item) : ?>

    <script id="jform_name_data" type="application/json"><?= $item->name; ?></script>
    <script id="jform_iso_data" type="application/json"><?= $item->iso; ?></script>
    <script id="jform_currencysymbol_data" type="application/json"><?= $item->currencysymbol; ?></script>
    <script id="jform_rate_data" type="application/json"><?= $item->rate; ?></script>
    <script id="jform_default_data" type="application/json"><?= ($item->default == 1 ? 'true' : 'false'); ?></script>
    <script id="jform_published_data"
            type="application/json"><?= ($item->published == 1 ? 'true' : 'false'); ?></script>

<?php endif; ?>


<div id="p2s_currency_form">

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


                                <button type="submit" @click="andClose = false"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
                                    Save
                                </button>
                                <button type="submit" @click="andClose = true"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
                                    Save & Close
                                </button>
                                <a class="uk-button uk-button-default uk-button-small "
                                   href="index.php?option=com_protostore&view=currencies">Cancel</a>

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
						'fields'    => array('name', 'iso', 'currencysymbol', 'rate', 'default', 'published')
					)); ?>

                </div>


                <div class="uk-width-1-3">


                </div>
            </div>

        </div>
    </form>
</div>
