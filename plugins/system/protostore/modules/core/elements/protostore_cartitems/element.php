<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */


use Joomla\CMS\Uri\Uri;

use Protostore\Cart\CartFactory;


return [
	'transforms' =>
		['render' => function ($node, array $params) {

			$cart = CartFactory::get();

			$node->props['cartItems'] = $cart->cartItems;
			$node->props['baseUrl']   = Uri::base();


		},
		]
];

























































