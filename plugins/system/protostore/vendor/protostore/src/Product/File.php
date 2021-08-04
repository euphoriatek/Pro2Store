<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Product;

defined('_JEXEC') or die('Restricted access');


class File
{

	public int $id;
	public int $product_id;
	public string $filename;
	public string $filename_obscured;
	public int $isjoomla;
	public string $version;
	public string $type;
	public string $stability_level;
	public string $stability_level_string;
	public string $php_min;
	public int $download_access;
	public int $published;
	public int $downloads;
	public ?string $created;
	public ?string $modified;


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
	 * @since 1.6
	 */

	private function init()
	{
		$this->stability_level_string = ProductFactory::getFileStabilityLevelString($this->stability_level);

	}


}
