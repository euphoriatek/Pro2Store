<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */
// no direct access


defined('_JEXEC') or die('Restricted access');

use Protostore\Coupon\CouponFactory;


class protostoreTask_apply
{

	/**
	 * @throws Exception
	 * @since 2.0
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
