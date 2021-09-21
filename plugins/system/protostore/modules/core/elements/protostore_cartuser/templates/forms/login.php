<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$language = Factory::getLanguage();
$language->load('com_protostore', JPATH_ADMINISTRATOR);

?>

<form @submit.prevent="submitLoginForm">
	<fieldset class="uk-fieldset">
		<legend class="uk-legend"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_LOGIN'); ?>
			<span uk-spinner class="uk-hidden"></span></legend>
		<div class="uk-margin">
			<label class="uk-form-label"
			       for="yps_cartlogin_username"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_USERNAME'); ?></label>
			<input id="yps_cartlogin_username" class="uk-input" type="text"
			       placeholder="<?= Text::_('COM_PROTOSTORE_ELM_CART_USER_USERNAME'); ?>" required
			       v-model="login_form.username">
		</div>
		<div class="uk-margin">
			<label class="uk-form-label"
			       for="yps_cartlogin_password"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_PASSWORD'); ?></label>
			<input id="yps_cartlogin_password" class="uk-input" type="password"
			       placeholder="<?= Text::_('COM_PROTOSTORE_ELM_CART_USER_PASSWORD'); ?>" required
			       v-model="login_form.password">
		</div>
		<button class="uk-button uk-button-default"
		        type="submit"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_LOGIN_BUTTON'); ?></button>
	</fieldset>
</form>
