<?php

/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

namespace Protostore\Product;

defined('_JEXEC') or die('Restricted access');



class PhysicalProduct extends Product
{


	public $shipping_mode;
	public $flatfee;
	public $weight;
	public $weight_unit;
	public int $manage_stock;
	public int $stock;
	public int $maxPerOrder;




	public function __construct($data)
	{

		if ($data)
		{
			$this->hydrate($data);
			$this->init($data);
			parent::__construct($data);
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

	}



}
