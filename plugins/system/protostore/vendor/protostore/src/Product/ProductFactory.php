<?php

/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

namespace Protostore\Product;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;


class ProductFactory
{


	/**
	 * @param $id
	 *
	 *
	 * @since 1.0
	 */

	public static function get(int $joomla_item_id)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product'));
		$query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($joomla_item_id));

		$db->setQuery($query);

		$result = $db->loadObject();
		if ($result && is_object($result))
		{

			switch ($result->product_type)
			{
				case 0:
					return new PurchaseProduct($result);
				case 1:
					return new SubscriptionProduct($result);
			}

			return new Product($result);
		}

		return false;
	}

	/**
	 * @param   int          $limit
	 *
	 * @param   int          $offset
	 * @param   int          $category
	 * @param   string|null  $searchTerm
	 *
	 * @return array|false
	 * @since version
	 */

	public static function getList(int $limit, int $offset, int $category = 0, string $searchTerm = null)
	{

		$products = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('id');
		$query->from($db->quoteName('#__content'));

		if ($searchTerm)
		{
			$query->where($db->quoteName('title') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');
		}

		if ($category)
		{
			$query->where($db->quoteName('catid') . ' = ' . $db->quote($category));
		}


		$db->setQuery($query, $offset, $limit);

		$results = $db->loadColumn();

		if ($results)
		{
			foreach ($results as $result)
			{
				$query = $db->getQuery(true);

				$query->select('*');
				$query->from($db->quoteName('#__protostore_product'));
				$query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($result));
				$db->setQuery($query);

				$result = $db->loadObject();

				if($result) {
					$products[] = new Product($result);
				}


			}


			return $products;
		}


		return false;
	}

}
