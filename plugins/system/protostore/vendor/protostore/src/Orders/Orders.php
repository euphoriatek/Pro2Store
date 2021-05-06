<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

namespace Protostore\Orders;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

class Orders
{

    public $db;

    public function __construct()
    {

        $this->db = Factory::getDbo();

    }


    public function getOrderTotal($id)
    {

        // get the cart list
        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_orders'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($id));

        $this->db->setQuery($query);

        $order = $this->db->loadObject();

        $itemsOrdered = json_decode($order->ordered_items);

        // init total var at 0
        $total = 0;

        // loop through the cart list
//        foreach ($itemsOrdered as $itemOrdered) {


//            // get the saved item options
//            $item_options = json_decode($itemOrdered->data);
//            // get the baseprice of the item
//            $baseprice = $this->getBaseprice($result->joomla_item_id);
//
//            //init the sum var as the baseprice
//            $sum = $baseprice;
//
//            // get the options as set in the item database
//            // keeps carts up to date with the latest product data
//            $dboptions = $this->getOptions($result->joomla_item_id);
//            $dboptions = json_decode($dboptions, true);
//
//            // loop through the saved item options and match them to the current product options
//            foreach ($item_options->options as $option) {
//                if ($dboptions[$option->name]['option'][$option->value]['pricemodval']) {
//                    $multiplier = $dboptions[$option->name]['option'][$option->value]['modifiertype'];
//                    $pricemodval = $dboptions[$option->name]['option'][$option->value]['pricemodval'];
//                    $optiontype = $dboptions[$option->name]['option'][$option->value]['optiontype'];
//
//                    // calculate how much to add or subtract from the sum
//                    if ($multiplier == 'Percent') {
//                        if ($optiontype == 'Add') {
//                            $sum += ($pricemodval / 100) * $baseprice;
//                        } else {
//                            $sum -= ($pricemodval / 100) * $baseprice;
//                        }
//
//                    } elseif ($multiplier == 'Amount') {
//                        if ($optiontype == 'Add') {
//                            $sum += $pricemodval;
//                        } else {
//                            $sum -= $pricemodval;
//                        }
//                    }
//                }
//
//            }
//            // add the summed amount to the total
//            $total += $sum;
//        }

        return $total;

    }


    public function getOrderList($publishedOnly = true)
    {

        $query = $this->db->getQuery(true);

        $query->select(array('a.*', 'b.name', 'b.email'));
        $query->from($this->db->quoteName('#__protostore_orders', 'a'));
        $query->join('INNER', $this->db->quoteName('#__protostore_customers', 'b') . ' ON ' . $this->db->quoteName('b.id') . ' = ' . $this->db->quoteName('a.customer'));
        $query->order($this->db->quoteName('a.order_date') . ' DESC');


        if ($publishedOnly) {
            $query->where($this->db->quoteName('published') . ' = 1');
        }

        $this->db->setQuery($query);

        return $this->db->loadObjectList();

    }


    public static function getAllOrdersValueTotal()
    {

        $orders = self::getAllOrders();

        $total = 0;

        foreach ($orders as $order) {
            $total += $order->order_total;
        }

        return $total;

    }

    public static function getAllOrdersCount()
    {

        $orders = self::getAllOrders();

        return count($orders);

    }


    public static function getAllOrders()
    {
        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('*');
        $query->from($db->quoteName('#__protostore_order'));

        $db->setQuery($query);

        return $db->loadObjectList();
    }


    public static function getOrderListByCustomer()
    {

        $user = Factory::getUser();

        if ($user->guest) {
            return false;
        } else {

            $db = Factory::getDbo();
            $query = $db->getQuery(true);

            $query->select(array('a.*', 'b.name', 'b.email', 'b.j_user_id'));
            $query->from($db->quoteName('#__protostore_order', 'a'));
            $query->join('INNER', $db->quoteName('#__protostore_customer', 'b') . ' ON ' . $db->quoteName('b.id') . ' = ' . $db->quoteName('a.customer'));
            $query->where($db->quoteName('b.j_user_id') . ' = ' . $db->quote($user->id));
            $query->order($db->quoteName('a.order_date') . ' DESC');

            $db->setQuery($query);

            return $db->loadObjectList();
        }
    }

}
