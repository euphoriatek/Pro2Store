<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// no direct access
namespace Protostore\Variant;

use Exception;
use stdClass;

defined('_JEXEC') or die('Restricted access');


class Variant
{


	/**
	 * @var array $variants
	 * @since 2.0
	 */

	public $variants;


	/**
	 * @var array $variantList
	 * @since 2.0
	 */

	public $variantList;


	/**
	 * @var array $default
	 * @since 2.0
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
	 *
	 * @throws Exception
	 * @since 2.0
	 */

	private function init()
	{

		if($this->variants) {
			$this->variants    = VariantFactory::attachVariantLabels($this->variants);
			$this->variantList = VariantFactory::processVariantData($this->variantList);
			$this->default     = VariantFactory::getVariantDefault($this->variantList);
		}




	}


}
