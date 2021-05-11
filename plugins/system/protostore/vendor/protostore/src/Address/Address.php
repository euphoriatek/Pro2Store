<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Address;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Protostore\Cart\CartFactory;
use Protostore\Utilities\Utilities;
use Protostore\Customer\Customer;
use Protostore\Cart\Cart;
use stdClass;

class Address
{

	protected $db;

	public $id;
	public $name;
	public $customer_id;
	public $address1;
	public $address2;
	public $address3;
	public $town;
	public $zone;
	public $zone_id;
	public $zone_name;
	public $country;
	public $country_id;
	public $country_name;
	public $country_2digit;
	public $postcode;
	public $phone;
	public $email;
	public $mobile_phone;
	public $address_as_string;
	public $isAssignedShipping;
	public $isAssignedBilling;


	public function __construct($addressid)
	{

		$this->db = Factory::getDbo();
		$this->initAddress($addressid);
	}

	/**
	 *
	 * Initialises the address object using the Address ID.
	 *
	 * @param $addressid
	 *
	 * @return false
	 * @since 1.0
	 *
	 */

	private function initAddress($addressid)
	{
		$query = $this->db->getQuery(true);

		$query->select('*');
		$query->from($this->db->quoteName('#__protostore_customer_address'));
		$query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($addressid));

		$this->db->setQuery($query);

		$address = $this->db->loadObject();

