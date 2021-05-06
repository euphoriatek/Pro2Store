<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Discount;
// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

use Brick\Money\Money;
use Protostore\Currency\Currency;

class Discount
{

    private $db;

    public $id;
    public $amount;

    public $couponcode;
    public $expiry_date;
    public $expiry_date_date;
    public $expiry_date_time;
    public $name;
    public $discount_type;
    public $published;

    public function __construct($id)
    {
        $this->db = Factory::getDbo();

        $this->initDiscount($id);


    }

    private function initDiscount($id)
    {

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_discount'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($id));

        $this->db->setQuery($query);

        $result = $this->db->loadObject();

        $this->id = $id;

        if ($result->discount_type == 'amount') {
            $this->amount = $this->getDiscountFloat($result->amount);
        } else {
            $this->amount = $result->amount;
        }


        $this->couponcode = $result->couponcode;
        $this->expiry_date = $result->expiry_date;
        $this->name = $result->name;
        $this->discount_type = $result->discount_type;
        $this->published = $result->published;

        $this->setExpiryDate();
        $this->setExpiryTime();


    }


    /**
     *
     * Function getDiscountFloat
     *
     * Returns the simple float value of the integer discount amount from the database.
     *
     * @return \Brick\Math\BigDecimal|string
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     */

    public function getDiscountFloat($amount)
    {
        $currency = new Currency();

        if ($amount) {

            $amount = Money::ofMinor($amount, $currency->_getDefaultCurrencyFromDB()->iso);

            return $amount->getAmount();

        }


    }

    public function setExpiryDate()
    {
        $this->expiry_date_date = HtmlHelper::date($this->expiry_date, Text::_('DATE_FORMAT_LC4'));
    }

    public function setExpiryTime()
    {
        $this->expiry_date_time = HtmlHelper::date($this->expiry_date, 'H:i');
    }
}
