<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Address;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\Input\Input;
use Protostore\Country\CountryFactory;


class AddressFactory
{


	/**
	 *
	 * Gets the address based on the given ID.
	 *
	 * @param $id
	 *
	 * @return false|Address
	 *
	 * @since 1.6
	 */

	public static function get($id)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_customer_address'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{

			return new Address($result);
		}

		return false;

	}

	/**
	 *
	 * Gets the address as CSV string based on the given ID.
	 *
	 * @param $id
	 *
	 * @return string
	 *
	 * @since 1.6
	 */

	public static function getAsCSVFromId($id): string
	{

		$address = self::get($id);

		unset($address->id);
		unset($address->created);
		unset($address->customer_id);
		unset($address->zone);
		unset($address->country);

		$string = '';

		foreach ($address as $line)
		{
			$string .= $line . ", ";
		}

		return rtrim($string, ', ');


	}


	/**
	 * @param   int          $limit
	 * @param   int          $offset
	 * @param   string|null  $searchTerm
	 * @param   string       $orderBy
	 * @param   string       $orderDir
	 * @param   int|null     $customerId
	 * @param   int|null     $zoneId
	 * @param   int|null     $countryId
	 *
	 * @return array|false
	 * @since 1.6
	 */

	public static function getList(int $limit = 0, int $offset = 0, string $searchTerm = null, string $orderBy = 'name', string $orderDir = 'DESC', int $customerId = null, int $zoneId = null, int $countryId = null)
	{


		// init items
		$items = array();

		// get the Database
		$db = Factory::getDbo();

		// set initial query
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__protostore_customer_address'));


		// if there is a search term, iterate over the columns looking for a match
		if ($searchTerm)
		{
			$query->where($db->quoteName('name') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');
			$query->where($db->quoteName('address1') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');
			$query->where($db->quoteName('address2') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));
			$query->where($db->quoteName('address3') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));
			$query->where($db->quoteName('town') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));
		}

		// filter by the customers id
		if ($customerId)
		{
			$query->where($db->quoteName('customer_id') . ' = ' . $db->quote($customerId));
		}
		// filter by the zone id
		if ($zoneId)
		{
			$query->where($db->quoteName('zone') . ' = ' . $db->quote($zoneId));
		}

		// filter by the country id
		if ($countryId)
		{
			$query->where($db->quoteName('country') . ' = ' . $db->quote($countryId));
		}

		$query->order($orderBy . ' ' . $orderDir);

		$db->setQuery($query, $offset, $limit);

		$results = $db->loadAssocList();

//		return $results;

		// only proceed if there's any rows
		if ($results)
		{
			// iterate over the array of objects, initiating the Class.
			foreach ($results as $result)
			{
				$items[] = new Address($result);

			}

			return $items;
		}

		return false;

	}

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 1.6
	 */


	public static function saveFromInputData(Input $data)
	{

		$address = json_decode($data->getString('address'));

		unset($address->zones);
		unset($address->zone_name);
		unset($address->country_name);
		unset($address->created);
		unset($address->address_as_csv);
		unset($address->edit);


		$result = Factory::getDbo()->updateObject('#__protostore_customer_address', $address, 'id');

		if ($result)
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	/**
	 * @param $zone_id
	 *
	 * @return string
	 *
	 * @since version
	 */


	public static function getZoneName($zone_id): string
	{

		if ($zone = CountryFactory::getZone($zone_id))
		{
			return $zone->zone_name;
		}
		else
		{
			return '';
		}


	}

	/**
	 * @param $country_id
	 *
	 * @return string
	 *
	 * @since version
	 */

	public static function getCountryName($country_id): string
	{

		if ($country = CountryFactory::get($country_id))
		{
			return $country->country_name;
		}
		else
		{
			return '';
		}


	}

	/**
	 *
	 * Gets the CSV from the given full address object
	 *
	 * @param   Address  $address
	 *
	 * @return string
	 *
	 * @since 1.6
	 */


	public static function getAddressAsCSV(Address $address)
	{

		// clone the $address to prevent actual values being unset
		$clone = clone $address;

		unset($clone->id);
		unset($clone->created);
		unset($clone->customer_id);
		unset($clone->zone);
		unset($clone->country);

		$string = '';

		foreach ($clone as $line)
		{
			if (!empty($line))
			{
				$string .= $line . ", ";
			}

		}

		return rtrim($string, ', ');

	}


}
