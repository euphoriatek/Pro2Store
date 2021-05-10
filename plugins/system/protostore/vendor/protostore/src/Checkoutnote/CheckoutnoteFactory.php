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

class CheckoutnoteFactory
{

	/**
	 * @param $id
	 *
	 * @return false|Checkoutnote
	 *
	 * @since 1.7
	 */

	public static function get($id)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_checkout_notes'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			return new Checkoutnote($result);
		}
		else
		{
			return false;
		}
		
	}

	/**
	 * @param $data
	 *
	 * @return bool
	 *
	 * @since 1.7
	 */

	public static function create($data)
	{

		$object            = new stdClass();
		$object->id        = 0;
		$object->cookie_id = Utilities::getCookieID();
		$object->orderid   = '';
		$object->note      = $data->get('note', '');
		$object->added     = Utilities::getDate();


		$create = Factory::getDbo()->insertObject('#__protostore_checkout_notes', $object);

		if ($create)
		{
			return true;
		}

		return false;

	}

	/**
	 *
	 * @return false|Checkoutnote
	 *
	 * @since 1.7
	 */

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
			return new Checkoutnote($result);
		}
		else
		{
			return false;
		}

	}

	/**
	 * @param $data
	 *
	 * @return false|Checkoutnote
	 *
	 * @since 1.7
	 */

	public static function save($data)
	{

		$note = self::getCurrentNote();

		if ($note)
		{
			$note->note = $data->get('note', '');

			$update = Factory::getDbo()->updateObject('#__protostore_checkout_notes', $note, 'id');

			if ($update)
			{
				return self::getCurrentNote();
			}
		}
		else
		{
			$create = self::create($data);

			if ($create)
			{
				return self::getCurrentNote();
			}
		}

		return false;

	}


}
