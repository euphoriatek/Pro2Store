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


	public $id;
	public $product_id;
	public $optiontype;
	public $optionname;
	public $modifier;
	public $modifiertype;
	public $modifiervalue;
	public $optionsku;
	public $ordering;


	public $optiontypename;

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
		$this->optiontypename = ProductoptionFactory::getOptionTypeName($this->optiontype);
	}
}



