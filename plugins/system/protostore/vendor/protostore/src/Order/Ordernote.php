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


class Ordernote
{

    public $id;
    public $order_id;
    public $note;
    public $created_by;
    public $created_by_name;
    public $created_by_email;
    public $created_by_avatar_abb;
    public $created;


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
	 * @since 1.6
	 */

	private function init()
	{

		$user = OrderFactory::getUser($this->created_by);

		$this->created_by_name = $user->name;
		$this->created_by_email = $user->email;
		$this->created_by_avatar_abb = OrderFactory::getAvatarAbb($this->created_by_name);

	}
}
