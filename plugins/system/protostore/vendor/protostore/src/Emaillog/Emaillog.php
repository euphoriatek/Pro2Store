<?php

/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Emaillog;

defined('_JEXEC') or die('Restricted access');


class Emaillog
{

	public int $id;
	public string $emailaddress;
	public string $emailtype;
	public string $sentdate;
	public int $customer_id;
	public ?string $customer_name;
	public int $order_id;
	public ?string $order_number;
	public int $created_by;
	public ?string $created_by_name;
	public int $modified_by;
	public string $created;
	public string $modified;


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
	 * @since 1.6
	 */

	private function init()
	{

		$this->customer_name = EmaillogFactory::getCustomerName($this->customer_id);
		$this->order_number = EmaillogFactory::getOrderNumber($this->order_id);
		$this->created_by_name = EmaillogFactory::getCreatedName($this->created_by);

	}


}
