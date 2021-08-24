<?php
/**
 * @package   Pro2Store - Coupon
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */


namespace Protostore\Coupon;

// no direct access
defined('_JEXEC') or die('Restricted access');


class Coupon
{

	public $id;
	public $amount;
	public $couponcode;
	public $expiry_date;
	public $name;
	public $discount_type;
	public $inDate;


	public function __construct($data)
	{

		if ($data)
		{
			$this->hydrate($data);
			$this->init($data);
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


	private function init($data)
	{

		$this->inDate = CouponFactory::isCouponInDate($this);


	}


}
