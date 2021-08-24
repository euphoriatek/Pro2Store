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


class Country
{

	public $id;
	public $country_name;
	public $country_isocode_2;
	public $country_isocode_3;
	public $requires_vat;
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

	}


}
