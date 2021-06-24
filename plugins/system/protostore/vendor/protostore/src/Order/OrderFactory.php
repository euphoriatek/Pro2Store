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

use Protostore\Currency\CurrencyFactory;

use Brick\Money\Exception\UnknownCurrencyException;

class OrderFactory
{


	/**
	 * @param   int  $id
	 *
	 * @return false|Order
	 *
	 * @since 1.6
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
	 * @param   int          $limit
	 *
	 * @param   int          $offset
	 * @param   string|null  $searchTerm
	 * @param   int|null     $customerId
	 * @param   string|null  $currency
	 * @param   string|null  $dateFrom
	 * @param   string|null  $dateTo
	 *
	 * @return array|false
	 * @since 1.6
	 */

	public static function getList(int $limit = 0, int $offset = 0, string $searchTerm = null, int $customerId = null, string $status = null, string $currency = null, string $dateFrom = null, string $dateTo = null)
	{

		$orders = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_order'));

		if ($searchTerm)
		{
			$query->where($db->quoteName('order_number') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');
			$query->where($db->quoteName('payment_method') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');
			$query->where($db->quoteName('customer_notes') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');
			$query->where($db->quoteName('vendor_token') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');
		}

		if ($currency)
		{
			$query->where($db->quoteName('currency') . ' = ' . $db->quote($currency));
		}

		if ($status)
		{
			$query->where($db->quoteName('order_status') . ' = ' . $db->quote($status));
		}

		if ($customerId)
		{
			$query->where($db->quoteName('customer') . ' = ' . $db->quote($customerId));
		}

		if ($dateFrom)
		{
			$query->where($db->quoteName('order_date') . ' >= ' . $db->quote($dateFrom));
		}

		if ($dateTo)
		{
			$query->where($db->quoteName('order_date') . ' <= ' . $db->quote($dateTo));
		}

		$db->setQuery($query, $offset, $limit);

		$results = $db->loadObjectList();

		if ($results)
		{
			foreach ($results as $result)
			{
				$orders[] = new Order($result);

			}

			return $orders;
		}


		return false;
	}

	/**
	 * @param $order_id
	 *
	 * @return array|false
	 *
	 * @since version
	 */


	public static function getOrderedProducts($order_id)
	{

		$products = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_order_products'));
		$query->where($db->quoteName('order_id') . ' = ' . $db->quote($order_id));

		$db->setQuery($query);

		$results = $db->loadObjectList();

		if ($results)
		{
			foreach ($results as $result)
			{
				$products[] = new OrderedProduct($result);

			}

			return $products;
		}


		return false;


	}

	/**
	 * @param   int     $int
	 * @param   string  $currency
	 *
	 * @return string
	 *
	 * @throws UnknownCurrencyException
	 * @since 1.6
	 */

	public static function intToFormat(int $int, string $currency = null): string
	{

		return CurrencyFactory::formatNumberWithCurrency($int, $currency);

	}

	/**
	 *
	 * Gets the currency for the order... we CAN'T instantiate the Order class here with self::get() as
	 * we need this function for the Ordered Product class
	 * calling the order would cause an infinite loop
	 *
	 * @param $id
	 *
	 * @return mixed|null
	 *
	 * @since 1.6
	 */


	public static function getOrderCurrency($id)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('currency');
		$query->from($db->quoteName('#__protostore_order'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		return $db->loadResult();


	}

}
