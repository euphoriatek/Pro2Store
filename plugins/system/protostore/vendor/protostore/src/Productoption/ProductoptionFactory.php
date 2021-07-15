<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

namespace Protostore\Productoption;

defined('_JEXEC') or die('Restricted access');


use Brick\Math\BigDecimal;
use Joomla\CMS\Factory;

use Brick\Money\Exception\UnknownCurrencyException;

use Protostore\Currency\CurrencyFactory;


class ProductoptionFactory
{


	/**
	 * @param $id
	 *
	 * @return false|Productoption
	 *
	 * @since 1.6
	 */

	public static function get($id)
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_option_values'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			return new Productoption($result);
		}

		return false;
	}

	/**
	 * @param $product_id
	 *
	 * @return array|false
	 *
	 * @since 1.6
	 */

	public static function getProductOptions($product_id)
	{

		$options = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_option_values'));
		$query->where($db->quoteName('product_id') . ' = ' . $db->quote($product_id));

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

		return false;


	}


	/**
	 * @param $optiontypeid
	 *
	 * @return mixed|null
	 *
	 * @since 1.6
	 */


	public static function getOptionTypeName($optiontype)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('name');
		$query->from($db->quoteName('#__protostore_product_option'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($optiontype));

		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * @param   int     $value
	 *
	 * @param   string  $type
	 *
	 * @return string
	 * @throws UnknownCurrencyException
	 * @since 1.6
	 */

	public static function translateModifierValue(int $value, string $type): string
	{

		switch ($type)
		{
			case 'perc':
				return $value . '%';
			case 'amount':
				return CurrencyFactory::formatNumberWithCurrency($value);
		}


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
