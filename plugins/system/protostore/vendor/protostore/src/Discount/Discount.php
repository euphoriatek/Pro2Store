<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Discount;
// no direct access
defined('_JEXEC') or die('Restricted access');


class Discount
{


	//TODO - WRITE A FUNCTION IN THE INSTALL/UPGRADE SCRIPT TO CONVERT OLD DISCOUNT STRUCTURE TO THIS NEW ONE.


	public $id;
	public $name;
	public $amount;
	public $amount_formatted;
	public $percentage;
	public $coupon_code;
	public $expiry_date;
	public $discount_type;
	public $discount_type_string;
	public $published;
	public $created;
	public $modified;
	public $created_by;
	public $modified_by;


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
	 * @since 2.0
	 */

	private function init()
	{

		$this->discount_type_string = DiscountFactory::getDiscountTypeAsString($this->discount_type);
		$this->amount_formatted = DiscountFactory::getDiscountAmountFormatted($this->amount, $this->percentage, $this->discount_type);

	}
}
