<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Discount;
// no direct access
defined('_JEXEC') or die('Restricted access');


class Discount
{


	//TODO - WRITE A FUNCTION IN THE INSTALL/UPGRADE SCRIPT TO CONVERT OLD DISCOUNT STRUCTURE TO THIS NEW ONE.


    public int $id;
	public string $name;
    public ?int $amount;
    public ?float $percentage;
    public string $coupon_code;

    public string $expiry_date;
    public int $discount_type;
    public string $discount_type_string;
    public int $published;
    public string $created;
    public string $modified;
    public ?int $created_by;
    public ?int $modified_by;


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

		$this->discount_type_string = DiscountFactory::getDiscountTypeAsString($this->discount_type);

	}
}
