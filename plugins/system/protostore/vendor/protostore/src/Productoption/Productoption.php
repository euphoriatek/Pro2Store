<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// no direct access

namespace Protostore\Productoption;


defined('_JEXEC') or die('Restricted access');

class Productoption
{


	public $id;
	public $product_id;
	public $option_name;
	public $modifier_type;
	public $modifier_value;
	public $modifier_valueFloat;
	public $modifier_value_translated;



	/**
	 * @throws \Brick\Money\Exception\UnknownCurrencyException
	 * @since 1.6
	 */
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



		$this->modifier_valueFloat = ProductoptionFactory::processModifierValue($this->modifier_value);
		$this->modifier_value_translated = ProductoptionFactory::translateModifierValue($this->modifier_value, $this->modifier_type);


	}
}



