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

use Joomla\CMS\Factory;

use Brick\Money\Money;

use \NumberFormatter;
use Exception;

class Currency
{

    public $db;
    public $cookie;
    public $currency;
    public $default = false;

    public function __construct()
    {
        $this->db = Factory::getDbo();
        $this->cookie = Factory::getApplication()->input->cookie;
        $this->getCurrency();
    }


    /**
     * @since 1.0
     */

    public function getCurrency()
    {

        // get the cookie that stores the selected currency - and set it to the default if none is set.
        $value = $this->cookie->get('yps-currency');

        if ($value !== null) {
            $this->currency = $this->_getCurrentCurrencyFromDB($value);
        } else {
            $this->currency = $this->_getDefaultCurrencyFromDB();
            $this->default = true;
        }

    }

    /**
     * @param $currency_id
     *
     * @since 1.0
     */

    public function setCurrency($currency_id)
    {
        $this->cookie->set('yps-currency', $currency_id, 0, Factory::getApplication()->get('cookie_path', '/'), Factory::getApplication()->get('cookie_domain'), Factory::getApplication()->isSSLConnection());

        return true;
    }

    /**
     * @param $currency_id
     *
     * @return mixed|null
     * @since 1.0
     */

    private function _getCurrentCurrencyFromDB($currency_id)
    {

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_currency'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($currency_id));

        $this->db->setQuery($query);

        return $this->db->loadObject();

    }

    /**
     * @return mixed|null
     * @since 1.0
     */

    public function _getDefaultCurrencyFromDB()
    {

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_currency'));
        $query->where($this->db->quoteName('default') . ' = 1');

        $this->db->setQuery($query);

        return $this->db->loadObject();

    }

    /**
     * @return mixed|null
     * @since 1.0
     */

    public function _getAPublishedCurrency()
    {

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_currency'));
        $query->where($this->db->quoteName('published') . ' = 1');
        $query->order('id ASC');
        $this->db->setQuery($query);

        return $this->db->loadObject();

    }

    /**
     * @param bool $publishedOnly
     *
     * @return array|mixed
     *
     * @since 1.0
     */

    public function getCurrencyList($publishedOnly = true)
    {

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_currency'));

        if ($publishedOnly) {
            $query->where($this->db->quoteName('published') . ' = 1');
        }

        $query->order('id ASC');
        $this->db->setQuery($query);

        return $this->db->loadObjectList();
    }


    /**
     * function formatNumberWithCurrency
     *
     * takes a MINOR integer and returns the formatted price with currency symbol.
     *
     * NOTE: $number here needs to be "MINOR" - i.e. 100.00 should be represented by 10000
     *
     * @param $number
     * @param $currency
     * @return string
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     */

    public static function formatNumberWithCurrency($number, $currency)
    {

        // cast given number to integer
        $number = (int)$number;

        // get the Joomla Locale
        $lang = Factory::getLanguage();
        $locales = $lang->getLocale();
        $locale = $locales[0];

        // use Brick to format the number
        $money = Money::ofMinor($number, $currency);
        return $money->formatTo($locale);


    }

    /**
     * @param $currency
     *
     * @return mixed
     *
     * @since 1.0
     */
    public function getConversionRate($currency)
    {

        $query = $this->db->getQuery(true);

        $query->select('rate');
        $query->from($this->db->quoteName('#__protostore_currency'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($currency->id));

        $this->db->setQuery($query);

        return $this->db->loadResult();


    }

    /**
     *
     * Function translate
     *
     * Takes a Integer (formatted to Minor) and a currency, an multiplies the value by the rate of the given currency.
     * Since default currency is always 1.
     *
     *  NOTE: $number here needs to be "MINOR" - i.e. 100.00 should be represented by 10000
     *
     * @param $number
     * @param $selectedCurrency
     * @return string
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     *
     */


    public static function translate($number, $selectedCurrency)
    {

        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('rate');
        $query->from($db->quoteName('#__protostore_currency'));
        $query->where($db->quoteName('id') . ' = ' . $db->quote($selectedCurrency->currency->id));

        $db->setQuery($query);

        $rate = $db->loadResult();

        if ($rate) {
            $value = ($number * $rate);
        } else {
            $value = $number;
        }


        return self::formatNumberWithCurrency((int)$value, $selectedCurrency->currency->iso);


    }

    /**
     *
     * Translates the value by the currency ISO
     *
     * @param $number
     * @param $iso
     * @return string
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     */

    public static function translateByISO($number, $iso)
    {

        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('rate');
        $query->from($db->quoteName('#__protostore_currency'));
        $query->where($db->quoteName('iso') . ' = ' . $db->quote($iso));

        $db->setQuery($query);

        $rate = $db->loadResult();

        $value = $number * $rate;

        return self::formatNumberWithCurrency($value, $iso);

    }

    /**
     *
     * function translateToInt
     *
     * translates the value, but only returns the integer value
     * rather than the fully formatted brick/money string like "translate" does
     *
     * @param $number
     * @param $iso
     * @return int
     *
     */

    public static function translateToInt($number, $iso)
    {

        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('rate');
        $query->from($db->quoteName('#__protostore_currency'));
        $query->where($db->quoteName('iso') . ' = ' . $db->quote($iso));

        $db->setQuery($query);

        $rate = $db->loadResult();

        $value = $number * $rate;

        return (int) $value;

    }

}
