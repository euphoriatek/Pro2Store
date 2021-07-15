<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Customer;

// no direct access
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Factory;
use Joomla\CMS\User\User;

use Joomla\Input\Input;
use Protostore\Address\AddressFactory;
use Protostore\Currency\CurrencyFactory;
use Protostore\Order\OrderFactory;


class CustomerFactory
{


	/**
	 *
	 * Gets the currency based on the given ID.
	 *
	 * @param $id
	 *
	 * @return false|Customer
	 *
	 * @since 1.6
	 */

	public static function get($id)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_customer'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{

			return new Customer($result);
		}

		return false;

	}


	/**
	 * @param   int          $limit
	 * @param   int          $offset
	 * @param   string|null  $searchTerm
	 * @param   string       $orderBy
	 * @param   string       $orderDir
	 *
	 *
	 * @return array|false
	 * @since 1.5
	 */

	public static function getList(int $limit = 0, int $offset = 0, string $searchTerm = null, string $orderBy = 'name', string $orderDir = 'DESC')
	{

		// init items
		$items = array();

		// get the Database
		$db = Factory::getDbo();

		// set initial query
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__protostore_customer'));


		// if there is a search term, iterate over the columns looking for a match
		if ($searchTerm)
		{
			$query->where($db->quoteName('name') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');
			$query->where($db->quoteName('email') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));
		}

		$query->order($orderBy . ' ' . $orderDir);

		$db->setQuery($query, $offset, $limit);

		$results = $db->loadObjectList();

		// only proceed if there's any rows
		if ($results)
		{
			// iterate over the array of objects, initiating the Class.
			foreach ($results as $result)
			{
				$items[] = new Customer($result);

			}

			return $items;
		}

		return false;

	}

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 1.6
	 */


	public static function saveFromInputData(Input $data)
	{

		$customer = json_decode($data->getString('customer'));


		$result = Factory::getDbo()->updateObject('#__protostore_customer', $customer, 'id');

		if ($result)
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	/**
	 * @param $id
	 *
	 * @return User
	 *
	 * @since version
	 */

	public static function getUser($joomla_user_id): User
	{

		return User::getInstance($joomla_user_id);


	}

	/**
	 * @param $id  - customer id NOT Joomla user ID
	 *
	 *
	 * @since version
	 */


	public static function getCustomersOrders($cusomter_id)
	{


		return OrderFactory::getList(0, 0, null, $cusomter_id);


	}


	/**
	 * @param          $orders
	 * @param   false  $integer
	 *
	 * @return int|mixed|string
	 *
	 * @throws \Brick\Money\Exception\UnknownCurrencyException
	 * @since 1.6
	 *
	 */

	public static function getOrderTotal($orders, bool $integer = false)
	{


		// set $total to 0 (sets an int)
		$total = 0;

		// make sure we have orders
		if ($orders)
		{
			// iterate through the orders adding to the integer total
			foreach ($orders as $order)
			{
				$total += $order->order_total;
			}

		}

		// if $integer is set to "true" then simply return the integer
		if ($integer)
		{
			return $total;
		}


		// if $integer is "false" but default, then go get the formatted currency string
		return CurrencyFactory::formatNumberWithCurrency($total);


	}

	/**
	 * @param $cusomter_id
	 *
	 * @return array|false
	 *
	 * @since 1.6
	 */

	public static function getCustomerAddresses($cusomter_id)
	{

		return AddressFactory::getList(0, 0, null, 'name', 'desc', $cusomter_id);

	}


}
