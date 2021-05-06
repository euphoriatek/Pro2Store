<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */


namespace Protostore\Customer;
// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Protostore\Address\Address;
use Protostore\Utilities\Utilities;
use Protostore\Currency\Currency;
use Protostore\Order\Order;
use stdClass;

class Customer
{

    private $db;

    public $id;
    public $name;
    public $email;
    public $j_user_id;
    public $j_user;
    public $orders;
    public $total_orders;
    public $order_total;
    public $addresses;
    public $order_total_integer;


    public function __construct($customerid = null)
    {

        $this->db = Factory::getDbo();

        if ($customerid == null) {
            $customerid = Utilities::getCustomerIdByCurrentUserId();
        }

        $this->initCustomer($customerid);
    }


    private function initCustomer($customerid)
    {


        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_customer'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($customerid));

        $this->db->setQuery($query);

        $customer = $this->db->loadObject();

        if($customer) {
	        $this->id = $customer->id;
	        $this->name = $customer->name;
	        $this->email = $customer->email;
	        $this->j_user_id = $customer->j_user_id;
	        $this->j_user = $this->getJUser();
	        $this->addresses = $this->initCustomerAddresses();
	        $this->orders = $this->setCustomerOrders();
	        $this->total_orders = count($this->orders);
	        $this->order_total = $this->setOrderTotal();
	        $this->order_total_integer = $this->setOrderTotal(true);
        } else {
        	return false;
        }


    }


    private function getJUser()
    {
        return Factory::getUser($this->j_user_id);
    }


    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getJUserId()
    {
        return $this->j_user_id;
    }


    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getOrderTotal()
    {
        return $this->order_total;
    }


    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getTotalOrders()
    {
        return $this->total_orders;
    }


    /**
     * @return array|mixed
     *
     * @since 1.0
     */
    public function getAddresses()
    {
        return $this->addresses;
    }


    private function initCustomerAddresses()
    {

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_customer_address'));
        $query->where($this->db->quoteName('customer_id') . ' = ' . $this->db->quote($this->getId()));

        $this->db->setQuery($query);

        $customerAddresses = $this->db->loadObjectList();

        if ($customerAddresses) {
            $addresses = array();
            foreach ($customerAddresses as $addressid) {
                $addresses[] = new Address($addressid->id);
            }
            return $addresses;
        } else {
            return false;
        }


    }


    public static function createNewCustomer($user)
    {

        $date = Utilities::getDate();
        $db = Factory::getDbo();

        $object = new stdClass();
        $object->id = 0;
        $object->j_user_id = $user->id;
        $object->email = $user->email;
        $object->name = $user->name;
        $object->published = 1;
        $object->created = $date;
        $object->modified = $date;
        $object->created_by = $user->id;

        $db->insertObject('#__protostore_customer', $object);


    }


    public function setOrderTotal($integer = false)
    {
        $total = 0;


        if ($this->orders) {


            foreach ($this->orders as $order) {
                $total += $order->total;
            }

        }

        if ($integer) {
            return $total;
        }

        $currencyHelper = new Currency();

        $total = Currency::formatNumberWithCurrency($total, $currencyHelper->currency->iso);

        return $total;


    }


    public function setCustomerOrders()
    {


        $orders = array();

//        return $orders;

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_order'));
        $query->where($this->db->quoteName('customer') . ' = ' . $this->db->quote($this->id));

        $this->db->setQuery($query);

        $results = $this->db->loadObjectList();

        foreach ($results as $order) {
            $orders[] = new Order($order->id);
        }

        return $orders;

    }


}
