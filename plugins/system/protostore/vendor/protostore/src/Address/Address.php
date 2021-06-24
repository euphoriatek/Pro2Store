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


class Address
{

	public int $id;
	public int $customer_id;
	public string $name;
	public ?string $address1;
	public ?string $address2;
	public ?string $address3;
	public ?string $town;
	public ?int $zone;
	public ?string $zone_name;
	public ?int $country;
	public ?string $country_name;
	public ?string $postcode;
	public ?string $phone;
	public ?string $email;
	public ?string $mobile_phone;
	public string $created;



	public function __construct($data)
	{

		if ($data)
		{
			$this->hydrate($data);
			$this->init($data);
		}

	}

	/**
	 *
	 * Function to simply "hydrate" the database values directly to the class parameters.
	 *
	 * @param $data
	 *
	 *
	 * @since 1.6
	 */

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

	/**
	 *
	 * Function to "hydrate" all non-database values.
	 *
	 * @param $data
	 *
	 *
	 * @since 1.6
	 */

	private function init($data)
	{

		$this->zone_name = AddressFactory::getZoneName($this->zone);
		$this->country_name = AddressFactory::getCountryName($this->zone);


	}



}
