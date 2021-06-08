<?php

/**
 * @package     Pro2Store - Add To Cart
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Uri\Uri;

use Joomla\CMS\Language\Text;

use Protostore\Cart\CartFactory;
use Protostore\Tandcs\Tandcs;
use Protostore\Utilities\Utilities;

return [

    // Define transforms for the element node
    'transforms' => [


        // The function is executed before the template is rendered
        'render' => function ($node, array $params) {


            $params = ComponentHelper::getParams('com_protostore');

            $node->props['baseUrl'] = Uri::base();
            $node->props['buttonEnabled'] = true;
            $node->props['message'] = "";


            if($params->get('requiretandcs')) {
                if(!Tandcs::isChecked()) {
                    $node->props['buttonEnabled'] = false;
                    $node->props['message'] = "Please Accept Terms and Conditions";
                }
            }


            if (Factory::getUser()->guest) {

                $node->props['buttonEnabled'] = false;
                $node->props['message'] = Text::_('COM_PROTOSTORE_ELM_ALERT_PLEASE_LOGIN');

                if (Utilities::isGuestCheckoutValid()) {
                    $node->props['buttonEnabled'] = true;
                    $node->props['message'] = "";
                }

            }

            if (!Utilities::isShippingAssigned()) {

                $node->props['buttonEnabled'] = false;
                $node->props['message'] .= Text::_('COM_PROTOSTORE_ELM_ALERT_PLEASE_ASSIGN_SHIPPING_ADDRESS');
            }

            if (!Utilities::isBillingAssigned()) {

                $node->props['buttonEnabled'] = false;
                $node->props['message'] .= Text::_('COM_PROTOSTORE_ELM_ALERT_PLEASE_ASSIGN_BILLING_ADDRESS');
            }

            $cart = CartFactory::get();

            if($cart->count == 0) {
                $node->props['buttonEnabled'] = false;
                $node->props['message'] = Text::_('COM_PROTOSTORE_ELM_ALERT_CART_EMPTY');
            }

        },

    ]

];

?>
