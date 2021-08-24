<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Country;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\Input\Input;


use stdClass;

class CountryFactory
{


	/**
	 *
	 * Gets the currency based on the given ID.
	 *
	 * @param $id
	 *
	 * @return Country
	 *
	 * @since 1.6
	 */

	public static function get($id): ?Country
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_country'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{

			return new Country($result);
		}

		return null;

	}


	/**
	 * @param $id
	 *
	 * @return Zone
	 *
	 * @since 1.6
	 */

	public static function getZone($id): ?Zone
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_zone'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{

			return new Zone($result);
		}

		return null;

	}


	/**
	 * @param   int          $limit
	 * @param   int          $offset
	 * @param   bool         $publishedOnly
	 * @param   string|null  $searchTerm
	 * @param   string       $orderBy
	 * @param   string       $orderDir
	 *
	 *
	 * @return array
	 * @since 1.6
	 */

	public static function getList(int $limit = 0, int $offset = 0, bool $publishedOnly = false, string $searchTerm = null, string $orderBy = 'published', string $orderDir = 'DESC'): ?array
	{

		// init items
		$items = array();

		// get the Database
		$db = Factory::getDbo();

		// set initial query
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__protostore_country'));

		// only get published items based on $publishedOnly boolean
		if ($publishedOnly)
		{
			$query->where($db->quoteName('published') . ' = 1');
		}


		// if there is a search term, iterate over the columns looking for a match
		if ($searchTerm)
		{
			$query->where($db->quoteName('country_name') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');
			$query->where($db->quoteName('country_isocode_2') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');
			$query->where($db->quoteName('country_isocode_3') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));
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
				$items[] = new Country($result);

			}

			return $items;
		}

		return null;

	}

	/**
	 * @param   int          $limit
	 * @param   int          $offset
	 * @param   false        $publishedOnly
	 * @param   string|null  $searchTerm
	 * @param   int|null     $country_id
	 * @param   string       $orderBy
	 * @param   string       $orderDir
	 *
	 * @return array
	 *
	 * @since 1.6
	 */

	public static function getZoneList(int $limit = 0, int $offset = 0, bool $publishedOnly = false, string $searchTerm = null, int $country_id = null, string $orderBy = 'published', string $orderDir = 'DESC'): ?array
	{

		// init items
		$items = array();

		// get the Database
		$db = Factory::getDbo();

		// set initial query
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__protostore_zone'));

		// only get published items based on $publishedOnly boolean
		if ($publishedOnly)
		{
			$query->where($db->quoteName('published') . ' = 1');
		}

		if ($country_id)
		{
			$query->where($db->quoteName('country_id') . ' = ' . $country_id);
		}


		if ($searchTerm)
		{
			$query->where($db->quoteName('zone_name') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));
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
				$items[] = new Zone($result);

			}

			return $items;
		}

		return null;

	}


	/**
	 *
	 * This method is called first and runs the check before calling other functions to commit the data.
	 *
	 * @param   Input  $data
	 *
	 *
	 * @return Country
	 * @since 1.6
	 */


	public static function saveFromInputData(Input $data)
	{


		if ($id = $data->json->getInt('jform_id', null))
		{

			$current = self::get($id);

			$current->country_name      = $data->json->getString('jform_country_name', $current->country_name);
			$current->country_isocode_2 = $data->json->getString('jform_country_isocode_2', $current->country_isocode_2);
			$current->country_isocode_3 = $data->json->getString('jform_country_isocode_3', $current->country_isocode_3);
			$current->requires_vat      = $data->json->getInt('jform_requires_vat', $current->requires_vat);
			$current->taxrate           = $data->json->getString('jform_taxrate', $current->taxrate);
			$current->published         = $data->json->getInt('jform_published', $current->published);


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

		return null;
	}

	/**
	 * @param   Input  $data
	 *
	 * @return Country
	 *
	 * @since 1.6
	 */


	private static function createFromInputData(Input $data): ?Country
	{

		$db = Factory::getDbo();

		$discount                    = new stdClass();
		$discount->country_name      = $data->json->getString('jform_country_name');
		$discount->country_isocode_2 = $data->json->getString('jform_country_isocode_2');
		$discount->country_isocode_3 = $data->json->getString('jform_country_isocode_3');
		$discount->requires_vat      = $data->json->getInt('jform_requires_vat');
		$discount->taxrate           = $data->json->getString('jform_taxrate');
		$discount->published         = $data->json->get('jform_published');


		$result = $db->insertObject('#__protostore_country', $discount);

		if ($result)
		{
			return self::get($db->insertid());
		}

		return null;


	}

	/**
	 * @param   Country  $item
	 *
	 * @return bool
	 * @since 1.6
	 */


	private static function commitToDatabase(Country $item): bool
	{

		$db = Factory::getDbo();

		$insert = new stdClass();

		$insert->id                = $item->id;
		$insert->country_name      = $item->country_name;
		$insert->country_isocode_2 = $item->country_isocode_2;
		$insert->country_isocode_3 = $item->country_isocode_3;
		$insert->requires_vat      = $item->requires_vat;
		$insert->taxrate           = $item->taxrate;
		$insert->published         = $item->published;

		$result = $db->updateObject('#__protostore_country', $insert, 'id');

		if ($result)
		{
			return true;
		}

		return false;

	}


}
