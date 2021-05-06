<?php

/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Emaillog;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Protostore\Utilities\Utilities;
use stdClass;

class Emaillog
{

	protected $db;

	public $id;
	public $emailaddress;
	public $emailtype;
	public $sentdate;
	public $customer_id;
	public $customer_name;
	public $order_id;
	public $order_number;
	public $created_by;
	public $created_by_name;
	public $modified_by;
	public $created;
	public $modified;

	public function __construct($email_log_id)
	{
		$this->db = Factory::getDbo();
		$this->initMailer($email_log_id);
	}


	private function initMailer($email_log_id)
	{

		$query = $this->db->getQuery(true);

		$query->select('*');
		$query->from($this->db->quoteName('#__protostore_email_log'));
		$query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($email_log_id));

		$this->db->setQuery($query);

		$emailLog = $this->db->loadObject();

		$this->id              = $email_log_id;
		$this->emailaddress    = $emailLog->emailaddress;
		$this->emailtype       = $emailLog->emailtype;
		$this->sentdate        = $emailLog->sentdate;
		$this->customer_id     = $emailLog->customer_id;
		$this->customer_name   = $this->getCustomerName($emailLog->customer_id);
		$this->order_id        = $emailLog->order_id;
		$this->order_number    = $this->getOrderNumber($emailLog->order_id);
		$this->created_by      = $emailLog->created_by;
		$this->created_by_name = Factory::getUser($emailLog->created_by)->name;
		$this->modified_by     = $emailLog->modified_by;
		$this->created         = $emailLog->created;
		$this->modified        = $emailLog->modified;

	}


	public static function log($emailaddress, $emailtype, $customer_id, $order_id)
	{

		$userid = (Factory::getUser()->id ? Factory::getUser()->id : 0);

		$object               = new stdClass();
		$object->id           = 0;
		$object->emailaddress = $emailaddress;
		$object->emailtype    = $emailtype;
		$object->sentdate     = Utilities::getDate();
		$object->customer_id  = $customer_id;
		$object->order_id     = $order_id;
		$object->created_by   = $userid;
		$object->modified_by  = $userid;
		$object->created      = Utilities::getDate();
		$object->modified     = Utilities::getDate();

		Factory::getDbo()->insertObject('#__protostore_email_log', $object);

	}


	public function getCustomerName($cusomterid)
	{

		if ($cusomterid)
		{
			$query = $this->db->getQuery(true);

			$query->select('name');
			$query->from($this->db->quoteName('#__protostore_customer'));
			$query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($cusomterid));

			$this->db->setQuery($query);

			return $this->db->loadResult();
		}
		else
		{
			return '';
		}


	}

	public function getOrderNumber($orderid)
	{

		if ($orderid)
		{
			$query = $this->db->getQuery(true);

			$query->select('order_number');
			$query->from($this->db->quoteName('#__protostore_order'));
			$query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($orderid));

			$this->db->setQuery($query);

			return $this->db->loadResult();
		}
		else
		{
			return '';
		}


	}


}
