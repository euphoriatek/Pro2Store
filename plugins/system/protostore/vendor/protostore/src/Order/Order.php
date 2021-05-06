<?php

/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Order;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

use Protostore\Address\Address;
use Protostore\Currency\Currency;

use Brick\Money\Money;

use stdClass;
use Exception;

class Order
{

    public $id;
    public $customerid;
    public $customername;
    public $customeremail;
    public $ordered_date;
    public $number;
    public $paid;
    public $status;
    public $status_translated;
    public $total;
    public $total_as_int;
    public $total_as_string;
    public $total_as_float;
    public $subtotal;
    public $subtotal_as_string;
    public $shipping_total;
    public $shipping_total_as_string;
    public $tax_total;
    public $tax_total_as_string;
    public $discount_code;
    public $discount_total;
    public $discount_total_as_string;
    public $currency;
    public $payment_method;
    public $billing_address_id;
    public $shipping_address_id;
    public $shipping_address;
    public $billing_address;
    public $ordered_products;
    public $product_count;
    public $customer_notes;
    public $guestPin;
    public $vendorToken;
    public $billingEmail;
    public $trackingcode;
    public $trackingcodeurl;


	public function __construct($data)
	{

		if ($data)
		{
			$this->hydrate($data);
			$this->init($data);
		}

	}

	private function hydrate($data)
	{
		foreach ($data as $key => $value)
		{

			if (property_exists($this, $key))
			{
				$this->{$key} = $value;
			}

		}
	}


    private function init($data)
    {

        if ($data) {

            $this->status_translated = $this->getTranslatedStatus();

            $this->total_as_string = $this->getTotalAsString();
            $this->total_as_int = $this->getOrderTotalAsInt();
            $this->total_as_float = $this->getOrderTotalAsFloat();
            $this->subtotal_as_string = $this->getSubTotalAsString();
            $this->shipping_total_as_string = $this->getShippingTotalAsString();
            $this->tax_total_as_string = $this->getTaxTotalAsString();
            $this->ordered_products = $this->getOrderedProducts();
            $this->product_count = count($this->ordered_products);
            $this->shipping_address = new Address($this->shipping_address_id);
            $this->billing_address = new Address($this->billing_address_id);
            $this->customername = $this->setCustomer()->name;
            $this->customeremail = $this->setCustomer()->email;
            $this->discount_total_as_string = $this->getDiscountTotalAsString();
            $this->billingEmail = $this->getBillingEmail();
            $this->trackingcode = $this->getTrackingCode();
            $this->trackingcodeurl = $this->getTrackingCodeURL();
        }


    }

    public function getOrderTotalAsInt()
    {
        return $this->total;
    }


    public function getOrderTotalAsFloat()
    {

        $total = Money::ofMinor($this->total, $this->currency);

        return $total->getAmount();


    }

    public function getTotalAsString()
    {
        return Currency::formatNumberWithCurrency($this->total, $this->currency);
    }

    public function getSubTotalAsString()
    {
        return Currency::formatNumberWithCurrency($this->subtotal, $this->currency);
    }

    public function getShippingTotalAsString()
    {
        return Currency::formatNumberWithCurrency($this->shipping_total, $this->currency);
    }

    public function getTaxTotalAsString()
    {
        return Currency::formatNumberWithCurrency($this->tax_total, $this->currency);
    }

    public function getDiscountTotalAsString()
    {
        return Currency::formatNumberWithCurrency($this->discount_total, $this->currency);

    }

