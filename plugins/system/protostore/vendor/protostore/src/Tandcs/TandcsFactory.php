<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// no direct access

namespace Protostore\Tandcs;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Protostore\Utilities\Utilities;

use stdClass;

class TandcsFactory
{


	/**
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */

	public static function isChecked(): bool
	{

		$db        = Factory::getDbo();
		$cookie_id = Utilities::getCookieID();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_tandcs_checked'));
		$query->where($db->quoteName('cookie_id') . ' = ' . $db->quote($cookie_id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if($result) {
			return true;
		} else {
			return false;
		}

	}


	public static function toggle($checkState)
	{

		$db        = Factory::getDbo();
		$cookie_id = Utilities::getCookieID();

		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('cookie_id') . ' = ' . $db->quote($cookie_id)
		);

		$query->delete($db->quoteName('#__protostore_tandcs_checked'));
		$query->where($conditions);

		$db->setQuery($query);

		$result = $db->execute();

		if ($result)
		{

			if ($checkState == 'checked')
			{

				$object            = new stdClass();
				$object->id        = 0;
				$object->cookie_id = $cookie_id;

				$db->insertObject('#__protostore_tandcs_checked', $object);

				return true;
			}

			return false;

		}


	}


}
