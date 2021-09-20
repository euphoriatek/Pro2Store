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
