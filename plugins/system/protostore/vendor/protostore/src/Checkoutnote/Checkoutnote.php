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


class Checkoutnote
{


	public int $id;
	public string $cookie_id;
	public int $orderid;
	public string $note;
	public string $added;

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



	}
//
//
//	private function newNote($note)
//	{
//
//		$this->id        = 0;
//		$this->cookie_id = Utilities::getCookieID();
//		$this->orderid   = '';
//		$this->note      = $note;
//		$this->added     = Utilities::getDate();
//
//		$this->create();
//
//	}
//
//
//	public function save()
//	{
//
//		$object            = new stdClass();
//		$object->id        = $this->id;
//		$object->cookie_id = $this->cookie_id;
//		$object->orderid   = $this->orderid;
//		$object->note      = $this->note;
//		$object->added     = $this->added;
//
//
//		$this->db->updateObject('#__protostore_checkout_notes', $object, 'id');
//	}
//
//
//	public function create()
//	{
//
//		$object            = new stdClass();
//		$object->id        = $this->id;
//		$object->cookie_id = $this->cookie_id;
//		$object->orderid   = $this->orderid;
//		$object->note      = $this->note;
//		$object->added     = $this->added;
//
//
//		$this->db->insertObject('#__protostore_checkout_notes', $this);
//	}
//
//
//	public static function getCurrentNote()
//	{
//		$db = Factory::getDbo();
//
//		$query = $db->getQuery(true);
//
//		$query->select('*');
//		$query->from($db->quoteName('#__protostore_checkout_notes'));
//		$query->where($db->quoteName('cookie_id') . ' = ' . $db->quote(Utilities::getCookieID()));
//
//		$db->setQuery($query);
//
//		$result = $db->loadObject();
//
//		if ($result)
//		{
//			return $result;
//		}
//		else
//		{
//			return false;
//		}
//
//	}


}
