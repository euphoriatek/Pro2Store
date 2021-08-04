<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Shippingrate;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Brick\Math\BigDecimal;
use Brick\Money\Exception\UnknownCurrencyException;
use Joomla\CMS\Factory;
use Joomla\Input\Input;
use Protostore\Country\CountryFactory;
use Protostore\Currency\CurrencyFactory;
use Protostore\Utilities\Utilities;


class ShippingrateFactory
{


	/**
	 *
	 * Gets the Shipping Rate based on the given ID.
	 *
	 * @param $id
	 *
	 * @return Shippingrate
	 *
	 * @since 1.6
	 */

	public static function get($id): ?Shippingrate
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_shipping_rate'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{

			return new Shippingrate($result);
		}

		return null;

	}


	/**
	 * @param $id
	 *
	 * @return Shippingrate
	 *
	 * @since version
	 */

	public static function getZone($id): ?Shippingrate
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_shipping_rate'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{

			return new Shippingrate($result);
		}

		return null;

	}


	/**
	 * @param   int       $limit
	 * @param   int       $offset
	 * @param   bool      $publishedOnly
	 * @param   int|null  $country_id
	 * @param   string    $orderBy
	 * @param   string    $orderDir
	 *
	 *
	 * @return array|false
	 * @since 1.6
	 */

	public static function getList(int $limit = 0, int $offset = 0, bool $publishedOnly = false, int $country_id = null, string $orderBy = 'published', string $orderDir = 'DESC')
	{

		// init items
		$items = array();

		// get the Database
		$db = Factory::getDbo();

		// set initial query
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__protostore_shipping_rate'));

		// only get published items based on $publishedOnly boolean
		if ($publishedOnly)
		{
			$query->where($db->quoteName('published') . ' = 1');
		}


		// if there is a search term, iterate over the columns looking for a match
		if ($country_id)
		{
			$query->where($db->quoteName('country_id') . ' LIKE ' . $db->quote($country_id));
		}

		$query->order($orderBy . ' ' . $orderDir);

		$db->setQuery($query, $offset, $limit);

		$results = $db->loadObjectList();

		// only proceed if there's any rows
		if ($results)
		{
			// iterate over the array of objects, initiating the Class.
			foreach ($results as $result)
			{
				$items[] = new Shippingrate($result);

			}

			return $items;
		}

		return false;

	}


	/**
	 * @param   int          $int
	 * @param   string|null  $currency
	 *
	 * @return string
	 *
	 * @throws UnknownCurrencyException
	 * @since 1.6
	 */

	public static function intToFormat(int $int, string $currency = null): string
	{

		return CurrencyFactory::formatNumberWithCurrency($int, $currency);

	}


	/**
	 * @param   Input  $data
	 *
	 *
	 * @return Shippingrate
	 * @since 1.6
	 */


	public static function saveFromInputData(Input $data): Shippingrate
	{


		if ($id = $data->getInt('itemid', null))
		{

			$current = self::get($id);

			$current->country_id          = $data->getString('country_id', $current->country_id);
			$current->weight_from        = $data->getInt('weight_from', $current->weight_from);
			$current->weight_to    = $data->getString('weight_to', $current->weight_to);

			// with prices... we need to run it through the Brick system first.
			$costFloat = $data->getFloat('costFloat', $current->costFloat);

			if ($costFloat)
			{
				$current->cost = CurrencyFactory::toInt($costFloat);
			}
			else
			{
				$current->cost = 0;
			}
			// with prices... we need to run it through the Brick system first.
			$handling_costFloat = $data->getFloat('handling_costFloat', $current->handling_costFloat);

			if ($handling_costFloat)
			{
				$current->handling_cost = CurrencyFactory::toInt($handling_costFloat);
			}
			else
			{
				$current->handling_cost = 0;
			}

			$current->published   = $data->getInt('published', $current->published);


			if (self::commitToDatabase($current))
			{
				return $current;
			}

		}
		else
		{

			if ($item = self::createFromInputData($data))
			{
				return $item;
			}

		}


	}

	/**
	 * @param   Input  $data
	 *
	 * @return Shippingrate|bool
	 *
	 * @since 1.6
	 */


	private static function createFromInputData(Input $data): Shippingrate
	{

//		$db = Factory::getDbo();
//
//		$item                = new stdClass();
//		$item->name          = $data->getString('name');
//		$item->amount        = $data->getInt('amount');
//		$item->discount_type = $data->get('discount_type');
//		$item->percentage    = $data->getString('percentage');
//		$item->coupon_code   = $data->getString('coupon_code');
//		$item->expiry_date   = $data->getString('expiry_date');
//		$item->published     = $data->get('published');
//		$item->created       = Utilities::getDate();
//		$item->created_by    = Factory::getUser()->id;
//		$item->modified      = Utilities::getDate();
//		$item->modified_by   = Factory::getUser()->id;
//
//
//		$result = $db->insertObject('#__protostore_discount', $item);
//
//		if ($result)
//		{
//			return self::get($db->insertid());
//		}
//
//		return false;


	}

	/**
	 * @param   Shippingrate  $item
	 *
	 *
	 * @return bool
	 * @since 1.6
	 */


	private static function commitToDatabase(Shippingrate $item): bool
	{

		$db = Factory::getDbo();

		$insert = new stdClass();

		$insert->id          = $item->id;
		$insert->country_id        = $item->country_id;
		$insert->weight_from      = $item->weight_from;
		$insert->weight_to  = $item->weight_to;
		$insert->cost = $item->cost;
		$insert->handling_cost = $item->handling_cost;
		$insert->published   = $item->published;

		$result = $db->updateObject('#__protostore_shipping_rate', $insert, 'id');

		if ($result)
		{
			return true;
		}

		return false;

	}
	
	

	/**
	 * @param $price
	 *
	 * @return BigDecimal
	 *
	 * @throws UnknownCurrencyException
	 * @since version
	 */

	public static function getFloat($price): BigDecimal
	{

		return CurrencyFactory::toFloat($price);

	}

	/**
	 * @param   int  $country_id
	 *
	 * @return string
	 *
	 * @since 1.6
	 */

	public static function getCountryName(int $country_id): string
	{

		return CountryFactory::get($country_id)->country_name;

	}


}
