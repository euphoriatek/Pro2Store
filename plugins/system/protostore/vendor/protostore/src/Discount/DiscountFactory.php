<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Discount;

// no direct access
defined('_JEXEC') or die('Restricted access');


use Brick\Money\Exception\UnknownCurrencyException;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Input\Input;
use Protostore\Currency\CurrencyFactory;
use Protostore\Utilities\Utilities;
use stdClass;


class DiscountFactory
{


	/**
	 *
	 * Gets the discount based on the given ID.
	 *
	 * @param $id
	 *
	 * @return Discount
	 *
	 * @since 1.6
	 */

	public static function get($id): ?Discount
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_discount'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{

			return new Discount($result);
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

	public static function getList(int $limit = 0, int $offset = 0, string $searchTerm = null, string $orderBy = 'id', string $orderDir = 'DESC'): ?array
	{

		// init items
		$items = array();

		// get the Database
		$db = Factory::getDbo();

		// set initial query
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__protostore_discount'));


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
				$items[] = new Discount($result);

			}

			return $items;
		}

		return null;

	}

	/**
	 * @param   int  $discount_type
	 *
	 * @return string
	 *
	 * @since 1.6
	 */

	public static function getDiscountTypeAsString(int $discount_type): string
	{

		$language = Factory::getLanguage();
		$language->load('com_protostore');

		switch ($discount_type)
		{
			case 1 :
				return Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_DISCOUNT_TYPE_AMOUNT');
			case 2 :
				return Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_DISCOUNT_TYPE_PERCENT');
		}

		return '';

	}

	/**
	 * @param   int    $amount
	 * @param   float  $percentage
	 * @param   int    $type
	 *
	 *
	 * @return string|void
	 * @throws UnknownCurrencyException
	 * @since 1.6
	 */


	public static function getDiscountAmountFormatted(?int $amount, ?float $percentage, int $type)
	{

		switch ($type)
		{
			case 1:
				return CurrencyFactory::formatNumberWithCurrency($amount * 100);
			case 2 :
				return $percentage . "%";
		}

	}

	/**
	 * @param   Input  $data
	 *
	 *
	 * @return Discount
	 * @since 1.6
	 */


	public static function saveFromInputData(Input $data)
	{


		if ($id = $data->getInt('itemid', null))
		{

			$currentDiscount = self::get($id);

			$amount     = $data->getInt('amount', $currentDiscount->amount);
			$percentage = $data->getString('percentage', $currentDiscount->percentage);

			$discountType = $data->getInt('discount_type', $currentDiscount->discount_type);


			switch ($discountType)
			{
				case 1:
					$percentage = 0;
					break;
				case 2:
					$amount = 0;
					break;
				case 3:
					$amount     = 0;
					$percentage = 0;
					break;
			}

			$currentDiscount->name          = $data->getString('name', $currentDiscount->name);
			$currentDiscount->amount        = $amount;
			$currentDiscount->percentage    = $percentage;
			$currentDiscount->discount_type = $data->getInt('discount_type', $currentDiscount->discount_type);
			$currentDiscount->coupon_code   = $data->getString('coupon_code', $currentDiscount->coupon_code);
			$currentDiscount->expiry_date   = $data->getString('expiry_date', $currentDiscount->expiry_date);
			$currentDiscount->published     = $data->getInt('published', $currentDiscount->published);
			$currentDiscount->modified      = Utilities::getDate();
			$currentDiscount->modified_by   = Factory::getUser()->id;

			if (self::commitToDatabase($currentDiscount))
			{
				return $currentDiscount;
			}

		}
		else
		{

			if ($discount = self::createFromInputData($data))
			{
				return $discount;
			}

		}

		return null;
	}

	/**
	 * @param   Input  $data
	 *
	 * @return Discount|bool
	 *
	 * @since 1.6
	 */


	private static function createFromInputData(Input $data): Discount
	{

		$db = Factory::getDbo();

		$discount                = new stdClass();
		$discount->name          = $data->getString('name');
		$discount->amount        = $data->getInt('amount');
		$discount->discount_type = $data->get('discount_type');
		$discount->percentage    = $data->getString('percentage');
		$discount->coupon_code   = $data->getString('coupon_code');
		$discount->expiry_date   = $data->getString('expiry_date');
		$discount->published     = $data->get('published');
		$discount->created       = Utilities::getDate();
		$discount->created_by    = Factory::getUser()->id;
		$discount->modified      = Utilities::getDate();
		$discount->modified_by   = Factory::getUser()->id;


		$result = $db->insertObject('#__protostore_discount', $discount);

		if ($result)
		{
			return self::get($db->insertid());
		}

		return false;


	}

	/**
	 * @param   Discount  $discount
	 *
	 *
	 * @return bool
	 * @since 1.6
	 */


	private static function commitToDatabase(Discount $discount): bool
	{

		$db = Factory::getDbo();

		/** @var Discount $insert */
		$insert = new stdClass();

		$insert->id            = $discount->id;
		$insert->name          = $discount->name;
		$insert->amount        = $discount->amount;
		$insert->percentage    = $discount->percentage;
		$insert->discount_type = $discount->discount_type;
		$insert->coupon_code   = $discount->coupon_code;
		$insert->expiry_date   = $discount->expiry_date;
		$insert->published     = $discount->published;
		$insert->modified      = $discount->modified;
		$insert->modified_by   = $discount->modified_by;

		$result = $db->updateObject('#__protostore_discount', $insert, 'id');

		if ($result)
		{
			return true;
		}

		return false;

	}

	/**
	 * @param   Input  $data
	 *
	 *
	 * @return bool
	 * @since 1.6
	 */

	public static function trashFromInputData(Input $data): bool
	{

		$db = Factory::getDbo();

		$items = $data->json->get('items', '', 'ARRAY');


		foreach ($items as $item)
		{
			$query      = $db->getQuery(true);
			$conditions = array(
				$db->quoteName('id') . ' = ' . $db->quote($item['id'])
			);
			$query->delete($db->quoteName('#__protostore_discount'));
			$query->where($conditions);
			$db->setQuery($query);
			$db->execute();

		}

		return true;

	}

	/**
	 * @param   Input  $data
	 *
	 *
	 * @return bool
	 * @since 1.6
	 */

	public static function togglePublishedFromInputData(Input $data): bool
	{


		$response = true;

		$db = Factory::getDbo();

		$items = $data->json->get('items', '', 'ARRAY');


		foreach ($items as $item)
		{

			$query = 'UPDATE ' . $db->quoteName('#__protostore_discount') . ' SET ' . $db->quoteName('published') . ' = IF(' . $db->quoteName('published') . '=1, 0, 1) WHERE ' . $db->quoteName('id') . ' = ' . $db->quote($item['']) . ';';
			$db->setQuery($query);
			$result = $db->execute();

			if (!$result)
			{
				$response = false;
			}

		}

		return $response;
	}


}
