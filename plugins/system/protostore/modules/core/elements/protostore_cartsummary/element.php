<?php

/**
 * @package     Pro2Store - Cart Summary
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


use Joomla\CMS\Uri\Uri;

use Protostore\Cart\CartFactory;
use Protostore\Currency\CurrencyFactory;
use Protostore\Shipping\ShippingFactory;
use Protostore\Total\TotalFactory;
use Protostore\Coupon\CouponFactory;
use Protostore\Cart\Cart;
use Protostore\Tax\Tax;
use Protostore\Language\LanguageFactory;
use Protostore\Config\ConfigFactory;

use Brick\Money\Money;

return [

	// Define transforms for the element node
	'transforms' => [


		// The function is executed before the template is rendered
		'render' => function ($node, array $params) {

			LanguageFactory::load();
			$config = ConfigFactory::get();
			$cart = CartFactory::get();
			$node->props['baseUrl'] = Uri::base();


			if ($config->get('address_show'))
			{
				$node->props['show_shipping'] = true;
			}
			else
			{
				$node->props['show_shipping'] = false;
			}

			$node->props['cart'] = $cart;

            $shippingTotal = CurrencyFactory::translate(ShippingFactory::getShipping($cart));
//
            $node->props['subTotal'] = CurrencyFactory::translate(TotalFactory::getSubTotal($cart));
            $node->props['total'] = CurrencyFactory::translate(TotalFactory::getGrandTotal($cart));
//
//            $node->props['tax'] = CurrencyFactory::translate(Tax::calculateTotalTax($cart), $currency);


//            if (Factory::getUser()->guest) {
//                if (Cart::isGuestAddressSet()) {
//
//                    $node->props['totalShipping'] = $shippingTotal;
//                } else {
//                    $node->props['totalShipping'] = Text::_('COM_PROTOSTORE_ELM_CARTSUMMARY_SELECT_SHIPPING_ADDRESS');
//                }
//
//            } else {
//                $node->props['totalShipping'] = $shippingTotal;
//            }


			// FFS COMMENT THIS STUFF FFS!

			// check if a coupon is applied
            if ($coupon = CouponFactory::getCurrentAppliedCoupon()) {

            	// check if the discount is free shipping
                if ($coupon->discount_type === 'freeship') {

                	//if so, set the discount to the shipping total.
                    $node->props['totalDiscount'] =  CurrencyFactory::translate(CouponFactory::calculateDiscount($cart));
                } else {
                	//
                    $couponDiscount = CouponFactory::calculateDiscount($cart);
                    if ($couponDiscount > TotalFactory::getSubTotal($cart)) {
                        $node->props['totalDiscount'] = $node->props['subTotal'];
                    } else {
                        $node->props['totalDiscount'] = CurrencyFactory::translate(CouponFactory::calculateDiscount($cart));
                    }
                }
            } else {

            	// if no coupon applied, simply return £0.00 (or whatever currency is selected)
                $node->props['totalDiscount'] = CurrencyFactory::translate(0);
            }

			$node->props['totalDiscount'] = CurrencyFactory::translate(CouponFactory::calculateDiscount($cart));

		},

	]

];
