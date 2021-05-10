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


class CheckoutnoteFactory
{

	public static function get($id)
	{

	}

	public static function create($data)
	{

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

	public static function save() {

	}


}
