<?php
/**
 * @package     Pro2Store - Cart Items
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


use Joomla\CMS\Uri\Uri;

use Protostore\Cart\CartFactory;

return ['transforms' => ['render' => function ($node, array $params) {

	$cart = CartFactory::get();

    $node->props['cartItems'] = $cart->cartItems;
    $node->props['baseUrl'] = Uri::base();


},]];

























































