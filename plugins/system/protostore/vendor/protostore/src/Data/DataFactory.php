<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Data;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

use stdClass;

class DataFactory
{

	public static function getList(string $type)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_' . $type));

		$db->setQuery($query);

		$results = $db->loadObjectList();

		if ($results)
		{
			return $results;
		}

	}

}
