<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
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
	 * @return Checkoutnote
	 *
	 * @since 1.5
	 */

	public static function get($id): ?Checkoutnote
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
			return null;
		}
		
	}

	/**
	 * @param $data
	 *
	 * @return bool
	 *
	 * @since 1.5
	 */

	public static function create($data): bool
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
	 * @return Checkoutnote
	 *
	 * @since 1.5
	 */

	public static function getCurrentNote(): ?Checkoutnote
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
			return null;
		}

	}

	/**
	 * @param $data
	 *
	 * @return Checkoutnote
	 *
	 * @since 1.5
	 */

	public static function save($data): ?Checkoutnote
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

		return null;

	}


}
