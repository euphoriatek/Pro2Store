<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

namespace Protostore\Productoption;


defined('_JEXEC') or die('Restricted access');

class Productoption
{


	public int $id;
	public int $product_id;
	public string $optiontype;
	public string $optionname;
	public string $modifier;
	public string $modifiertype;
	public int $modifiervalue;
	public $modifiervalueFloat;
	public ?string $modifiervalue_translated;
	public string $optionsku;
	public int $ordering;


	public $optiontypename;

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
		$this->optiontypename = ProductoptionFactory::getOptionTypeName($this->optiontype);
		if($this->modifiertype === 'amount'){
			$this->modifiervalueFloat = ProductoptionFactory::getFloat($this->modifiervalue);
		} else {
			$this->modifiervalueFloat = $this->modifiervalue;
		}



		$this->modifiervalue_translated = ProductoptionFactory::translateModifierValue($this->modifiervalue, $this->modifiertype);


	}
}



