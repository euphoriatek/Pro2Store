<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */


namespace Protostore\Cartitem;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Protostore\Currency\Currency;
use Protostore\Productoption\Productoption;


use stdClass;

class Cartitem
{

    private $db;

    public $cart_id;
    public $cart_itemid;
    public $joomla_item_title;
    public $joomla_item_id;
    public $joomla_item_cat;
    public $product;
    public $selected_options;
    public $item_options;
    public $item_options_raw;
    public $item_options_clear;
    public $bought_at_price;
    public $bought_at_price_formatted;
    public $images;
    public $added;
    public $manage_stock_enabled;
    public $count;
    public $totalCost;

    public function __construct($cart_itemid, $cart_id = null)
    {

        $this->db = Factory::getDbo();
        $this->cart_itemid = $cart_itemid;
        $this->cart_id = $cart_id;
        $this->initCartitem($cart_itemid);
    }


    private function initCartitem($cart_itemid)
    {

        $query = $this->db->getQuery(true);

        $query->select(array('a.*', 'b.*',));
        $query->from($this->db->quoteName('#__protostore_carts', 'a'));
        $query->join('INNER', $this->db->quoteName('#__content', 'b') . ' ON ' . $this->db->quoteName('a.joomla_item_id') . ' = ' . $this->db->quoteName('b.id'));
        $query->where($this->db->quoteName('a.id') . ' = ' . $this->db->quote($cart_itemid));

        $this->db->setQuery($query);

        $cartItem = $this->db->loadObject();

        if ($this->cart_id == null) {
            $this->cart_id = $cartItem->cart_id;
        }

        $this->id = $cartItem->id;
        $this->joomla_item_id = $cartItem->joomla_item_id;
        $this->joomla_item_cat = $cartItem->catid;
        $this->item_options = json_decode($cartItem->item_options);
        $this->item_options_raw = $cartItem->item_options;
        $this->item_options_clear = $cartItem->item_options;
        $this->bought_at_price = $cartItem->bought_at_price;
        $this->joomla_item_title = $cartItem->title;
        $this->added = $cartItem->added;
        $this->bought_at_price_formatted = $this->getFormattedPrice();
        $this->images = json_decode($cartItem->images);
        $this->product = $this->getProductById($cartItem->joomla_item_id);
        $this->selected_options = $this->getProductOptions();
        $this->manage_stock_enabled = $this->isManageStockEnabled();
        $this->count = $cartItem->amount;
        $this->totalCost = $cartItem->amount * $cartItem->bought_at_price;


    }


    private function getProductOptions()
    {

        $selectedOptions = array();


        foreach ($this->item_options as $option) {


            foreach ($option as $selectedOption) {
                $selectedOptions[] = new Productoption($selectedOption->optionid);
            }
        }


        return $selectedOptions;

    }


    private function getProductById($joomla_item_id)
    {

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_product'));
        $query->where($this->db->quoteName('joomla_item_id') . ' = ' . $this->db->quote($joomla_item_id));

        $this->db->setQuery($query);

        return $this->db->loadObject();

    }


    public function getFormattedPrice()
    {

        $currencyHelper = new Currency();

        return Currency::translate($this->bought_at_price, $currencyHelper, true);

    }


    private function isManageStockEnabled()
    {
        $query = $this->db->getQuery(true);

        $query->select('manage_stock');
        $query->from($this->db->quoteName('#__protostore_product'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($this->product->id));

        $this->db->setQuery($query);

        return $this->db->loadResult();
    }

    private function getCountOfThisItem()
    {


        $query = $this->db
            ->getQuery(true)
            ->select('COUNT(*)')
            ->from($this->db->quoteName('#__protostore_carts'))
            ->where($this->db->quoteName('cart_id') . " = " . $this->db->quote($this->cart_id), 'AND');
        $query->where($this->db->quoteName('item_options') . " = " . $this->db->quote($this->item_options_raw), 'AND');
        $query->where($this->db->quoteName('joomla_item_id') . " = " . $this->db->quote($this->joomla_item_id));
        $this->db->setQuery($query);
        return $this->db->loadResult();

    }


    public function saveCount($newCount)
    {
        $db = Factory::getDbo();

        $object = new stdClass();
        $object->id = $this->cart_itemid;
        $object->amount = $newCount;

        $result = $db->updateObject('#__protostore_carts', $object, 'id');

        if ($result) {
            return true;
        } else {
            return false;
        }

    }


    public static function change($change, $cart_id, $item_options_raw, $cartItemId)
    {

        $db = Factory::getDbo();


        if ($change < 0) {
            // remove

            $change = abs($change);

            for ($x = 1; $x <= $change; $x++) {

                $query = $db->getQuery(true);

                $conditions = array(
                    $db->quoteName('cart_id') . " = " . $db->quote($cart_id),
                    $db->quoteName('item_options') . ' = ' . $db->quote($item_options_raw)
                );

                $query->delete($db->quoteName('#__protostore_carts'));
                $query->where($conditions);
                $query->setLimit('1');
                $db->setQuery($query);
                $db->execute();

            }


        } else {
            // create

            $query = $db->getQuery(true);

            $query->select('*');
            $query->from($db->quoteName('#__protostore_carts'));
            $query->where($db->quoteName('id') . ' = ' . $db->quote($cartItemId));
            $db->setQuery($query);
            $result = $db->loadObject();


            for ($x = 1; $x <= $change; $x++) {
                $result->id = 0;
                $db->insertObject('#__protostore_carts', $result);

            }

        }


    }


    public static function removeAll($cartitemid)
    {

        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $conditions = array(
            $db->quoteName('id') . " = " . $db->quote($cartitemid),
        );

        $query->delete($db->quoteName('#__protostore_carts'));
        $query->where($conditions);
        $db->setQuery($query);
        $db->execute();
    }


}
