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
use Joomla\CMS\Uri\Uri;

use Protostore\Coupon\CouponFactory;


return [

	// Define transforms for the element node
	'transforms' => [


		// The function is executed before the template is rendered
		'render' => function ($node, array $params) {

			$node->props['baseUrl'] = Uri::base();

			\Protostore\Language\LanguageFactory::load();

			$node->props['buttontext']       = Text::_('COM_PROTOSTORE_ELM_COUPON_FIELD_BUTTON_TEXT_ADD');
			$node->props['removebuttontext'] = Text::_('COM_PROTOSTORE_ELM_COUPON_FIELD_BUTTON_TEXT_REMOVE');
			$node->props['couponapplied']    = Text::_('COM_PROTOSTORE_ELM_COUPON_FIELD_COUPON_APPLIED');
			$node->props['entercouponcode']  = Text::_('COM_PROTOSTORE_ELM_COUPON_FIELD_PLACEHOLDER');
			$node->props['couponremoved']    = Text::_('COM_PROTOSTORE_ELM_COUPON_FIELD_COUPON_REMOVED');

			$isCouponApplied = CouponFactory::isCouponApplied();

			$node->props['isCouponApplied'] = $isCouponApplied ? 'true' : 'false';

			$node->props['coupon'] = '';


			if ($isCouponApplied)
			{


				$node->props['coupon'] = CouponFactory::getCurrentAppliedCoupon();



			}


		},

	]

];

?>
