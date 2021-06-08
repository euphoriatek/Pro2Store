<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Currency;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Exception;
use Joomla\CMS\Factory;


class CurrencyFactory
{


	/**
	 *
	 * Gets the currency based on the given ID.
	 *
	 * @param $id
	 *
	 * @return false|Currency
	 *
	 * @since 1.5
	 */

	public static function get($id)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_currency'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{

			return new Currency($result);
		}

		return false;

	}

	/**
	 *
	 * Gets the current users set currency from the cookie
	 * If none is set, the currency initialisation occurs.
	 *
	 * @return false|Currency
	 *
	 * @throws Exception
	 *
	 * @since 1.5
	 */

	public static function getCurrent()
	{

		$currency_id = Factory::getApplication()->input->cookie->get('yps-currency');

		if (!$currency_id)
		{
			return self::initCurrency();
		}

		return self::get($currency_id);

	}

	/**
	 *
	 * Gets the default currency
	 *
	 * @return false|Currency
	 *
	 * @since 1.5
	 */

	public static function getDefault()
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_currency'));
		$query->where($db->quoteName('default') . ' = 1');

		$result = $db->setQuery($query);

		if ($result)
		{
			return new Currency($result);
		}

		return false;

	}


	/**
	 *
	 * sets the currency cookie to the set value
	 *
	 * @param $id
	 *
	 *
	 * @throws Exception
	 * @since 1.5
	 */


	public static function setCurrency($id)
	{

		Factory::getApplication()->input->cookie->set(
			'yps-currency',
			$id,
			0,
			Factory::getApplication()->get('cookie_path', '/'),
			Factory::getApplication()->get('cookie_domain'),
			Factory::getApplication()->isSSLConnection()
		);

	}


	/**
	 * @param   int          $limit
	 * @param   int          $offset
	 * @param   int          $published
	 * @param   string|null  $searchTerm
	 * @param   string       $orderBy
	 * @param   string       $orderDir
	 *
	 *
	 * @since 1.5
	 */

	public static function getList(int $limit = 25, int $offset = 0, int $published = 1, string $searchTerm = null, string $orderBy = 'name', string $orderDir = 'ASC')
	{

	}


	public static function format($number, $currency)
	{

	}

	/**
	 * @param         $number
	 * @param   null  $selectedCurrency
	 *
	 * @return string
	 *
	 * @throws Exception
	 * @since 1.5
	 */

	public static function translate($number, ?Currency $currency = null): string
	{

		if (!$currency)
		{
			$currency = self::getCurrent();
		}



		$rate = $currency->rate;

		if ($rate) {
			$value = ($number * $rate);
		} else {
			$value = $number;
		}


		return self::formatNumberWithCurrency((int)$value, $currency->iso);


	}

	public static function translateByISO($number, $iso)
	{

	}

	public static function translateToInt($number, $iso)
	{

	}

	public static function getConversionRate($currency)
	{

	}


	/**
	 *
	 * Takes an integer (representing the MINOR of the value - i.e. for 10 pounds, the number will be 1000)
	 * and a Currency ISO and returns the Formatted string for the value.
	 *
	 * @param   int     $number
	 * @param   string  $currencyISO
	 *
	 * @return string
	 *
	 * @throws UnknownCurrencyException
	 * @since 1.5
	 */

	public static function formatNumberWithCurrency(int $number, string $currencyISO): string
	{


		// get the Joomla Locale
		$lang = Factory::getLanguage();
		$locales = $lang->getLocale();
		$locale = $locales[0];

		// use Brick to format the number
		$money = Money::ofMinor($number, $currencyISO);
		return $money->formatTo($locale);


	}


	/**
	 *
	 * Just gets the first published currency - used for initialising a currency
	 *
	 * @return false|Currency
	 *
	 * @since version
	 */

	private static function getAPublishedCurrency()
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('id');
		$query->from($db->quoteName('#__protostore_currency'));
		$query->where($db->quoteName('published') . ' = 1');
		$query->order('id ASC');
		$db->setQuery($query);

		$id = $db->loadResult();

		return self::get($id);

	}


	/**
	 *
	 * Initialises the currency in the cookie if there is none already set.
	 *
	 *
	 * @return false|Currency
	 *
	 * @throws Exception
	 * @since 1.5
	 */

	private static function initCurrency()
	{
		$currency = self::getAPublishedCurrency();

		Factory::getApplication()->input->cookie->set(
			'yps-currency',
			$currency->id,
			0,
			Factory::getApplication()->get('cookie_path', '/'),
			Factory::getApplication()->get('cookie_domain'),
			Factory::getApplication()->isSSLConnection()
		);

		return $currency;

	}

}
