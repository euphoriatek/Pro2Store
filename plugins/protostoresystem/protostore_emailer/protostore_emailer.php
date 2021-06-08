<?php
/**
 * @package     Pro2Store - Emailer
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - pro2store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Protostore\Order\Order;
use Protostore\Currency\Currency;
use Protostore\Customer\Customer;
use Protostore\Emaillog\Emaillog;
use Protostore\Orderlog\Orderlog;

class plgProtostoresystemProtostore_emailer extends JPlugin
{

    private $db;
    private $order;
    private $customer;

    public function onSendProtoStoreEmail($emailtype, $orderId)
    {

        $language = Factory::getLanguage();
        $language->load('com_protostore', JPATH_ADMINISTRATOR);

        $this->order = new Order($orderId);
        $this->customer = new Customer($this->order->customerid);

        $this->db = Factory::getDbo();

        $emails = $this->_getEmails($emailtype);

        if ($emails) {

            foreach ($emails as $email) {


                $mailer = Factory::getMailer();

                $config = Factory::getConfig();
                $sender = array(
                    $config->get('mailfrom'),
                    $config->get('fromname')
                );

                $mailer->setSender($sender);

                if ($email->to) {
                    $emailto = explode(',', $email->to);

                } else {
                    $emailto = $this->order->billing_address->email;
                    $emailto = array($emailto);
                }

                $mailer->addRecipient($emailto);

                $text = $this->processReplacements($email->body);


                $params = ComponentHelper::getParams('com_protostore');

                $displayData = array('order' => $this->order, 'body' => $text, 'config' => $params);

                $body = LayoutHelper::render($this->params->get('layout', 'default'), $displayData, JPATH_PLUGINS . '/protostoresystem/protostore_emailer/tmpl');

                $mailer->setSubject($this->processReplacements($email->subject));
                $mailer->isHtml(true);
                $mailer->setBody($body);

                $send = $mailer->Send();

                if ($send) {
                    Emaillog::log($emailto, $emailtype, $this->order->customerid, $orderId);

                    $name = (Factory::getUser()->name ? Factory::getUser()->name : 'Joomla');
                    new Orderlog(false, $orderId, Text::sprintf('COM_PROTOSTORE_ORDER_EMAIL_SENT_LOG', ucfirst($emailtype), implode(" and ", $emailto), $name));
                }
            }
        }


    }


    private function _getEmails($emailType)
    {

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_email'));
        $query->where($this->db->quoteName('emailtype') . ' = ' . $this->db->quote($emailType), 'AND');
        $query->where($this->db->quoteName('published') . ' = 1');

        $this->db->setQuery($query);

        return $this->db->loadObjectList();

    }


    private function processReplacements($text)
    {

        $config = Factory::getConfig();

        // global
        $text = str_replace('{site_name}', $config->get('fromname'), $text);

        // order
        $text = str_replace('{order_number}', $this->order->number, $text);
        $text = str_replace('{order_grand_total}', Currency::formatNumberWithCurrency($this->order->total, $this->order->currency, '', true), $text);
        $text = str_replace('{order_subtotal}', Currency::formatNumberWithCurrency($this->order->total, $this->order->currency, '', true), $text);
//		if ($this->order->shipping_total)
//		{
//			$text = str_replace('{order_shipping_total}', Currency::formatNumberWithCurrency($this->order->shipping_total, $this->order->currency, '', true), $text);
//		}
        $text = str_replace('{order_payment_method}', $this->order->payment_method, $text);


        // tracking
        $text = str_replace('{tracking_code}', $this->order->trackingcode, $text);
        $text = str_replace('{tracking_url}', $this->order->trackingcodeurl, $text);


        // customer
        if ($this->customer->name) {
            $text = str_replace('{customer_name}', $this->customer->name, $text);
        } else {
            $text = str_replace('{customer_name}', $this->order->shipping_address->name, $text);
        }
        if ($this->customer->email) {
            $text = str_replace('{customer_email}', $this->customer->email, $text);
        } else {
            $text = str_replace('{customer_email}', $this->order->shipping_address->email, $text);
        }

        if ($this->customer->total_orders) {
            $text = str_replace('{customer_order_count}', $this->customer->total_orders, $text);
        }


        // shipping
        $text = str_replace('{shipping_name}', $this->order->shipping_address->name, $text);
        $text = str_replace('{shipping_address1}', $this->order->shipping_address->address1, $text);
        $text = str_replace('{shipping_address2}', $this->order->shipping_address->address2, $text);
        $text = str_replace('{shipping_address3}', $this->order->shipping_address->address3, $text);
        $text = str_replace('{shipping_town}', $this->order->shipping_address->town, $text);
        $text = str_replace('{shipping_state}', $this->order->shipping_address->zone_name, $text);
        $text = str_replace('{shipping_country}', $this->order->shipping_address->country_name, $text);
        $text = str_replace('{shipping_postcode}', $this->order->shipping_address->postcode, $text);
        $text = str_replace('{shipping_email}', $this->order->shipping_address->email, $text);
        $text = str_replace('{shipping_postcode}', $this->order->shipping_address->postcode, $text);
        $text = str_replace('{shipping_mobile}', $this->order->shipping_address->mobile_phone, $text);
        $text = str_replace('{shipping_phone}', $this->order->shipping_address->phone, $text);

        // billing
        $text = str_replace('{billing_name}', $this->order->billing_address->name, $text);
        $text = str_replace('{billing_address1}', $this->order->billing_address->address1, $text);
        $text = str_replace('{billing_address2}', $this->order->billing_address->address2, $text);
        $text = str_replace('{billing_address3}', $this->order->billing_address->address3, $text);
        $text = str_replace('{billing_town}', $this->order->billing_address->town, $text);
        $text = str_replace('{billing_state}', $this->order->billing_address->zone_name, $text);
        $text = str_replace('{billing_country}', $this->order->billing_address->country_name, $text);
        $text = str_replace('{billing_postcode}', $this->order->billing_address->postcode, $text);
        $text = str_replace('{billing_email}', $this->order->billing_address->email, $text);
        $text = str_replace('{billing_postcode}', $this->order->billing_address->postcode, $text);
        $text = str_replace('{billing_mobile}', $this->order->billing_address->mobile_phone, $text);
        $text = str_replace('{billing_phone}', $this->order->billing_address->phone, $text);


        return $text;

    }


}
