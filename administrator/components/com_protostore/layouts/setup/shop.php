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

<div class="uk-card uk-card-default  uk-margin-bottom">
	<div class="uk-card-header">
		<h3><?= Text::_('COM_PROTOSTORE_SETUP_GETSTARTED'); ?></h3></div>
	<div class="uk-card-body">

        <div class="uk-margin">
            <label class="uk-form-label" for="shop_name"><?= Text::_('COM_PROTOSTORE_CONFIG_GLOBAL_SHOP_NAME_LABEL'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="shop_name" type="text" placeholder="<?= Text::_('COM_PROTOSTORE_CONFIG_GLOBAL_SHOP_NAME_LABEL'); ?>" v-model="shopName">
            </div>
        </div>
        <div class="uk-margin">
            <label class="uk-form-label" for="shop_email"><?= Text::_('COM_PROTOSTORE_CONFIG_SUPPORT_EMAIL_LABEL'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="shop_email" type="email" placeholder="<?= Text::_('COM_PROTOSTORE_CONFIG_SUPPORT_EMAIL_LABEL'); ?>" v-model="shopEmail">
            </div>
        </div>


	</div>
</div>
