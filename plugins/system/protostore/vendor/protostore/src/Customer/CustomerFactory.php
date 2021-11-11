<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Customer;

// no direct access
defined('_JEXEC') or die('Restricted access');


use Brick\Money\Exception\UnknownCurrencyException;
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
	 * Gets the customer based on the given ID.
	 *
	 * @param $id
	 *
	 * @return Customer
	 *
	 * @since 2.0
	 */

	public static function get($id = null): ?Customer
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_customer'));

		// if the id is set... use it to grab the Customer
		if ($id)
		{
			$query->where($db->quoteName('id') . ' = ' . $db->quote($id));
		}
		else
		{
			$id = Factory::getUser()->id;
			// if the id is not set, try to retrieve using the current logged in user

			if ($id !== 0)
			{
				$query->where($db->quoteName('j_user_id') . ' = ' . $db->quote($id));
			}

			else
			{
				return null;
			}
		}

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{

			return new Customer($result);
		}

		return null;

	}


	/**
	 * @param   int          $limit
	 * @param   int          $offset
	 * @param   string|null  $searchTerm
	 * @param   string       $orderBy
	 * @param   string       $orderDir
	 *
	 *
	 * @return array
	 * @since 1.5
	 */

	public static function getList(int $limit = 0, int $offset = 0, string $searchTerm = null, string $orderBy = 'name', string $orderDir = 'DESC'): ?array
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
			$query->where($db->quoteName('name') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));
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

		return null;

	}

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */


	public static function saveFromInputData(Input $data): bool
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
	 * @since 2.0
	 */


	public static function getCustomersOrders($cusomter_id): ?array
	{


		return OrderFactory::getList(0, 0, null, $cusomter_id);


	}


	/**
	 * @param          $orders
	 * @param   false  $integer
	 *
	 * @return int|mixed|string
	 *
	 * @throws UnknownCurrencyException
	 * @since 2.0
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
	 * @param $customer_id
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	public static function getCustomerAddresses($customer_id): ?array
	{

		return AddressFactory::getList(0, 0, null, 'name', 'desc', $customer_id);

	}


	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */

	public static function delete(Input $data)
	{
		$user_id     = $data->json->getInt('user_id');
		$customer_id = $data->json->getInt('customer_id');

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('user_id') . ' = ' . $db->quote($user_id)
		);

		$query->delete($db->quoteName('#__user_profiles'));
		$query->where($conditions);

		$db->setQuery($query);

		$db->execute();



		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('id') . ' = ' . $db->quote($user_id)
		);

		$query->delete($db->quoteName('#__users'));
		$query->where($conditions);

		$db->setQuery($query);

		$db->execute();


		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('customer_id') . ' = ' . $db->quote($customer_id)
		);

		$query->delete($db->quoteName('#__protostore_customer_address'));
		$query->where($conditions);

		$db->setQuery($query);

		$db->execute();

		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('customer_id') . ' = ' . $db->quote($customer_id)
		);

		$query->delete($db->quoteName('#__protostore_order'));
		$query->where($conditions);

		$db->setQuery($query);

		$db->execute();

		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('id') . ' = ' . $db->quote($customer_id)
		);

		$query->delete($db->quoteName('#__protostore_customer'));
		$query->where($conditions);

		$db->setQuery($query);

		$db->execute();


		return true;
	}

}
