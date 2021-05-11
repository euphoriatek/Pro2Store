<?php
/**
 * @package     Pro2Store
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access


defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

use Protostore\Currency\CurrencyFactory;
use Protostore\Cart\CartFactory;
use Protostore\Coupon\CouponFactory;
use Protostore\Shipping\Shipping;
use Protostore\Total\Total;
use Protostore\Tax\Tax;

class protostoreTask_update
{

	/**
	 * @throws Exception
	 */
	public function getResponse($data)
	{

		// init


		$response = array();

		$cart     = CartFactory::get();

//		$response['cartItems'] = $cart->cartItems;
//		$response['cartCount'] = $cart->count;
//
//		$response['total']    = $cart->total;
//		$response['totalInt'] = $cart->totalInt;
//		$response['subtotal'] = $cart->subtotal;
//		$response['subtotalInt'] = $cart->subtotalInt;
//		$response['tax']      = $cart->tax;
//		$response['taxInt']   = $cart->taxInt;
//		$response['totalShipping'] = $cart->totalShipping;
//
//		$response['totalDiscount']    = $cart->totalDiscount;
//		$response['totalDiscountInt'] = $cart->totalDiscountInt;
//
//

		return $cart;
	}

}