    public function setCustomer()
    {

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_customer'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($this->customerid));

        $this->db->setQuery($query);

        $result = $this->db->loadObject();

        if ($result) {
            return $result;
        } else {
            $object = new stdClass();
            $object->name = $this->billing_address->name;
            $object->email = $this->billing_address->email;

            return $object;

        }

    }

    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getCurrency()
    {
        return $this->currency;
    }


    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }


    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getNumber()
    {
        return $this->number;
    }


    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getOrderedDate()
    {
        return $this->ordered_date;
    }


    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getTotal()
    {
        return $this->total;
    }


    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getTranslatedStatus()
    {
        $language = Factory::getLanguage();
        $language->load('com_protostore', JPATH_ADMINISTRATOR);


        switch ($this->status) {
            case 'P':
                return Text::_('COM_PROTOSTORE_ORDER_PENDING');
            case 'C':
                return Text::_('COM_PROTOSTORE_ORDER_CONFIRMED');
            case 'X':
                return Text::_('COM_PROTOSTORE_ORDER_CANCELLED');
            case 'R':
                return Text::_('COM_PROTOSTORE_ORDER_REFUNDED');
            case 'S':
                return Text::_('COM_PROTOSTORE_ORDER_SHIPPED');
            case 'F':
                return Text::_('COM_PROTOSTORE_ORDER_COMPLETED');
        }

    }


    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * @return mixed
     *
     * @since 1.0
     *
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     * @since 1.0
     */
    public function getShippingAddressId()
    {
        return $this->shipping_address_id;
    }

    /**
     * @return mixed
     *
     * @since 1.0
     */
    public function getBillingAddressId()
    {
        return $this->billing_address_id;
    }


    /**
     * function getOrderedProducts()
     *
     * get the products associated with this order
     *
     *
     * @return array|mixed
     *
     * @since 1.0
     */

    public function getOrderedProducts()
    {

        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('*');
        $query->from($db->quoteName('#__protostore_order_products'));
        $query->where($db->quoteName('order_id') . ' = ' . $db->quote($this->id));

        $db->setQuery($query);

        $orderedProductsArray = array();

        $orderedProducts = $db->loadObjectList();

        if ($orderedProducts) {


            foreach ($orderedProducts as $orderedProduct) {
                $selectedOptions = json_decode($orderedProduct->item_options);

                foreach ($selectedOptions as $selectedOption) {
                    $orderedProduct->the_item_options[$selectedOption->optiontypename] = $selectedOption->optionname;
                }

                $orderedProduct->price_at_sale_translated = $orderedProduct->price_at_sale;

                if ($orderedProduct->amount == '') {
                    $orderedProduct->amount = 1;
                }

                $orderedProductsArray[] = $orderedProduct;

            }
        }

        return $orderedProductsArray;


    }


    private function getBillingEmail()
    {


        $query = $this->db->getQuery(true);

        $query->select('email');
        $query->from($this->db->quoteName('#__protostore_customer_address'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($this->billing_address_id));

        $this->db->setQuery($query);

        return $this->db->loadResult();

    }


    private function getTrackingCode()
    {
        $query = $this->db->getQuery(true);

        $query->select('tracking_code');
        $query->from($this->db->quoteName('#__protostore_order_tracking'));
        $query->where($this->db->quoteName('order_id') . ' = ' . $this->db->quote($this->id));

        $this->db->setQuery($query);

        return $this->db->loadResult();
    }

    private function getTrackingCodeURL()
    {
        $query = $this->db->getQuery(true);

        $query->select('tracking_link');
        $query->from($this->db->quoteName('#__protostore_order_tracking'));
        $query->where($this->db->quoteName('order_id') . ' = ' . $this->db->quote($this->id));

        $this->db->setQuery($query);

        return $this->db->loadResult();
    }


    public function setTrackingCodeData($trackingCode, $trackingCodeURL)
    {
        $this->trackingcode = $trackingCode;
        $this->trackingcodeurl = $trackingCodeURL;

    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function saveTrackingCode()
    {

        if ($this->getTrackingCode()) {
            $object = new stdClass();
            $object->order_id = $this->id;
            $object->tracking_code = $this->trackingcode;
            $object->tracking_link = $this->trackingcodeurl;

            $this->db->updateObject('#__protostore_order_tracking', $object, 'order_id');

        } else {
            $object = new stdClass();
            $object->id = 0;
            $object->order_id = $this->id;
            $object->tracking_code = $this->trackingcode;
            $object->tracking_link = $this->trackingcodeurl;

            $this->db->insertObject('#__protostore_order_tracking', $object);
        }
    }


    /**
     *
     * function updateStatus()
     *
     * Allows the order status to be updated from any part of the Pro2Store system
     *
     * @param $statusCode
     *
     * @since 1.0
     */

    public function updateStatus($statusCode)
    {
        $this->status = $statusCode;
        $this->save();
    }


    /**
     *
     * function updatePaid()
     *
     * Allows the paid status to be updated from any part of the Pro2Store system
     *
     * @param $statusCode
     *
     * @since 1.0
     */

    public function updatePaid($pay)
    {
        $this->paid = (int)$pay;
        $this->save();
    }


    /**
     *
     * function save()
     *
     *
     * @since 1.0
     *
     */


    public function save()
    {

        $object = new stdClass();
        $object->id = $this->id;
        $object->customer = $this->customerid;
        $object->order_paid = $this->paid;
        $object->order_status = $this->status;
        $object->order_total = $this->total;
        $object->billing_address_id = $this->billing_address_id;
        $object->shipping_address_id = $this->shipping_address_id;
        $object->vendor_token = $this->vendorToken;


        $result = $this->db->updateObject('#__protostore_order', $object, 'id');
    }

    public function getOrderIdViaNumber($orderNumber)
    {
        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('id');
        $query->from($db->quoteName('#__protostore_order'));
        $query->where($db->quoteName('order_number') . ' = ' . $db->quote($orderNumber));

        $db->setQuery($query);

        $result = $db->loadResult();

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }


    public static function checkIfOrderExists($column, $value)
    {
        $db = Factory::getDbo();

        $query = $db->getQuery(true);

        $query->select('*');
        $query->from($db->quoteName('#__protostore_order'));
        $query->where($db->quoteName($column) . ' = ' . $db->quote($value));

        $db->setQuery($query);

        $result = $db->loadObject();

        if ($result) {
            return $result->id;
        } else {
            return false;
        }


    }

}
