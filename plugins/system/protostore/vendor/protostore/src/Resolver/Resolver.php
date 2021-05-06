<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

namespace Protostore\Resolver;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Protostore\Price\Price;
use Protostore\Product\Product;
use Protostore\Currency\Currency;

use Exception;

class Resolver
{

    public static function getSKU($itemid)
    {

        try {
            $product = new Product($itemid);

        } catch (Exception $e) {
            return true;
        }
        return $product->getSku();

    }


    public static function getBasePrice($itemid)
    {

        try {
            $product = new Product($itemid);

        } catch (Exception $e) {
            return false;
        }
        $currencyHelper = new Currency();

        return Currency::formatNumberWithCurrency($product->getBaseprice(), $currencyHelper->currency->iso);

    }

    public static function getDiscountedPrice($itemid)
    {

        try {
            $product = new Product($itemid);

        } catch (Exception $e) {
            return true;
        }
        $currencyHelper = new Currency();
        $price = $product->getBaseprice() - Price::calculateItemDiscount($itemid, true);

        return Currency::formatNumberWithCurrency($price, $currencyHelper->currency->iso, '', false);
    }


    public static function getStock($itemid)
    {
        try {
            $product = new Product($itemid);

        } catch (Exception $e) {
            return true;
        }
        if ($product->getStock() == 0) {
            return "'0'";
        } else {
            return (string)$product->getStock();
        }

    }


    public static function getWeight($itemid)
    {

        try {
            $product = new Product($itemid);


        } catch (Exception $e) {
            return true;
        }
        return $product->getWeight() . $product->getWeightUnit();

    }

    public static function getFlatFee($itemid)
    {

        try {

            $product = new Product($itemid);

        } catch (Exception $e) {
            return true;
        }
        $currencyHelper = new Currency();

        return Currency::formatNumberWithCurrency($product->getFlatfee(), $currencyHelper->currency->iso);

    }

}
