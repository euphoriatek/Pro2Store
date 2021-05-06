<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */


namespace Protostore\Cart;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Protostore\Cartitem\Cartitem;
use Protostore\Currency\Currency;
use Protostore\Product\Product;
use Protostore\Total\Total;
use Protostore\Shipping\Shipping;
use Protostore\Coupon\Coupon;
use Protostore\Utilities\Utilities;
use Protostore\Orderlog\Orderlog;
use Protostore\Checkoutnote\Checkoutnote;
use Protostore\Tax\Tax;

use stdClass;

class Cart
{


    protected $db;
    protected $app;

    public $id;
    public $user_id;
    public $cookie_id;
    public $coupon_id;
    public $shipping_address_id;
    public $billing_address_id;
    public $shipping_type;
    public $count;
    public $cartItems;
    public $cartItemsCombined;


    public function __construct($cartid = null)
    {
        $this->db = Factory::getDbo();
        $this->app = Factory::getApplication();

        if ($cartid) {
            $this->initCart($cartid);
        } else {
            $this->newCart();
        }

    }

    private function initCart($cartid)
    {
        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_cart'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($cartid));

        $this->db->setQuery($query);

        $cart = $this->db->loadObject();

        $this->id = $cart->id;
        $this->user_id = $cart->user_id;
        $this->cookie_id = $cart->cookie_id;
        $this->coupon_id = $cart->coupon_id;
        $this->shipping_address_id = $cart->shipping_address_id;
        $this->billing_address_id = $cart->billing_address_id;
        $this->shipping_type = $this->getShippingType($cart->shipping_type);
        $this->cartItems = $this->getCartItems();
        $this->cartItemsCombined = $this->getCartItemsCombined();
        $this->count = $this->getCount();


    }

    private function newCart()
    {
        $user = Factory::getUser();
        $shipping = Shipping::getPrioritisedShipping();

        $object = new stdClass();
        $object->id = 0;
        $object->user_id = $user->id;
        $object->shipping_type = ($shipping->name ? $shipping->name : 'defaultshipping');
        if ($user->guest) {
            $object->cookie_id = Utilities::getCookieID();
        } else {
            $object->cookie_id = 0;
        }

        $this->db->insertObject('#__protostore_cart', $object);
        $this->initCart($this->db->insertId());
    }


    public function getCartItems()
    {

        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('id');
        $query->from($db->quoteName('#__protostore_carts'));

        $query->where($db->quoteName('cart_id') . ' = ' . $db->quote($this->id));


        $db->setQuery($query);

        $items = $db->loadColumn();

        $cartItems = array();

        foreach ($items as $item) {
            $cartItems[] = new Cartitem($item);
        }

        return $cartItems;

    }


    public function getCartItemsCombined()
    {


        $query = $this->db->getQuery(true);

        $query->select('id');
        $query->from($this->db->quoteName('#__protostore_carts'));

        $query->where($this->db->quoteName('cart_id') . ' = ' . $this->db->quote($this->id));
        $query->group($this->db->quoteName('joomla_item_id'));
        $query->group($this->db->quoteName('joomla_item_id'));
        $this->db->setQuery($query);

        $items = $this->db->loadColumn();

        $cartItems = array();

        foreach ($items as $item) {
            $cartItems[] = new Cartitem($item, $this->id);
        }

        return $cartItems;

    }


    private function getCount()
    {

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_carts'));
        $query->where($this->db->quoteName('cart_id') . ' = ' . $this->db->quote($this->id));

        $this->db->setQuery($query);

        $items = $this->db->loadObjectList();

        $count = 0;

        foreach ($items as $item) {
            $count += $item->amount;
        }

        return $count;

    }


    private function getShippingType($shippingType)
    {

        if ($shippingType) {
            return $shippingType;
        } else {
            return 'defaultshipping';
        }
    }


    /**
     * @param mixed $shipping_address_id
     */
    public function setShippingAddressId($shipping_address_id)
    {
        $this->shipping_address_id = $shipping_address_id;
    }

    /**
     * @param mixed $billing_address_id
     */
    public function setBillingAddressId($billing_address_id)
    {
        $this->billing_address_id = $billing_address_id;
    }

    public function save()
    {

        $object = new stdClass();
        $object->id = $this->id;
        $object->user_id = $this->user_id;
        $object->cookie_id = $this->cookie_id;
        $object->coupon_id = $this->coupon_id;
        $object->billing_address_id = $this->billing_address_id;
        $object->shipping_address_id = $this->shipping_address_id;

        $this->db->updateObject('#__protostore_cart', $object, 'id');
    }


