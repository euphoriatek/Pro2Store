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


class protostoreTask_remove
{

	/**
	 * @param $data
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */

	public function getResponse($data): bool
	{

		return CouponFactory::remove();

	}

}
