<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Address;

// no direct access
defined('_JEXEC') or die('Restricted access');


class Address
{
	public $id;
	public $customer_id;
	public $name;
	public $address1;
	public $address2;
	public $address3;
	public $town;
	public $zone;
	public $zone_name;
	public $country;
	public $country_name;
	public $postcode;
	public $phone;
	public $email;
	public $mobile_phone;
	public $created;
	public $address_as_csv;
	public $isAssignedShipping;
	public $isAssignedBilling;



	public function __construct($data)
	{

		if ($data)
		{
			$this->hydrate($data);
			$this->init();
		}

	}

	/**
	 *
	 * Function to simply "hydrate" the database values directly to the class parameters.
	 *
	 * @param $data
	 *
	 *
	 * @since 2.0
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
	 *
	 * @since 2.0
	 */

	private function init()
	{

		$this->zone_name = AddressFactory::getZoneName($this->zone);
		$this->country_name = AddressFactory::getCountryName($this->country);
		$this->address_as_csv = AddressFactory::getAddressAsCSV($this);
		$this->isAssignedShipping = AddressFactory::checkAssigned($this->id, 'shipping');
		$this->isAssignedBilling = AddressFactory::checkAssigned($this->id, 'billing');


	}



}
