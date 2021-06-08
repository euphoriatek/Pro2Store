<?php

/**
 * @package     Pro2Store - Cart Summary
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

use Protostore\Currency\CurrencyFactory;
use Protostore\Cart\CartFactory;
use Protostore\Shipping\Shipping;
use Protostore\Total\Total;
use Protostore\Coupon\Coupon;
use Protostore\Cart\Cart;
use Protostore\Tax\Tax;

use Brick\Money\Money;

return [

    // Define transforms for the element node
    'transforms' => [


        // The function is executed before the template is rendered
        'render' => function ($node, array $params) {


            $language = Factory::getLanguage();
            $language->load('com_protostore', JPATH_ADMINISTRATOR);

            $node->props['baseUrl'] = Uri::base();

            $currency = CurrencyFactory::getCurrent();
            $cart = CartFactory::get();

            $app = Factory::getApplication();
            $config = $app->getParams('com_protostore');

            if($config->get('address_show')) {
                $node->props['show_shipping'] = true;
            } else {
                $node->props['show_shipping'] = false;
            }

            $node->props['cart'] = $cart;

//            $shippingTotal = CurrencyFactory::translate(Shipping::getTotalShippingFromPlugin($cart), $currency);
//
//            $node->props['subTotal'] = CurrencyFactory::translate(Total::getSubTotal($cart), $currency);
//            $node->props['total'] = CurrencyFactory::translate(Total::getGrandTotal($cart), $currency);
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

//            if ($coupon = Coupon::getCurrentAppliedCoupon()) {
//                if ($coupon->discount_type === 'freeship') {
//                    $node->props['totalDiscount'] =  Currency::translate(Coupon::calculateDiscount(Total::getSubTotal()), $currencyHelper);
//                } else {
//                    $couponDiscount = Money::ofMinor(Coupon::calculateDiscount(Total::getSubTotal()), $currencyHelper->currency->iso);
//                    if ($couponDiscount > Total::getSubTotal()) {
//                        $node->props['totalDiscount'] = $node->props['subTotal'];
//                    } else {
//                        $node->props['totalDiscount'] = Currency::translate(Coupon::calculateDiscount(Total::getSubTotal()), $currencyHelper);
//                    }
//                }
//            } else {
//                $node->props['totalDiscount'] = Currency::translate(0, $currencyHelper);
//            }


        },

    ]

];

?>
