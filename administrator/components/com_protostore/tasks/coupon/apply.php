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

use Protostore\Coupon\CouponFactory;


class protostoreTask_apply
{

	/**
	 * @throws Exception
	 * @since 1.6
	 */
	public function getResponse($data)
	{

		// init
		$response = array();

		$couponCode = $data->get('couponCode');

		if ($couponCode)
		{
			$applied = CouponFactory::apply($couponCode);
			if ($applied)
			{
				$response['applied'] = $applied;
			}
			else
			{
				throw new Exception('Error Applying Coupon');
			}

		}
		else
		{
			throw new Exception('no coupon code');
		}


		return $response;
	}

}
