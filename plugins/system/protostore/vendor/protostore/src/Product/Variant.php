<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Product;

use Exception;
use stdClass;

defined('_JEXEC') or die('Restricted access');


class Variant
{


	/**
	 * @var array $variants
	 * @since 1.6
	 */

	public $variants;


	/**
	 * @var array $variantList
	 * @since 1.6
	 */

	public $variantList;


	/**
	 * @var array $default
	 * @since 1.6
	 */

	public $default;


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
	 *
	 * @throws Exception
	 * @since 1.6
	 */

	private function init()
	{

		if($this->variants) {
			$this->variants    = ProductFactory::attachVariantLabels($this->variants);
			$this->variantList = ProductFactory::processVariantData($this->variantList);
			$this->default     = ProductFactory::getVariantDefault($this->variantList);
		}




	}


}
