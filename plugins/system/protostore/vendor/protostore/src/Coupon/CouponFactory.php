<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */


namespace Protostore\Coupon;

// no direct access
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Factory;
use Joomla\CMS\Date\Date;
use Protostore\Shipping\Shipping;
use Protostore\Utilities\Utilities;

use stdClass;

class CouponFactory
{

	/**
	 * @param $id
	 *
	 * @return false|Coupon
	 *
	 * @since 1.7
	 */

	public static function get($id)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_discount'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			return new Coupon($result);
		}
		else
		{
			return false;
		}

	}

	/**
	 * @param $couponCode
	 *
	 * @return false|Coupon
	 *
	 * @since 1.7
	 */

	public static function getByCode($couponCode)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_discount'));
		$query->where($db->quoteName('couponcode') . ' = ' . $db->quote($couponCode));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			return new Coupon($result);
		}
		else
		{
			return false;
		}

	}

	/**
	 *
	 * @return false|Coupon
	 *
	 * @since 1.7
	 */


	public static function getCurrentAppliedCoupon()
	{

		$cookieId = Utilities::getCookieID();
		$db       = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_coupon_cart'));
		$query->where($db->quoteName('cookie_id') . ' = ' . $db->quote($cookieId));

		$db->setQuery($query);

		$appliedCoupon = $db->loadObject();

		if ($appliedCoupon)
		{

			$query = $db->getQuery(true);

			$query->select('*');
			$query->from($db->quoteName('#__protostore_discount'));
			$query->where($db->quoteName('id') . ' = ' . $db->quote($appliedCoupon->coupon_id));
			$query->where($db->quoteName('expiry_date') . ' > ' . $db->quote(Utilities::getDate()));

			$db->setQuery($query);

			$result = $db->loadObject();

			if ($result)
			{
				return new Coupon($result);
			}
			else
			{
				return false;
			}

		}
		else
		{
			return false;
		}

	}

	/**
	 * @param $couponCode
	 *
	 * @return bool
	 *
	 * @since version
	 */


	public static function apply($couponCode)
	{

		// check if coupon is valid
		$valid = self::checkCouponValidity($couponCode);

		if ($valid)
		{
			// check if coupon is already applied
			$alreadyApplied = self::isCouponApplied();

			if ($alreadyApplied)
			{
				return false;
			}

			$coupon = self::getByCode($couponCode);

			$object            = new stdClass();
			$object->id        = 0;
			$object->cookie_id = Utilities::getCookieID();
			$object->coupon_id = $coupon->id;

			$insert = Factory::getDbo()->insertObject('#__protostore_coupon_cart', $object);

			if ($insert)
			{
				return true;
			}

		}

		return false;

	}

	/**
	 *
	 * @return bool
	 *
	 * @since version
	 */

	public static function remove()
	{

		$cookieId = Utilities::getCookieID();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('cookie_id') . ' = ' . $db->quote($cookieId)
		);

		$query->delete($db->quoteName('#__protostore_coupon_cart'));
		$query->where($conditions);

		$db->setQuery($query);

		$result = $db->execute();

		if ($result)
		{
			return true;
		}

		return false;


	}


	/**
	 *
	 * checkCouponValidity - checks if the coupon is valid against the expiry date and that the coupon code exists
	 *
	 *
	 * @param $couponCode
	 *
	 * @return bool
	 *
	 * @since version
	 */


	public static function checkCouponValidity($couponCode)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('id');
		$query->from($db->quoteName('#__protostore_discount'));
		$query->where($db->quoteName('couponcode') . ' = ' . $db->quote($couponCode));
		$query->where($db->quoteName('expiry_date') . ' > ' . $db->quote(Utilities::getDate()));

		$db->setQuery($query);

		$id = $db->loadObject();

		if ($id)
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	/**
	 *
	 * Simple date check on expiry date.
	 *
	 * @param   Coupon  $coupon
	 *
	 * @return bool
	 *
	 * @since 1.7
	 */

	public static function isCouponInDate(Coupon $coupon)
	{

		$today  = new Date();
		$expiry = new Date($coupon->expiry_date);;

		if ($today->toUnix() < $expiry->toUnix())
		{
			return true;
		}

		return false;


	}


	/**
	 *
	 * checks to see if there is a coupon applied to this cart session
	 *
	 * @return bool
	 *
	 * @since 1.7
	 */


	public static function isCouponApplied()
	{

		$cookieId = Utilities::getCookieID();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_coupon_cart'));
		$query->where($db->quoteName('cookie_id') . ' = ' . $db->quote($cookieId));

		$db->setQuery($query);

		$appliedCoupon = $db->loadObject();

		if ($appliedCoupon)
		{
			return true;

		}

		return false;


	}

	/**
	 *
	 * * * Should this be moved?
	 *
	 *  Calculates the discount amount
	 *
	 * @param $subTotal
	 *
	 * @return float|int|mixed
	 *
	 * @since 1.7
	 */

	public static function calculateDiscount($subTotal)
	{

		//get the current applied coupon
		$coupon = self::getCurrentAppliedCoupon();

		//if there's a coupon applied
		if ($coupon)
		{
			if ($coupon->inDate)
			{

				// get and set the actual amount
				$total = $coupon->amount;

				if ($coupon->discount_type == 'freeship')
				{
					$total = Shipping::getTotalShippingFromPlugin();
				}

				// if the discount type is perc, then divide the number by 100
				if ($coupon->discount_type == 'perc')
				{
					$total = $subTotal * ($total / 100);
				}


				return $total;
			}

			return 0;

		}

		//No coupon Applied

		return 0;


	}


}
