<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */


use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

use Protostore\Cart\CartFactory;
use Protostore\Language\LanguageFactory;

return ['transforms' => ['render' => function ($node, array $params) {

	$cart = CartFactory::get();

	$node->props['cartItems'] = $cart->cartItems;
	$node->props['baseUrl']   = Uri::base();

	if ($node->props['cartItems'])
	{

		LanguageFactory::load();

		$doc = Factory::getDocument();
		$doc->addCustomTag('<script id="yps-cart-items-itemsdata" type="application/json">' . json_encode($node->props['cartItems']) . '</script>');
		$doc->addCustomTag('<script id="yps-cart-items-trans-remove-all-items" type="application/json">' . Text::_('COM_PROTOSTORE_ELM_CARTITEMS_ALERT_REMOVE_ALL_FROM_CART') . '</script>');
	}


},]];

























































