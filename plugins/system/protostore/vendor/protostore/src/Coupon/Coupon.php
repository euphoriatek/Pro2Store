<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */


namespace Protostore\Coupon;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Protostore\Currency\Currency;
use Protostore\Shipping\Shipping;
use Protostore\Utilities\Utilities;

class Coupon
{

    private $db;


    public function __construct($cart_itemid)
    {

        $this->db = Factory::getDbo();

    }

    /**
     *
     * function to check the validiity of a coupon code
     *
     *
     * @param $code
     * @return false|mixed
     */


    public static function checkCouponValidity($code)
    {

        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('*');
        $query->from($db->quoteName('#__protostore_discount'));
        $query->where($db->quoteName('couponcode') . ' = ' . $db->quote($code));
        $query->where($db->quoteName('expiry_date') . ' > ' . $db->quote(Utilities::getDate()));

        $db->setQuery($query);

        $coupon = $db->loadObject();

        if ($coupon) {
            return $coupon;
        } else {
            return false;
        }

    }

    /**
     *
     * Calculates the discount amount
     *
     * @param $subTotal
     * @param false $integer
     * @param false $float
     * @return float|int|mixed|string|string[]|null
     *
     */

    public static function calculateDiscount($subTotal)
    {

        $cookieId = Utilities::getCookieID();


        //get the current applied coupon
        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('*');
        $query->from($db->quoteName('#__protostore_coupon_cart'));
        $query->where($db->quoteName('cookie_id') . ' = ' . $db->quote($cookieId));

        $db->setQuery($query);

        $appliedCoupon = $db->loadObject();


        //if there's a coupon applied
        if ($appliedCoupon) {


            //get the discount from the DB
            $query = $db->getQuery(true);

            $query->select('*');
            $query->from($db->quoteName('#__protostore_discount'));
            $query->where($db->quoteName('id') . ' = ' . $db->quote($appliedCoupon->coupon_id));
            $query->where($db->quoteName('expiry_date') . ' > ' . $db->quote(Utilities::getDate()));

            $db->setQuery($query);

            $coupon = $db->loadObject();

            // get and set the actual ammount
            $total = $coupon->amount;

            if ($coupon->discount_type == 'freeship') {
                $total = Shipping::getTotalShippingFromPlugin();
            }


            // if the discount type is perc, then divide the number by 100
            if ($coupon->discount_type == 'perc') {
                $total = $subTotal * ($total / 100);
            }


            return $total;
        } else {
            //No coupon Applied


            return 0;
        }

    }


    public static function isCouponApplied()
    {

        $cookieId = Utilities::getCookieID();

        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('*');
        $query->from($db->quoteName('#__protostore_coupon_cart'));
        $query->where($db->quoteName('cookie_id') . ' = ' . $db->quote($cookieId));

        $db->setQuery($query);

        $appliedCoupon = $db->loadObject();

        if ($appliedCoupon) {
            //now get the coupon and make sure it's valid

            $query = $db->getQuery(true);

            $query->select('*');
            $query->from($db->quoteName('#__protostore_discount'));
            $query->where($db->quoteName('id') . ' = ' . $db->quote($appliedCoupon->coupon_id));
            $query->where($db->quoteName('expiry_date') . ' > ' . $db->quote(Utilities::getDate()));

            $db->setQuery($query);

            return $db->loadObject();


        } else {
            return false;
        }


    }


    public static function removeCoupon()
    {

        $cookieId = Utilities::getCookieID();

        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $conditions = array(
            $db->quoteName('cookie_id') . ' = ' . $db->quote($cookieId)
        );

        $query->delete($db->quoteName('#__protostore_coupon_cart'));
        $query->where($conditions);

        $db->setQuery($query);

        $result = $db->execute();

        if ($result) {
            return 'ok';
        } else {
            return 'ko';
        }


    }

    public static function getCurrentAppliedCoupon()
    {

        $cookieId = Utilities::getCookieID();
        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('*');
        $query->from($db->quoteName('#__protostore_coupon_cart'));
        $query->where($db->quoteName('cookie_id') . ' = ' . $db->quote($cookieId));

        $db->setQuery($query);

        $appliedCoupon = $db->loadObject();

        if ($appliedCoupon) {

            $query = $db->getQuery(true);

            $query->select('*');
            $query->from($db->quoteName('#__protostore_discount'));
            $query->where($db->quoteName('id') . ' = ' . $db->quote($appliedCoupon->coupon_id));
            $query->where($db->quoteName('expiry_date') . ' > ' . $db->quote(Utilities::getDate()));

            $db->setQuery($query);

            return $db->loadObject();
        } else {
            return false;
        }

    }


}
