<?php

/**
 * @package     Pro2Store - Coupon Field
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

use Protostore\Coupon\Coupon;


return [

    // Define transforms for the element node
    'transforms' => [


        // The function is executed before the template is rendered
        'render' => function ($node, array $params) {

            $node->props['baseUrl'] = Uri::base();

            $language = Factory::getLanguage();
            $language->load('com_protostore', JPATH_ADMINISTRATOR);

            $node->props['buttontext'] = Text::_('COM_PROTOSTORE_ELM_COUPON_FIELD_BUTTON_TEXT_ADD');
            $node->props['removebuttontext'] = Text::_('COM_PROTOSTORE_ELM_COUPON_FIELD_BUTTON_TEXT_REMOVE');
            $node->props['couponapplied'] = Text::_('COM_PROTOSTORE_ELM_COUPON_FIELD_COUPON_APPLIED');
            $node->props['entercouponcode'] = Text::_('COM_PROTOSTORE_ELM_COUPON_FIELD_PLACEHOLDER');
            $node->props['couponremoved'] = Text::_('COM_PROTOSTORE_ELM_COUPON_FIELD_COUPON_REMOVED');

            $node->props['isCouponApplied'] = Coupon::isCouponApplied() ? 'true' : 'false';

            if($node->props['isCouponApplied']) {
                $node->props['coupon'] = Coupon::getCurrentAppliedCoupon();
            }


        },

    ]

];

?>
