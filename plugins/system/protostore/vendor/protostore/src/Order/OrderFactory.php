<?php

/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

namespace Protostore\Order;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;


class OrderFactory
{


	/**
	 * @param   int  $id
	 *
	 * @return false|Order
	 *

	 * @since version
	 */

	public static function get(int $id)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_order'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();
		if ($result && is_object($result))
		{
			return new Order($result);
		}

		return false;
	}

	/**
	 * @param   int  $limit
	 *
	 *
	 * @since version
	 */

	public static function getList()
	{

		$products = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_order'));

		$db->setQuery($query);

		$results = $db->loadObjectList();

		if ($results)
		{
			foreach ($results as $result)
			{
				$products[] = new Order($result);

			}

			return $products;
		}


		return false;
	}

}
