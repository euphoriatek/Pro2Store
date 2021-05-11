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
		$currency = CurrencyFactory::getCurrent();
		$cart     = CartFactory::get();

		$response['cartItems'] = $cart->cartItems;
		$response['cartCount'] = $cart->count;
		$response['total']     = CurrencyFactory::translate(Total::getGrandTotal(), $currency);
		$response['totalInt']  = Total::getGrandTotal();
		$response['subtotal']  = CurrencyFactory::translate(Total::getSubTotal(), $currency);
		$response['tax']       = CurrencyFactory::translate(Tax::calculateTotalTax(), $currency);
		$response['taxInt']    = Tax::calculateTotalTax();

		if (Factory::getUser()->guest)
		{
			if (CartFactory::isGuestAddressSet())
			{
				$response['totalShipping'] = CurrencyFactory::translate(Shipping::getTotalShippingFromPlugin(), $currency);
			}
			else
			{
				$response['totalShipping'] = Text::_('COM_PROTOSTORE_ELM_CARTSUMMARY_SELECT_SHIPPING_ADDRESS');
			}


		}
		else
		{
			$response['totalShipping'] = CurrencyFactory::translate(Shipping::getTotalShippingFromPlugin(), $currency);
		}


		$couponDiscount = CouponFactory::calculateDiscount(Total::getSubTotal());

		if ($couponDiscount > Total::getSubTotal())
		{
			$response['totalDiscount']    = CurrencyFactory::translate($response['subtotal'], $currency);
			$response['totalDiscountInt'] = $response['subtotal'];
		}
		else
		{
			$discount                     = CouponFactory::calculateDiscount(Total::getSubTotal());
			$response['totalDiscount']    = CurrencyFactory::translate($discount, $currency);
			$response['totalDiscountInt'] = CouponFactory::calculateDiscount(Total::getSubTotal());
		}


		return $response;
	}

}
