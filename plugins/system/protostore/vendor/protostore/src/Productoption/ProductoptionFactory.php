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

use Joomla\Input\Input;
use Protostore\Currency\CurrencyFactory;

use stdClass;

class ProductoptionFactory
{


	/**
	 * @param $id
	 *
	 * @return Productoption
	 *
	 * @since 1.6
	 */

	public static function get($id): ?Productoption
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

		return null;
	}

	/**
	 * @param   array  $ids
	 *
	 * @return array|null
	 *
	 * @since 1.6
	 */

	public static function getListFromGivenIds($ids = array()): ?array
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
			$query->from($db->quoteName('#__protostore_product_option_values'));
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
	 * @param $product_id
	 *
	 * @return array
	 *
	 * @since 1.6
	 */

	public static function getProductOptions($product_id): ?array
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

		return null;


	}


	/**
	 * @param   int  $optiontype
	 *
	 * @return mixed|null
	 *
	 * @since 1.6
	 */


	public static function getOptionTypeName(int $optiontype)
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


	public static function getOptionTypes()
	{

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_option'));

		$db->setQuery($query);

		$result = $db->loadObjectList();

	}

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 1.6
	 */


	public static function createOptionTypeFromInputData(Input $data): bool
	{

		$db = Factory::getDbo();


		$object              = new stdClass();
		$object->id          = 0;
		$object->name        = $data->json->get('optionTypeName');
		$object->option_type = $data->json->get('optionType');

		$result = $db->insertObject('#__protostore_product_option', $object);

		return $result;

	}
}
