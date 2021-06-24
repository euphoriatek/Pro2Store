<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Order;

defined('_JEXEC') or die('Restricted access');


class OrderedProduct
{

	public int $id;
	public int $order_id;
	public int $j_item;
	public int $j_item_cat;
	public string $j_item_name;
	public ?string $item_options;
	public int $price_at_sale;
	public string $price_at_sale_formatted;
	public int $amount;




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
	 * @param $data
	 *
	 *
	 * @throws \Brick\Money\Exception\UnknownCurrencyException
	 * @since 1.6
	 */

	private function init($data)
	{

		// set all the formats for the money values.
		$this->price_at_sale_formatted = OrderFactory::intToFormat($this->price_at_sale, OrderFactory::getOrderCurrency($this->order_id));

	}


}
