<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

namespace Protostore\Total;

defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Factory;

use Protostore\Cart\CartFactory;
use Protostore\Coupon\Coupon;
use Protostore\Coupon\CouponFactory;
use Protostore\Productoptions\Productoptions;
use Protostore\Productoption\Productoption;
use Protostore\Cart\Cart;
use Protostore\Price\Price;
use Protostore\Shipping\Shipping;
use Protostore\Tax\Tax;

class Total
{

    public $db;
    public $app;
    private $cookie_id;

    public function __construct()
    {
        $this->db = Factory::getDbo();
        $this->app = Factory::getApplication();
        $this->cookie_id = $this->app->input->cookie->get('yps-cart', null);
    }


    /**
     *
     * Function - getGrandTotal
     *
     * Returns the Grand Total for any cart in integer format
     *
     * @return integer
     */

    public static function getGrandTotal()
    {

        $total = self::getSubTotal();

        $couponDiscount = CouponFactory::calculateDiscount($total, false, true);

        if ($couponDiscount > $total) {
            $couponDiscount = $total;
        }

        $total = $total - $couponDiscount;
        $total = $total + Shipping::getTotalShippingFromPlugin();
        $total = $total + Tax::calculateTotalTax();

        return $total;

    }


    /**
     *
     * Function - getSubTotal
     *
     * Returns the subtotal for any given cart as an integer
     *
     * @param false $integer
     * @param false $float
     * @return integer
     */

    public static function getSubTotal()
    {


        $cart = CartFactory::get();
        $results = $cart->cartItems;

        // init total var at 0
        $total = 0;

        if ($results) {

            // loop through the cart list
            foreach ($results as $result) {

                $total += (int) $result->totalCost;
            }

        }

        return $total;

    }

    /**
     * Function - getItemTotal
     *
     * returns to the total for the item in the cart in integer format
     *
     * @param $cartitemid
     * @return integer
     */


    public static function getItemTotal($cartitemid)
    {
        // Get Helpers
        $productOptionsHelper = new Productoptions();

        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('*');
        $query->from($db->quoteName('#__protostore_carts'));
        $query->where($db->quoteName('id') . ' = ' . $db->quote($cartitemid));

        $db->setQuery($query);

        $result = $db->loadObject();

        // init total var at 0
        $total = 0;
        // get the saved item options
        $item_options = json_decode($result->item_options);

        // get the baseprice of the item
//        $baseprice = $this->getBaseprice($result->joomla_item_id);
        $baseprice = Price::getBasePrice($result->joomla_item_id);

        //init the sum var as the baseprice
        $sum = $baseprice;

        // get the options as set in the item database
        // keeps carts up to date with the latest product data
        $dboptions = $productOptionsHelper->getOptions($result->joomla_item_id);

        // loop through the saved item options and match them to the current product options
        foreach ($item_options->options as $option) {


            $dboption = new Productoption($option->optionid);

//            return json_encode($dboption);

            if ($dboption->modifiervalue) {

                if ($dboption->modifiertype === 'perc') {
                    if ($dboption->modifier == 'add') {
                        $sum += ($dboption->modifiervalue / 100) * $baseprice;
                    } else {
                        $sum -= ($dboption->modifiervalue / 100) * $baseprice;
                    }
                } elseif ($dboption->modifiertype === 'amount') {
                    if ($dboption->modifier == 'add') {
                        $sum += $dboption->modifiervalue;
                    } else {
                        $sum -= $dboption->modifiervalue;
                    }
                }

            }


        }
        // add the summed amount to the total
        $total += $sum;

        return $total;

    }

    public static function formatTotal($total, $currency)
    {

    }

}
