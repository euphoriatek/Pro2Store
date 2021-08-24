<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  User.joomla
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


use Joomla\CMS\Factory;
use Protostore\Utilities\Utilities;
use Protostore\Cart\Cart;
use Protostore\Order\Order;
use Protostore\Address\Address;

/**
 * Joomla User plugin
 *
 * @since  1.5
 */
class PlgUserProtostore extends JPlugin
{

    public function onUserAfterSave($user, $isnew, $success, $msg)
    {

        $db = Factory::getDbo();


        $query = $db->getQuery(true);

        $query->select('*');
        $query->from($db->quoteName('#__protostore_customer'));
        $query->where($db->quoteName('email') . ' = ' . $db->quote($user['email']));

        $db->setQuery($query);

        $result = $db->loadObject();

        if ($result) {
            // user is a customer - run update

            $object = new stdClass();
            $object->j_user_id = $user['id'];
            $object->email = $user['email'];
            $object->name = $user['name'];
            $object->published = 1;
            $object->modified = Utilities::getDate();

            $db->updateObject('#__protostore_customer', $object, 'email');

        } else {
            // user is not a customer - run insert

            $object = new stdClass();
            $object->id = 0;
            $object->j_user_id = $user['id'];
            $object->email = $user['email'];
            $object->name = $user['name'];
            $object->published = 1;
            $object->created = Utilities::getDate();

            $db->insertObject('#__protostore_customer', $object);
        }


    }


    /**
     * function onUserLogin
     *
     * Aim:
     *
     * 1) We need to make sure that newly logged in customers are setup in the P2S customers table.
     * 2) We need to move all cookie reference cart items over to the user to make sure the items stay in the cart after login
     * 3) We need to "mop up" any previous guest orders using this email address and associate them with this user
     *
     *
     * @param $user
     * @param array $options
     * @throws Exception
     */

    public function onUserLogin($user, $options = array())
    {

        $cookieid = Factory::getApplication()->input->cookie->get('yps-cart', null);

        if ($cookieid) {

            $db = Factory::getDbo();


            /**
             *
             * AIM 1 - set the user in the P2S customer db
             *
             */

            // check if user is a customer
            $query = $db->getQuery(true);

            $query->select('*');
            $query->from($db->quoteName('#__protostore_customer'));
            $query->where($db->quoteName('email') . ' = ' . $db->quote($user['email']));

            $db->setQuery($query);

            $result = $db->loadObject();

            $userid = $this->_getUserId($user['email']);

            if ($result) {
                // user is a customer - run update

                $object = new stdClass();
                $object->email = $user['email'];
                $object->name = $user['fullname'];
                $object->published = 1;
                $object->modified = Utilities::getDate();

                $db->updateObject('#__protostore_customer', $object, 'email');

            } else {
                // user is not a customer - run insert

                $object = new stdClass();
                $object->id = 0;
                $object->j_user_id = $userid;
                $object->email = $user['email'];
                $object->name = $user['fullname'];
                $object->published = 1;
                $object->created = Utilities::getDate();

                $db->insertObject('#__protostore_customer', $object);


            }

            /**
             *
             * Aim 2 - Mop up the guest orders associated with this email address
             *
             */

            // now check the emails on guest orders and associate them if not.
            $this->mopGuestOrders($userid, $user['email']);

            /**
             *
             * Aim 3 - move all cart items over to the logged in user
             *
             */

            //also move all guest cart items to logged in cart items.
            $this->convertGuestCarts($userid, $cookieid);

        }

    }


    public function onUserLogout($user, $options = array())
    {


        // To prevent the guest checkout from showing addresses after logout,
        // make sure to remove all cart addresses and the cookie.

//        Cart::destroyCookie();

        return true;

    }

    public function onUserAfterDelete($user, $success, $msg)
    {


        $db = Factory::getDbo();

        // check if user is a customer
        $query = $db->getQuery(true);

        $query->select('*');
        $query->from($db->quoteName('#__protostore_customer'));
        $query->where($db->quoteName('email') . ' = ' . $db->quote($user['email']));

        $db->setQuery($query);

        $result = $db->loadObject();

        if ($result) {

            $query = $db->getQuery(true);

            $conditions = array(
                $db->quoteName('id') . ' = ' . $db->quote($result->id)
            );

            $query->delete($db->quoteName('#__protostore_customer'));
            $query->where($conditions);

            $db->setQuery($query);

            $db->execute();


        }


    }


    private function _getUserId($email)
    {

        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('id');
        $query->from($db->quoteName('#__users'));
        $query->where($db->quoteName('email') . ' = ' . $db->quote($email));

        $db->setQuery($query);

        return $db->loadResult();

    }


    /**
     *
     * mopGuestOrders
     *
     *
     * since 1.1.0
     */

    private function mopGuestOrders($userid, $user_email)
    {

        // Get the DB
        $db = Factory::getDbo();

        // now get all orders with a costomerid of '0'
        $query = $db->getQuery(true);

        $query->select('id');
        $query->from($db->quoteName('#__protostore_order'));
        $query->where($db->quoteName('customer_id') . ' = 0');

        $db->setQuery($query);

        $guestOrders = $db->loadColumn();

        $ordersToCheck = array();

        foreach ($guestOrders as $guestOrder) {
            $ordersToCheck[] = new Order($guestOrder);
        }

        foreach ($ordersToCheck as $order) {
            if ($order->billingEmail == $user_email) {
                $order->customerid = Utilities::getCustomerIdByCurrentUserId($userid);
                $order->save();

                $addressToUpdate = new Address($order->billing_address_id);

                $addressToUpdate->setCustomerId($order->customerid);
                $addressToUpdate->save();

            }
        }


    }

    /**
     *
     * convertGuestCarts
     *
     * @param $userid
     *
     * @since 1.1.0
     */


    private function convertGuestCarts($userid, $cookieid)
    {
        // Get the DB
        $db = Factory::getDbo();


        //first, get the id of the cart with this userid, if not found, create one.

        $query = $db->getQuery(true);

        $query->select('id');
        $query->from($db->quoteName('#__protostore_cart'));
        $query->where($db->quoteName('user_id') . ' = ' . $db->quote($userid));

        $db->setQuery($query);

        $cartRowFromUserId = $db->loadResult();

        if (!$cartRowFromUserId) {
            // create a new row with this user id
            $cart = new stdClass();
            $cart->id = 0;
            $cart->user_id = $userid;
            $cart->shipping_address_id = NULL;
            $cart->billing_address_id = NULL;
            $cart->shipping_type = 'defaultshipping';

            $db->insertObject('#__protostore_cart', $cart);

            $cartRowFromUserId = $db->insertid();

        }


        // ok, we have a row with this user id ($cartRowFromUserId), now we need to get the id of the row with this cookie id, so as we know that cart items row we need to change

        $query = $db->getQuery(true);

        $query->select('id');
        $query->from($db->quoteName('#__protostore_cart'));
        $query->where($db->quoteName('cookie_id') . ' = ' . $db->quote($cookieid));

        $db->setQuery($query);

        $cartRowFromCookieId = $db->loadResult();


        // now we need to set each of the cart items table "cart_id" with this new id.
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('cart_id') . ' = ' . $db->quote($cartRowFromUserId)
        );

        $conditions = array(
            $db->quoteName('cart_id') . ' = ' . $db->quote($cartRowFromCookieId)
        );

        $query->update($db->quoteName('#__protostore_cart_item'))->set($fields)->where($conditions);

        $db->setQuery($query);

        $db->execute();


    }


}