		if ($address)
		{
			$this->id                 = $addressid;
			$this->name               = $address->name;
			$this->customer_id        = $address->customer_id;
			$this->address1           = $address->address1;
			$this->address2           = $address->address2;
			$this->address3           = $address->address3;
			$this->town               = $address->town;
			$this->zone_id            = $address->zone;
			$this->zone_name          = $this->getZoneNameById($address->zone);
			$this->country_id         = $address->country;
			$this->country_name       = $this->getCountryNameByid($address->country);
			$this->country_2digit     = $this->getCountry2DigitByid($address->country);
			$this->postcode           = $address->postcode;
			$this->phone              = $address->phone;
			$this->email              = $address->email;
			$this->address_as_string  = implode(', ', $this->getAddressDetailsAsObject());
			$this->isAssignedShipping = $this->isAssigned('shipping');
			$this->isAssignedBilling  = $this->isAssigned('billing');
		}
		else
		{
			return false;
		}


	}

	/**
	 * @param   mixed  $name
	 *
	 * @since 1.0
	 */
	public function setName($name)
	{
		$this->name = $name;
	}


	/**
	 * @param   mixed  $address1
	 *
	 * @since 1.0
	 */
	public function setCustomerId($customer_id)
	{
		$this->customer_id = $customer_id;
	}

	/**
	 * @param   mixed  $address1
	 *
	 * @since 1.0
	 */
	public function setAddress1($address1)
	{
		$this->address1 = $address1;
	}


	/**
	 * @param   mixed  $address2
	 *
	 * @since 1.0
	 */
	public function setAddress2($address2)
	{
		$this->address2 = $address2;
	}

	/**
	 * @param   mixed  $address3
	 *
	 * @since 1.0
	 */
	public function setAddress3($address3)
	{
		$this->address3 = $address3;
	}


	/**
	 * @param   mixed  $country
	 *
	 * @since 1.0
	 */
	public function setCountry($country)
	{
		$this->country_id = $country;
	}

	/**
	 * @param   mixed  $email
	 *
	 * @since 1.0
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 * @param   mixed  $mobile_phone
	 *
	 * @since 1.0
	 */
	public function setMobilePhone($mobile_phone)
	{
		$this->mobile_phone = $mobile_phone;
	}

	/**
	 * @param   mixed  $phone  \
	 *
	 * @since 1.0
	 */
	public function setPhone($phone)
	{
		$this->phone = $phone;
	}

	/**
	 * @param   mixed  $postcode
	 *
	 * @since 1.0
	 */
	public function setPostcode($postcode)
	{
		$this->postcode = $postcode;
	}

	/**
	 * @param   mixed  $zone
	 *
	 * @since 1.0
	 */
	public function setZone($zone)
	{
		$this->zone_id = $zone;
	}

	/**
	 * @param   mixed  $town
	 *
	 * @since 1.0
	 */
	public function setTown($town)
	{
		$this->town = $town;
	}


	/***
	 *
	 * GETTERS
	 *
	 *
	 */


	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getCustomerId()
	{
		return $this->customer_id;
	}


	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getAddress1()
	{
		return $this->address1;
	}


	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getAddress2()
	{
		return $this->address2;
	}

	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getAddress3()
	{
		return $this->address3;
	}


	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getCountryId()
	{
		return $this->country_id;
	}


	/**
	 * @return mixed
	 *
	 * @since      0.1
	 * @deprecated 1.0
	 *
	 */
	public function getCountry()
	{
		return $this->country_id;
	}


	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getCountryName()
	{
		return $this->country_name;
	}

	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getCountryNameByid($country_id)
	{
		$query = $this->db->getQuery(true);

		$query->select('country_name');
		$query->from($this->db->quoteName('#__protostore_country'));
		$query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($country_id));

		$this->db->setQuery($query);

		return $this->db->loadResult();
	}

	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getCountry2DigitByid($country_id)
	{
		$query = $this->db->getQuery(true);

		$query->select('country_isocode_2');
		$query->from($this->db->quoteName('#__protostore_country'));
		$query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($country_id));

		$this->db->setQuery($query);

		return $this->db->loadResult();
	}

	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getZoneNameById($zone_id)
	{
		$query = $this->db->getQuery(true);

		$query->select('zone_name');
		$query->from($this->db->quoteName('#__protostore_zone'));
		$query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($zone_id));

		$this->db->setQuery($query);

		return $this->db->loadResult();
	}

	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getMobilePhone()
	{
		return $this->mobile_phone;
	}

	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getPhone()
	{
		return $this->phone;
	}

	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getPostcode()
	{
		return $this->postcode;
	}

	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getTown()
	{
		return $this->town;
	}

	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getZoneId()
	{
		return $this->zone_id;
	}

	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getZoneName()
	{
		return $this->zone_name;
	}


	/**
	 *
	 * Returns the selected address as an iterable object.
	 *
	 * @return array
	 *
	 * @since 1.0
	 */


	public function getAddressDetailsAsObject()
	{

		$address = array();

		$address[] = $this->getAddress1();
		$address[] = $this->getAddress2();
		$address[] = $this->getAddress3();
		$address[] = $this->getTown();
		$address[] = $this->getZoneName();
		$address[] = $this->getCountryName();
		$address[] = $this->getPostcode();
		$address[] = $this->getEmail();
		$address[] = $this->getMobilePhone();
		$address[] = $this->getPhone();

		return $address;

	}

	/**
	 *
	 * Returns the selected address as a string.
	 *
	 * @return array
	 *
	 * @since 1.0
	 */


	public function getAddressDetailsAsString()
	{

		$address = '';

		$address .= $this->getAddress1() . " ";
		$address .= $this->getAddress2() . " ";
		$address .= $this->getAddress3() . " ";
		$address .= $this->getTown() . " ";
		$address .= $this->getZoneName() . " ";
		$address .= $this->getCountryName() . " ";
		$address .= $this->getPostcode() . " ";
		$address .= $this->getEmail() . " ";
		$address .= $this->getMobilePhone() . " ";
		$address .= $this->getPhone() . " ";

		return $address;

	}

	private function isAssigned($type)
	{


		$query = $this->db->getQuery(true);

		$query->select($type . '_address_id');
		$query->from($this->db->quoteName('#__protostore_cart'));
		$query->where($this->db->quoteName('id') . ' = ' . $this->db->quote(Cart::getCurrentCartId()));

		$this->db->setQuery($query);

		$result = $this->db->loadResult();

		if ($result == $this->id)
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
	 * This function assigns the customers default address to the order
	 *
	 *
	 * @return false|void
	 *
	 * @since 1.0
	 */


	public static function assignDefaultAddressToOrder()
	{


		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('shipping_address_id');
		$query->from($db->quoteName('#__protostore_cart'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote(CartFactory::get()->id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			//there is already an address for this order so return
			return;
		}
		else
		{

			// there is no address for this order so let's get one.

			//get a user address
			$custmomer = new Customer();
			$addresses = $custmomer->getAddresses();

			if ($addresses)
			{

				$cart = CartFactory::get();

				$cart->shipping_address_id = $addresses[0]->id;
				$cart->billing_address_id  = $addresses[0]->id;

				CartFactory::save($cart);

				if ($result)
				{
					return false;
				}
			}

			return false;
		}

	}


	/**
	 *
	 * This static function returns the assigned shipping address id for the current order.
	 *
	 *
	 * @return false|mixed
	 * @since 1.0
	 *
	 *
	 *
	 */


	public static function getAssignedShippingAddressID()
	{


		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('shipping_address_id');
		$query->from($db->quoteName('#__protostore_cart'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote(CartFactory::get()->id));

		$db->setQuery($query);

		$result = $db->loadResult();

		if ($result)
		{
			return $result;
		}
		else
		{
			return false;
		}


	}


	/**
	 *
	 * This static function returns the assigned billing address for the current order.
	 *
	 *
	 * @return false|mixed
	 * @since 1.0
	 *
	 */


	public static function getAssignedBillingAddressID()
	{


		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('billing_address_id');
		$query->from($db->quoteName('#__protostore_cart'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote(CartFactory::get()->id));

		$db->setQuery($query);

		$result = $db->loadResult();

		if ($result)
		{
			return $result;
		}
		else
		{
			return false;
		}

	}


	/**
	 *
	 * This static function determines if there is an address assigned to the current order.
	 *
	 *
	 * @return false|mixed
	 * @since 1.0
	 *
	 */


	public static function isAddressAssigned($addressid, $type)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select($type . '_address_id');
		$query->from($db->quoteName('#__protostore_cart'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote(CartFactory::get()->id));

		$db->setQuery($query);

		$result = $db->loadResult();

		if ($result == $addressid)
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
	 * This static function determines if the billing address is different from the shipping address.
	 *
	 *
	 * @return false|mixed
	 * @since 1.0
	 *
	 */


	public static function doesOrderHaveUniqueBillingAddressAssigned()
	{

		$cart = CartFactory::get();

		if ($cart->billing_address_id === $cart->shipping_address_id)
		{
			return false;
		}
		else
		{
			return true;
		}


	}

	/**
	 * function save()
	 *
	 * Allows the address to be updated from any part of the Pro2Store system
	 *
	 *
	 * @return bool
	 *
	 * @since 1.0
	 */


	public function save()
	{

		$date = Utilities::getDate();

		$object               = new stdClass();
		$object->id           = $this->id;
		$object->name         = $this->name;
		$object->customer_id  = $this->customer_id;
		$object->address1     = $this->address1;
		$object->address2     = $this->address2;
		$object->address3     = $this->address3;
		$object->town         = $this->town;
		$object->zone         = $this->zone_id;
		$object->country      = $this->country_id;
		$object->postcode     = $this->postcode;
		$object->phone        = $this->phone;
		$object->email        = $this->email;
		$object->mobile_phone = $this->mobile_phone;

		$result = $this->db->updateObject('#__protostore_customer_address', $object, 'id');

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
	 *
	 * This function allows for duplication of an address via its ID.
	 *
	 * @param $id
	 *
	 * @return string
	 * @since 1.0
	 */


	public static function duplicate($id)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_customer_address'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		$date            = Utilities::getDate();
		$result->id      = 0;
		$result->created = $date;

		$insert = $db->insertObject('#__protostore_customer_address', $result);

		if ($insert)
		{
			return 'ok';
		}
		else
		{
			return 'ko';
		}


	}


	/**
	 *
	 * This function allows for deletion of an address via its ID.
	 *
	 * @param $id
	 *
	 * @return string
	 * @since 1.0
	 */


	public static function delete($id)
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('id') . ' = ' . $db->quote($id)
		);

		$query->delete($db->quoteName('#__protostore_customer_address'));
		$query->where($conditions);

		$db->setQuery($query);

		$result = $db->execute();

		if ($result)
		{
			return 'ok';
		}
		else
		{
			return 'ko';
		}

	}


	/**
	 *
	 * This funciton takes in address data and creates a new address from it.
	 *
	 * @param $data
	 *
	 * @return false|mixed
	 *
	 * @since 1.0
	 */

	public static function addNewAddress($data)
	{
		$db   = Factory::getDbo();
		$date = Utilities::getDate();
		// init
		$data = (object) $data;
		$data = Utilities::prepareTaskData($data);


		$object     = new stdClass();
		$object->id = 0;
		if (Factory::getUser()->guest)
		{
			$object->customer_id = 0;
		}
		else
		{
			$object->customer_id = Utilities::getCustomerIdByCurrentUserId();
		}

		$object->name = $data->name;
		if (isset($data->address1))
		{
			$object->address1 = $data->address1;
		}
		if (isset($data->address2))
		{
			$object->address2 = $data->address2;
		}
		if (isset($data->address3))
		{
			$object->address3 = $data->address3;
		}
		if (isset($data->town))
		{
			$object->town = $data->town;
		}
		if (isset($data->zone))
		{
			$object->zone = $data->zone;
		}
		if (isset($data->country))
		{
			$object->country = $data->country;
		}
		if (isset($data->postcode))
		{
			$object->postcode = $data->postcode;
		}
		if (isset($data->phone))
		{
			$object->phone = $data->phone;
		}
		if (isset($data->email))
		{
			$object->email = $data->email;
		}
		if (isset($data->mobile_phone))
		{
			$object->mobile_phone = $data->mobile_phone;
		}


		$object->created = $date;

		$result = $db->insertObject('#__protostore_customer_address', $object);

		if ($result)
		{
			return $db->insertid();
		}
		else
		{
			return false;
		}

	}


	public static function setAsCartDefault($id)
	{

		if (self::isAddressAssigned($id, 'shipping') && self::isAddressAssigned($id, 'billing'))
		{
			return;
		}
		else
		{

			$cart = CartFactory::get();

			$cart->shipping_address_id = $id;
			$cart->billing_address_id  = $id;

			CartFactory::save($cart);

		}


	}

}
