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


class CountryFactory
{


	/**
	 *
	 * Gets the currency based on the given ID.
	 *
	 * @param $id
	 *
	 * @return false|Country
	 *
	 * @since 1.6
	 */

	public static function get($id)
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

		return false;

	}


	/**
	 * @param $id
	 *
	 * @return false|Zone
	 *
	 * @since version
	 */

	public static function getZone($id)
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

		return false;

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
	 * @return array|false
	 * @since 1.5
	 */

	public static function getList(int $limit = 0, int $offset = 0, bool $publishedOnly = false, string $searchTerm = null, string $orderBy = 'published', string $orderDir = 'DESC')
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

		return false;

	}

	public static function getZoneList(int $limit = 0, int $offset = 0, bool $publishedOnly = false, string $searchTerm = null, int $country_id = null, string $orderBy = 'published', string $orderDir = 'DESC')
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


		// if there is a search term, iterate over the columns looking for a match
		if ($searchTerm)
		{
			$query->where($db->quoteName('zone_name') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');
			$query->where($db->quoteName('zone_isocode') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));
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

		return false;

	}






}