    public static function getCurrentCartId()
    {

        $db = Factory::getDbo();
        $user = Factory::getUser();

        // now check if there is already a cart for this cookie
        $query = $db->getQuery(true);

        $query->select('id');
        $query->from($db->quoteName('#__protostore_cart'));

        if ($user->guest) {
            $query->where($db->quoteName('cookie_id') . ' = ' . $db->quote(Utilities::getCookieID()));
        } else {
            $query->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));
        }

        $db->setQuery($query);

        $currentCartId = $db->loadResult();

        if ($currentCartId) {
            return $currentCartId;
        } else {
            return false;

        }

    }

    public static function countCartItems($cartId)
    {

        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('COUNT(*)');
        $query->from($db->quoteName('#__protostore_carts'));
        $query->where($db->quoteName('cart_id') . ' = ' . $db->quote($cartId));

        $db->setQuery($query);

        return $db->loadResult();

    }


    public static function isGuestAddressSet()
    {

        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('shipping_address_id');
        $query->from($db->quoteName('#__protostore_cart'));
        $query->where($db->quoteName('id') . ' = ' . $db->quote(self::getCurrentCartId()));

        $db->setQuery($query);

        $result = $db->loadResult();

        if ($result) {
            return true;
        } else {
            return false;
        }


    }

    /**
     *
     * function destroyCartAddress()
     *
     * Called when logging out to clear all remaining data
     *
     *
     */

    public static function destroyCartAddress()
    {

        $db = Factory::getDbo();

        $object = new stdClass();
        $object->id = Cart::getCurrentCartId();
        $object->shipping_address_id = '';
        $object->billing_address_id = '';

        $result = $db->updateObject('#__protostore_cart', $object, 'id');

    }

    /**
     *
     * function destroyCookie()
     *
     * Called when logging out to clear all remaining data
     *
     *
     */

    public static function destroyCookie()
    {

        $db = Factory::getDbo();

        $object = new stdClass();
        $object->id = Cart::getCurrentCartId();
        $object->cookie_id = '';

        $result = $db->updateObject('#__protostore_cart', $object, 'id');

    }


    public static function setCartAddress($address_id, $type)
    {


        $currentCartId = self::getCurrentCartId();

        switch ($type) {

            case 'shipping':

                $object = new stdClass();

                $object->id = $currentCartId;
                $object->shipping_address_id = $address_id;

                $result = Factory::getDbo()->updateObject('#__protostore_cart', $object, 'id');

                if ($result) {
                    return 'ok';
                } else {
                    return 'ko';
                }

            case 'billing':

                $object = new stdClass();

                $object->id = $currentCartId;
                $object->billing_address_id = $address_id;

                $result = Factory::getDbo()->updateObject('#__protostore_cart', $object, 'id');

                if ($result) {
                    return 'ok';
                } else {
                    return 'ko';
                }

        }

    }


    public static function setBillingAsShipping()
    {

        $currentCartId = Cart::getCurrentCartId();

        $object = new stdClass();

        $object->id = $currentCartId;
        $object->billing_address_id = self::getCartAddressID('shipping');

        $result = Factory::getDbo()->updateObject('#__protostore_cart', $object, 'id');

        if ($result) {
            return 'ok';
        } else {
            return 'ko';
        }

    }

    public static function removeBillingAsShipping()
    {

        $db = Factory::getDbo();

        $object = new stdClass();
        $object->id = Cart::getCurrentCartId();
        $object->billing_address_id = 0;

        $result = $db->updateObject('#__protostore_cart', $object, 'id');

        if ($result) {
            return 'ok';
        } else {
            return 'ko';
        }

    }


    public static function getCartAddressID($type)
    {

        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select($type . '_address_id');
        $query->from($db->quoteName('#__protostore_cart'));
        $query->where($db->quoteName('id') . ' = ' . $db->quote(Cart::getCurrentCartId()));

        $db->setQuery($query);

        return $db->loadResult();

    }

    /**
     * @param $product \Protostore\Product\Product;
     * @param $cart_itemid
     * @return false|mixed|null
     */

    public static function doStockCompare(Product $product, $cart_itemid)
    {

        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('amount');
        $query->from($db->quoteName('#__protostore_carts'));
        $query->where($db->quoteName('id') . ' = ' . $db->quote($cart_itemid));

        $db->setQuery($query);

        $currentAmount = $db->loadResult();

        if ($currentAmount >= $product->stock) {
            return false;
        } else {
            return $product->stock - $currentAmount;
        }

    }


    /**
     * function convertToOrder()
     *
     * Call this function from the payment plugin. It simply turns the Cart object into an order in Pro2Store.
     *
     *
     * @return bool
     *
     * @since 1.0
     */

    public static function convertToOrder($paymentMethod, $shippingMethod = 'default', $vendorToken = '', $sendEmail = false)
    {

        $currencyHelper = new Currency();
        $date = Utilities::getDate();
        $cookie_id = Utilities::getCookieID();

        $cart = new Cart(self::getCurrentCartId());


        if (Factory::getUser()->guest) {
            $customer_id = 0;
        } else {
            $customer_id = Utilities::getCustomerIdByCurrentUserId();
        }


        $db = Factory::getDbo();


        // Build Order Object
        $object = new stdClass();
        $object->id = 0;
        // Todo - "guest checkout" - Build a getCustomer function to handle guest checkout.
        $object->customer = $customer_id;

        if ($customer_id == 0) {
            //Todo - "guest checkout" - for the minute lets generate a uniqid() to identify the order for guest customers
            $object->guest_pin = uniqid();
        }

        $object->order_date = $date;
        $object->order_number = self::_generateOrderId(rand(10000, 99999));
        $object->order_paid = 0;
        $object->order_status = 'P';
        $object->order_total = Total::getGrandTotal(true);
        $object->order_subtotal = Total::getSubTotal(true);
//        $object->shipping_total = Shipping::calculateTotalShipping(true);
        $object->shipping_total = Shipping::getTotalShippingFromPlugin(true);
        $object->tax_total = Tax::calculateTotalTax(true);
        $object->currency = $currencyHelper->currency->iso;
        $object->payment_method = $paymentMethod;
        $object->vendor_token = $vendorToken;
        $object->billing_address_id = $cart->billing_address_id;
        $object->shipping_address_id = $cart->shipping_address_id;
        $object->published = 1;
        if ($coupon = Coupon::isCouponApplied()) {
            $object->discount_code = $coupon->couponcode;
        }

        $object->discount_total = Coupon::calculateDiscount(Total::getSubTotal(false, true), true, false);
        if ($currentNote = Checkoutnote::getCurrentNote()) {
            $object->customer_notes = $currentNote->note;
        } else {
            $object->customer_notes = '';
        }


        $result = $db->insertObject('#__protostore_order', $object);
        $orderID = $db->insertid();
        //now set the order items

        $cartItems = $cart->getCartItems();

        foreach ($cartItems as $cartItem) {

            $object = new stdClass();
            $object->id = 0;
            $object->order_id = $orderID;
            $object->j_item = $cartItem->joomla_item_id;
            $object->j_item_cat = $cartItem->joomla_item_cat;
            $object->j_item_name = $cartItem->joomla_item_title;
            $object->item_options = json_encode($cartItem->selected_options);
            $object->price_at_sale = Currency::translateToInt($cartItem->bought_at_price, $currencyHelper->currency->iso);
            $object->amount = $cartItem->count;

            $result = $db->insertObject('#__protostore_order_products', $object);

            if ($cartItem->manage_stock_enabled == 1) {

                $currentStock = Product::getCurrentStock($cartItem->product->id);
                $newStockLevel = ($currentStock - $cartItem->count);

                if ($newStockLevel <= 0) {
                    $newStockLevel = 0;
                }

                $stockUpdate = new stdClass();
                $stockUpdate->id = $cartItem->product->id;
                $stockUpdate->stock = $newStockLevel;

                $db->updateObject('#__protostore_product', $stockUpdate, 'id');
            }

        }


        //Clear the cart
        $query = $db->getQuery(true);
        $conditions = array(
            $db->quoteName('cart_id') . ' = ' . $db->quote($cart->id),
        );
        $query->delete($db->quoteName('#__protostore_carts'));
        $query->where($conditions);
        $db->setQuery($query);
        $db->execute();

        //Clear coupons
        $query = $db->getQuery(true);
        $conditions = array(
            $db->quoteName('cookie_id') . ' = ' . $db->quote($cookie_id)
        );
        $query->delete($db->quoteName('#__protostore_coupon_cart'));
        $query->where($conditions);
        $db->setQuery($query);
        $db->execute();


        if ($sendEmail) {
            // get the plugin functions
            PluginHelper::importPlugin('protostoresystem');
            Factory::getApplication()->triggerEvent('onSendProtoStoreEmail', array('pending', $orderID));

        }

        new Orderlog(false, $orderID, Text::_('COM_PROTOSTORE_ORDER_IS_CREATED_LOG'));


        if ($result) {
            PluginHelper::importPlugin('protostoresystem');
            Factory::getApplication()->triggerEvent('onOrderCreated', array($orderID));

            return $orderID;
        }

    }


    private static function _generateOrderId($seed)
    {

        $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $base = strlen($charset);
        $result = '';
        $len = 5;
        $now = explode(' ', microtime())[1];
        while ($now >= $base) {
            $i = $now % $base;
            $result = $charset[$i] . $result;
            $now /= $base;
        }
        $rand = substr($result, -$len);

        return strtoupper($rand . $seed);
    }


}
