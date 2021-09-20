<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */


namespace Protostore\Country;

// no direct access
defined('_JEXEC') or die('Restricted access');


class Zone
{

	public $id;
	public $country_id;
	public $country_name;
	public $zone_name;
	public $zone_isocode;
	public $taxrate;
	public $published;


	public function __construct($data)
	{

		if ($data)
		{
			$this->hydrate($data);
			$this->init();
		}

	}

	private function hydrate($data)
	{
		foreach ($data as $key => $value)
		{

			if (property_exists($this, $key))
			{
				$this->{$key} = $value;
			}

		}
	}

	private function init()
	{

		$country = CountryFactory::get($this->country_id);

		if ($country)
		{
			$this->country_name = $country->country_name;
		}


	}


}
