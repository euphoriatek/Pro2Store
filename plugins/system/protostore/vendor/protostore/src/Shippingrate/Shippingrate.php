<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Shippingrate;
// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

use Protostore\Currency\Currency;

use Brick\Money\Money;
use Brick\Money\Context\CashContext;
use Brick\Math\RoundingMode;

class Shippingrate
{

    private $db;

    public $id;
    public $country_id;
    public $country_name;
    public $weight_from;
    public $weight_to;
    public $cost;
    public $costAsFloat;
    public $handling_cost;
    public $handling_costAsFloat;
    public $published;

    public function __construct($id)
    {
        $this->db = Factory::getDbo();

        $this->initShippingrate($id);


    }

    private function initShippingrate($id)
    {

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_shipping_rate'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($id));

        $this->db->setQuery($query);

        $result = $this->db->loadObject();

        $this->id = $id;
        $this->country_id = $result->country_id;
        $this->country_name = $this->getCountryName($result->country_id);
        $this->weight_from = $result->weight_from;
        $this->weight_to = $result->weight_to;
        $this->cost = $result->cost;
        $this->costAsFloat = $this->getCostAsFloat();
        $this->handling_cost = $result->handling_cost;
        $this->handling_costAsFloat = $this->getHandlingCostAsFloat();
        $this->published = $result->published;

    }

    private function getCountryName($id)
    {
        $query = $this->db->getQuery(true);

        $query->select('country_name');
        $query->from($this->db->quoteName('#__protostore_country'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($id));

        $this->db->setQuery($query);

        return $this->db->loadResult();
    }

    private function getCostAsFloat()
    {
        $currency = new Currency();

        if ($this->cost) {

            $cost = Money::ofMinor($this->cost, $currency->_getDefaultCurrencyFromDB()->iso,new CashContext(1), RoundingMode::DOWN);

            return $cost->getAmount();


        }
    }

    private function getHandlingCostAsFloat()
    {
        $currency = new Currency();

        if ($this->handling_cost) {

            $handling_cost = Money::ofMinor($this->handling_cost, $currency->_getDefaultCurrencyFromDB()->iso,new CashContext(1), RoundingMode::DOWN);

            return $handling_cost->getAmount();


        }
    }


}



