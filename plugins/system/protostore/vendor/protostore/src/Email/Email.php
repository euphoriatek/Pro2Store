<?php

/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Email;

defined('_JEXEC') or die('Restricted access');


class Email
{

	public $id;
	public $to;
	public $body;
	public $emailtype;
	public $emailtype_string;
	public $subject;
	public $published;
	public $created_by;
	public $modified_by;
	public $created;
	public $modified;


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

		$this->emailtype_string = EmailFactory::emailTypeToString($this->emailtype);


	}


}