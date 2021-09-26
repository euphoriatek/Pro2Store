<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// no direct access

namespace Protostore\Productoption;

defined('_JEXEC') or die('Restricted access');



use Joomla\CMS\Factory;
use Joomla\Input\Input;

use Protostore\Currency\CurrencyFactory;

use Brick\Math\BigDecimal;
use Brick\Money\Exception\UnknownCurrencyException;

use stdClass;

class ProductoptionFactory
{


	/**
	 * @param $id
	 *
	 * @return Productoption
	 *
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */

	public static function get($id): ?Productoption
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_option'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			return new Productoption($result);
		}

		return null;
	}

	/**
	 * @param   array  $ids
	 *
	 * @return array|null
	 *
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */

	public static function getListFromGivenIds(array $ids = array()): ?array
	{

		if(empty($ids)) {
			return null;
		}

		if (!is_array($ids))
		{
			$ids = array($ids);
		}

		$db = Factory::getDbo();

		$selectedOptions = array();


		foreach ($ids as $id)
		{

			$query = $db->getQuery(true);

			$query->select('*');
			$query->from($db->quoteName('#__protostore_product_option'));
			$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

			$db->setQuery($query);

			$result = $db->loadObject();

			if ($result)
			{
				$selectedOptions[] = new Productoption($result);
			}

		}

		return $selectedOptions;

	}

	/**
	 * @param   int  $j_item_id
	 *
	 * @return array
	 *
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */

	public static function getProductOptions(int $j_item_id): ?array
	{

		$options = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_option'));
		$query->where($db->quoteName('product_id') . ' = ' . $db->quote($j_item_id));

		$db->setQuery($query);

		$results = $db->loadObjectList();

		if ($results)
		{
			foreach ($results as $result)
			{
				$options[] = new Productoption($result);

			}

			return $options;
		}

		return null;


	}


	/**
	 * @param   int  $value
	 *
	 * @return BigDecimal
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */

	public static function processModifierValue(int $value): BigDecimal
	{

		return CurrencyFactory::toFloat($value);

	}
	/**
	 * @param   int     $value
	 *
	 * @param   string  $type
	 *
	 * @return string
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */

	public static function translateModifierValue(int $value, string $type): string
	{

		switch ($type)
		{
			case 'perc':
				return ($value / 100). '%';
			case 'amount':
				return CurrencyFactory::formatNumberWithCurrency($value);
		}

		return '';

	}

	/**
	 * @param $price
	 *
	 * @return BigDecimal
	 *
	 * @throws UnknownCurrencyException
	 * @since version
	 */

	public static function getFloat($price): BigDecimal
	{

		return CurrencyFactory::toFloat($price);

	}




}
