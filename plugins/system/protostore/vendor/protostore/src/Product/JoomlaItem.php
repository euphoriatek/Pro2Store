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


class JoomlaItem
{

	public int $id;
	public string $title;
	public string $alias;
	public string $introtext;
	public string $fulltext;
	public int $state;
	public int $catid;
	public string $created;
	public int $created_by;
	public string $created_by_alias;
	public string $modified;
	public ?int $modified_by;
	public string $publish_up;
	public string $publish_down;
	public string $images;
	public int $ordering;
	public string $metakey;
	public string $metadesc;
	public int $access;
	public int $hits;
	public int $featured;



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


	}


}
