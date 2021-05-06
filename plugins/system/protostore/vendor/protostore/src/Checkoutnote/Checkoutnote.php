<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */


namespace Protostore\Checkoutnote;

// no direct access
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Factory;
use Protostore\Utilities\Utilities;
use stdClass;

class Checkoutnote
{

	private $db;

	public $id;
	public $cookie_id;
	public $orderid;
	public $note;
	public $added;


	public function __construct($noteid, $note = null)
	{

		$this->db = Factory::getDbo();


		if ($noteid)
		{
			$this->initCheckoutnote($noteid);
		}
		else
		{
			$this->newNote($note);
		}


	}


	private function initCheckoutnote($id)
	{

		$query = $this->db->getQuery(true);

		$query->select('*');
		$query->from($this->db->quoteName('#__protostore_checkout_notes'));
		$query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($id));

		$this->db->setQuery($query);

		$result = $this->db->loadObject();

		$this->id        = $id;
		$this->cookie_id = $result->cookie_id;
		$this->orderid   = $result->orderid;
		$this->note      = $result->note;
		$this->added     = $result->added;


	}


	private function newNote($note)
	{

		$this->id        = 0;
		$this->cookie_id = Utilities::getCookieID();
		$this->orderid   = '';
		$this->note      = $note;
		$this->added     = Utilities::getDate();

		$this->create();

	}


	public function save()
	{

		$object            = new stdClass();
		$object->id        = $this->id;
		$object->cookie_id = $this->cookie_id;
		$object->orderid   = $this->orderid;
		$object->note      = $this->note;
		$object->added     = $this->added;


		$this->db->updateObject('#__protostore_checkout_notes', $object, 'id');
	}


	public function create()
	{

		$object            = new stdClass();
		$object->id        = $this->id;
		$object->cookie_id = $this->cookie_id;
		$object->orderid   = $this->orderid;
		$object->note      = $this->note;
		$object->added     = $this->added;


		$this->db->insertObject('#__protostore_checkout_notes', $this);
	}


	public static function getCurrentNote()
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_checkout_notes'));
		$query->where($db->quoteName('cookie_id') . ' = ' . $db->quote(Utilities::getCookieID()));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			return $result;
		}
		else
		{
			return false;
		}

	}


}
