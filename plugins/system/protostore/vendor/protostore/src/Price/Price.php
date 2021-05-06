<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Price;
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Factory;
use Protostore\Utilities\Utilities;
use Protostore\Productoption\Productoption;

class Price
{

    /**
     * @param null $itemid
     *
     * @return integer
     *
     * @since 1.0
     */


    public static function getBasePrice($itemid = null)
    {

        if ($itemid == null) {
            $itemid = Utilities::getCurrentItemId();
        }

        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $query->select('base_price');
        $query->from($db->quoteName('#__protostore_product'));
        $query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($itemid));

        $db->setQuery($query);

        return (int)$db->loadResult();

    }


    public static function getDiscountAmount($itemid = null)
    {

        if ($itemid == null) {
            $itemid = Utilities::getCurrentItemId();
        }

        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $query->select('discount');
        $query->from($db->quoteName('#__protostore_product'));
        $query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($itemid));

        $db->setQuery($query);

        return $db->loadResult();

    }


    public static function getDiscountType($itemid = null)
    {

        if ($itemid == null) {
            $itemid = Utilities::getCurrentItemId();
        }

        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $query->select('discount_type');
        $query->from($db->quoteName('#__protostore_product'));
        $query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($itemid));

        $db->setQuery($query);

        return $db->loadResult();

    }


    public static function calculateItemDiscount($itemid = null)
    {
        if ($itemid == null) {
            $itemid = Utilities::getCurrentItemId();
        }

        $baseprice = self::getBasePrice($itemid);
        $discountAmount = self::getDiscountAmount($itemid);
        $discountType = self::getDiscountType($itemid);

        if ($discountAmount && $discountType) {
            $change_value = 0;

            if ($discountType == 'perc') {
                $change_value += (($discountAmount / 100) * $baseprice);
            } else {
                $change_value += $discountAmount;
            }


            return (int)$change_value;


        } else {
            return 0;
        }

    }

    public static function calculatePriceAfterDiscount($itemid = null)
    {

            if ($itemid == null) {
            $itemid = Utilities::getCurrentItemId();
        }

        $baseprice = self::getBasePrice($itemid);
        $discountAmount = self::getDiscountAmount($itemid);
        $discountType = self::getDiscountType($itemid);

        if ($discountAmount && $discountType) {
            $change_value = 0;

            if ($discountType == 'perc') {
                $change_value += (($discountAmount / 100) * $baseprice);
            } else {
                $change_value += $discountAmount;
            }
            $price = $baseprice - $change_value;

            return $price;


        } else {
            return $baseprice;
        }

    }


    public static function calculatePrice($ids = array(), $itemid, $multiplier = 1)
    {

        $baseprice = self::getBasePrice($itemid);

        if (!$baseprice) {
            return 0;
        }

        $change_value = 0;

        $amountToAddon = 0;

        if ($ids) {

            $pulledSelectedOptions = array();

            foreach ($ids as $selectedOptions) {
                $pulledSelectedOptions[] = new Productoption($selectedOptions);
            }

            foreach ($pulledSelectedOptions as $option) {
                if ($option->modifiervalue) {

                    if ($option->modifiertype == 'perc') {
                        $change_value += Utilities::getPercentOfNumber($baseprice, $option->modifiervalue);
                    } elseif ($option->modifiertype == 'amount') {
                        $change_value = $option->modifiervalue;
                    }

                    if ($option->modifier === 'add') {
                        $amountToAddon = ($amountToAddon + $change_value);
                    } else {
                        $amountToAddon = (($amountToAddon - $change_value));
                    }

                }
            }
        }


        $newPrice = $baseprice - self::calculateItemDiscount($itemid);

        $newPrice = $newPrice + $amountToAddon;

        $newPrice = $newPrice * $multiplier;
        if ($newPrice < 0) {
            $newPrice = 0;
        }


        return (int)$newPrice;


    }

    public static function formatPriceForDB($price)
    {
        if (strpos($price, '.') == false) {
            $price = $price . '.00';
        }

        return preg_replace("/[^0-9.]/", "", $price);
    }


}
