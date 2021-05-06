<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Zone;
// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

class Zone
{

	private $db;

	public $id;
	public $country_id;
	public $country_name;
	public $country;
	public $zone_name;
	public $zone_isocode;
	public $taxrate;
	public $published;

	public function __construct($id)
	{
		$this->db = Factory::getDbo();

		$this->initZone($id);


	}

	private function initZone($id)
	{

		$query = $this->db->getQuery(true);

		$query->select('*');
		$query->from($this->db->quoteName('#__protostore_zone'));
		$query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($id));

		$this->db->setQuery($query);

		$result = $this->db->loadObject();

		$this->id           = $id;
		$this->country_id   = $result->country_id;
		$this->country      = $this->getCountry();
		$this->country_name = $this->country->country_name;
		$this->zone_name    = $result->zone_name;
		$this->zone_isocode = $result->zone_isocode;
		$this->taxrate      = $result->taxrate;
		$this->published    = $result->published;

	}

	private function getCountry()
	{

		$query = $this->db->getQuery(true);

		$query->select('*');
		$query->from($this->db->quoteName('#__protostore_country'));
		$query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($this->country_id));

		$this->db->setQuery($query);

		return $this->db->loadObject();

	}


	public static function getAllZones()
	{

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_zone'));
		$query->where($db->quoteName('published') . ' = 1');

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	public static function getAllCountries()
	{

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_country'));
		$query->where($db->quoteName('published') . ' = 1');
		$query->order('country_name ASC');

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	public static function getZonesByCountryId($country_id)
	{

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_zone'));
		$query->where($db->quoteName('country_id') . ' = ' . $db->quote($country_id));
		$query->where($db->quoteName('country_id') . ' = ' . $db->quote($country_id));
		$query->where($db->quoteName('published') . ' = 1');
		$query->order('zone_name ASC');

		$db->setQuery($query);

		return $db->loadObjectList();
	}


}
