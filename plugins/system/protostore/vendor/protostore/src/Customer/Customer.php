<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Customer;

// no direct access

defined('_JEXEC') or die('Restricted access');


class Customer
{

	public $id;
	public $j_user_id;
	public $name;
	public $email;
	public $published;
	public $j_user;
	public $orders;
	public $total_orders;
	public $order_total;
	public $order_total_integer;
	public $addresses;


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
	 * @throws \Brick\Money\Exception\UnknownCurrencyException
	 * @since 1.6
	 */

	private function init()
	{

		$this->j_user = CustomerFactory::getUser($this->j_user_id);

		$this->orders = CustomerFactory::getCustomersOrders($this->id);


		if ($this->orders)
		{
			$this->total_orders = count($this->orders);
		}
		else
		{
			$this->total_orders = 0;
		}


		$this->order_total         = CustomerFactory::getOrderTotal($this->orders);
		$this->order_total_integer = CustomerFactory::getOrderTotal($this->orders, true);
		$this->addresses           = CustomerFactory::getCustomerAddresses($this->id);
	}


}
