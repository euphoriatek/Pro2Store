<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */


namespace Protostore\Coupon;

// no direct access
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Factory;
use Joomla\CMS\Date\Date;
use Protostore\Shipping\Shipping;
use Protostore\Shipping\ShippingFactory;
use Protostore\Utilities\Utilities;
use Protostore\Cart\Cart;

use stdClass;

class CouponFactory
{

	/**
	 * @param $id
	 *
	 * @return Coupon
	 *
	 * @since 1.5
	 */

	public static function get($id): ?Coupon
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
			return null;
		}

	}

	/**
	 * @param $couponCode
	 *
	 * @return Coupon
	 *
	 * @since 1.5
	 */

	public static function getByCode($couponCode): ?Coupon
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_discount'));
		$query->where($db->quoteName('coupon_code') . ' = ' . $db->quote($couponCode));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			return new Coupon($result);
		}
		else
		{
			return null;
		}

	}

	/**
	 *
	 * @return Coupon
	 *
	 * @since 1.5
	 */


	public static function getCurrentAppliedCoupon(): ?Coupon
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
				return null;
			}

		}
		else
		{
			return null;
		}

	}

	/**
	 * @param $couponCode
	 *
	 * @return bool
	 *
	 * @since 1.6
	 */


	public static function apply($couponCode): bool
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

	public static function remove(): bool
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


	public static function checkCouponValidity($couponCode): bool
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('id');
		$query->from($db->quoteName('#__protostore_discount'));
		$query->where($db->quoteName('coupon_code') . ' = ' . $db->quote($couponCode));
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
	 * @since 1.5
	 */

	public static function isCouponInDate(Coupon $coupon): bool
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
	 * @since 1.5
	 */


	public static function isCouponApplied(): bool
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
	 * @return float|int
	 *
	 * @throws \Exception
	 * @since 1.5
	 */

	public static function calculateDiscount(Cart $cart)
	{

		//get the current applied coupon
		/** @var Coupon $coupon */
		$coupon = self::getCurrentAppliedCoupon();

		//if there's a coupon applied
		if ($coupon)
		{
			if ($coupon->inDate)
			{

				// init total
				$total = 0;

				// get the total based on the discount type
				switch ($coupon->discount_type)
				{
					case '3':
						$total = ShippingFactory::getShipping($cart);
						break;
					case '2':
						$total = $cart->subtotalInt * ($coupon->percentage / 100);
						break;
					case '1':
						$total = $coupon->amount;
				}


				return $total;
			}

			return 0;

		}

		//No coupon Applied

		return 0;


	}


}
