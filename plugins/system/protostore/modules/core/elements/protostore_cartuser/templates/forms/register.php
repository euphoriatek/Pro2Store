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

echo "{emailcloak=off}";

?>

<form @submit.prevent="submitRegisterForm">
	<fieldset class="uk-fieldset">

		<legend class="uk-legend"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_CREATE_AN_ACCOUNT'); ?>
			<span v-show="loading" uk-spinner class="uk-hidden"></span></legend>

		<div class="uk-margin">
			<label class="uk-form-label"
			       for="yps_cartsignup_username"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_CHOOSE_A_USERNAME'); ?></label>
			<div class="uk-form-controls">
				<input class="uk-input" id="yps_cartsignup_username" type="text"
				       :class="{ 'uk-form-danger' : formErrorsList['username'] !== undefined ? true : false}"
				       :style="formErrorsList['username'] !== undefined ? 'border-colour: red; border-style: solid; border-width: 1px;' : ''"
				       placeholder="<?= Text::_('COM_PROTOSTORE_ELM_CART_USER_CHOOSE_A_USERNAME_PLACEHOLDER'); ?>"
				       v-model="reg_form.username">
			</div>
		</div>
		<div class="uk-margin">
			<label class="uk-form-label"
			       for="yps_cartsignup_password"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_CHOOSE_A_PASSWORD'); ?></label>
			<div class="uk-form-controls">
				<input class="uk-input" id="yps_cartsignup_password" type="password"
				       :class="{ 'uk-form-danger' : formErrorsList['password'] !== undefined ? true : false}"
				       :style="formErrorsList['password'] !== undefined ? 'border-colour: red; border-style: solid; border-width: 1px;' : ''"
				       placeholder="<?= Text::_('COM_PROTOSTORE_ELM_CART_USER_CHOOSE_A_PASSWORD_PLACEHOLDER'); ?>"
				       required v-model="reg_form.password">
			</div>
		</div>
		<div class="uk-margin">
			<label class="uk-form-label"
			       for="yps_cartsignup_name"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_YOUR_NAME'); ?></label>
			<div class="uk-form-controls">
				<input class="uk-input" type="text"
				       :class="{ 'uk-form-danger' : formErrorsList['name'] !== undefined ? true : false}"
				       :style="formErrorsList['name'] !== undefined ? 'border-colour: red; border-style: solid; border-width: 1px;' : ''"
				       placeholder="<?= Text::_('COM_PROTOSTORE_ELM_CART_USER_YOUR_NAME_PLACEHOLDER'); ?>"
				       required v-model="reg_form.name">
			</div>
		</div>
		<div class="uk-margin">
			<label class="uk-form-label"
			       for="yps_cartsignup_email"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_YOUR_EMAIL'); ?></label>
			<div class="uk-form-controls">
				<input class="uk-input" id="yps_cartsignup_email" type="email"
				       :class="{ 'uk-form-danger' : formErrorsList['email'] !== undefined ? true : false}"
				       :style="formErrorsList['email'] !== undefined ? 'border-colour: red; border-style: solid; border-width: 1px;' : ''"
				       placeholder="<?= Text::_('COM_PROTOSTORE_ELM_CART_USER_YOUR_EMAIL_PLACEHOLDER'); ?>"
				       required v-model="reg_form.email">
			</div>
		</div>

		<button class="uk-button uk-button-default"
		        type="submit"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_CONFIRM'); ?></button>
	</fieldset>
</form>
