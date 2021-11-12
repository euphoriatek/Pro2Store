<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// no direct access

namespace Protostore\Order;

defined('_JEXEC') or die('Restricted access');

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\User\User;
use Joomla\Input\Input;
use Joomla\CMS\Date\Date;

use Protostore\Address\Address;
use Protostore\Address\AddressFactory;
use Protostore\Cart\CartFactory;
use Protostore\Cart\CartItem;
use Protostore\Checkoutnote\CheckoutnoteFactory;
use Protostore\Config\ConfigFactory;
use Protostore\Coupon\CouponFactory;
use Protostore\Currency\CurrencyFactory;
use Protostore\Customer\CustomerFactory;
use Protostore\Emaillog\EmaillogFactory;
use Protostore\Product\ProductFactory;
use Protostore\Shipping\ShippingFactory;
use Protostore\Tax\TaxFactory;
use Protostore\Total\TotalFactory;
use Protostore\Utilities\Utilities;

use Brick\Money\Exception\UnknownCurrencyException;

use stdClass;


class OrderFactory
{


	/**
	 * @param   int  $id
	 *
	 * @return Order
	 *
	 * @since 2.0
	 */

	public static function get(int $id): ?Order
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_order'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();
		if ($result && is_object($result))
		{
			return new Order($result);
		}

		return null;
	}


	/**
	 * @param   int          $limit
	 *
	 * @param   int          $offset
	 * @param   string|null  $searchTerm
	 * @param   int|null     $customerId
	 * @param   string|null  $status
	 * @param   string|null  $currency
	 * @param   string|null  $dateFrom
	 * @param   string|null  $dateTo
	 *
	 * @return array
	 * @since 2.0
	 */

	public static function getList(int $limit = 0, int $offset = 0, string $searchTerm = null, int $customerId = null, string $status = null, string $currency = null, string $dateFrom = null, string $dateTo = null, $orderBy = 'order_date', $orderDir = 'DESC'): ?array
	{


		$orders = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_order'));

		if ($searchTerm)
		{
			$query->where($db->quoteName('order_number') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));
		}

		if ($currency)
		{
			$query->where($db->quoteName('currency') . ' = ' . $db->quote($currency));
		}

		if ($status)
		{

			$query->where($db->quoteName('order_status') . ' = ' . $db->quote($status));


		}

		if ($customerId)
		{
			$query->where($db->quoteName('customer_id') . ' = ' . $db->quote($customerId));
		}
		if ($dateTo)
		{
			$query->where($db->quoteName('order_date') . ' <= ' . $db->quote($dateTo . ' 23:59:59'));
		}

		if ($dateFrom)
		{
			$query->where($db->quoteName('order_date') . ' >= ' . $db->quote($dateFrom . ' 00:00:00'));
		}

		$query->order($orderBy . ' ' . $orderDir);

		$db->setQuery($query, $offset, $limit);

		$results = $db->loadObjectList();

		if ($results)
		{
			foreach ($results as $result)
			{
				$orders[] = new Order($result);

			}

			return $orders;
		}


		return null;
	}

	/**
	 * @param $order_id
	 *
	 * @return array
	 *
	 * @since 2.0
	 */


	public static function getOrderedProducts($order_id): ?array
	{

		$products = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_order_products'));
		$query->where($db->quoteName('order_id') . ' = ' . $db->quote($order_id));

		$db->setQuery($query);

		$results = $db->loadObjectList();

		if ($results)
		{
			foreach ($results as $result)
			{
				$products[] = new OrderedProduct($result);

			}

			return $products;
		}


		return null;


	}

	/**
	 *
	 * careful now....
	 *
	 * Ok... we need to grab the order then compare the order date with the expiry date set in the component config.
	 *
	 *
	 * @param   string  $hash
	 *
	 * @return Order|null
	 *
	 * @throws Exception
	 * @since 2.0
	 */


	public static function getOrderByHash(string $hash): ?Order
	{

		// get the component config
		$params = ConfigFactory::get();

		// grab the expiry time in minutes (int)
		$expiry = $params->get('hash_expiry', 1);


		// get the order id via the hash string
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('id');
		$query->from($db->quoteName('#__protostore_order'));
		$query->where($db->quoteName('hash') . ' = ' . $db->quote($hash));

		$db->setQuery($query);

		$id = $db->loadResult();

		// if there is an id...
		if ($id)
		{

			// go get the full order
			$order = self::get($id);

			// get a Date() object of the order date
			$orderDate = new Date($order->order_date);

			// get a Date() object of the expiry... so now minus however amount of minutes.
			$expiryDate = new Date('now +1 hour -' . $expiry . ' minutes');

			$expiryDate = $expiryDate->toSQL();
			// if the order date is more than the expiry date... then we're good to go.
			if ($orderDate > $expiryDate)
			{
				return $order;
			}

		}

		return null;

	}

	/**
	 * @param   int     $order_id
	 * @param   int     $limit
	 * @param   int     $offset
	 * @param   string  $orderBy
	 * @param   string  $orderDir
	 *
	 * @return array
	 *
	 * @since 2.0
	 */


	public static function getOrderLogs(int $order_id, int $limit = 0, int $offset = 0, string $orderBy = 'created', string $orderDir = 'DESC'): ?array
	{
		$orderLogs = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_order_log'));

		$query->where($db->quoteName('order_id') . ' = ' . $db->quote($order_id));

		$query->order($orderBy . ' ' . $orderDir);

		$db->setQuery($query, $offset, $limit);

		$results = $db->loadObjectList();

		if ($results)
		{
			foreach ($results as $result)
			{
				$orderLogs[] = new Orderlog($result);

			}

			return $orderLogs;
		}


		return null;

	}

	/**
	 * @param $order_id
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	public static function getEmailLogs($order_id): ?array
	{
		return EmaillogFactory::getList(0, 0, '', null, $order_id);

	}


	/**
	 * Function to save a new Order Log
	 *
	 * @param   int     $order_id
	 * @param   string  $note
	 *
	 *
	 * @since 2.0
	 */

	public static function log(int $order_id, string $note)
	{

		$object             = new stdClass();
		$object->id         = 0;
		$object->order_id   = $order_id;
		$object->note       = $note;
		$object->created_by = Factory::getUser()->id;
		$object->created    = Utilities::getDate();


		Factory::getDbo()->insertObject('#__protostore_order_log', $object);

	}

	/**
	 * @param   int  $address_id
	 *
	 * @return Address
	 *
	 * @since 2.0
	 */


	public static function getAddress(int $address_id): ?Address
	{

		return AddressFactory::get($address_id);

	}

	/**
	 * @param   int     $order_id
	 * @param   int     $limit
	 * @param   int     $offset
	 * @param   string  $orderBy
	 * @param   string  $orderDir
	 *
	 * @return array
	 *
	 * @since 2.0
	 */


	public static function getOrderNotes(int $order_id, int $limit = 0, int $offset = 0, string $orderBy = 'created', string $orderDir = 'ASC'): ?array
	{
		$orderNotes = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_order_notes'));
		$query->where($db->quoteName('order_id') . ' = ' . $db->quote($order_id));

		$query->order($orderBy . ' ' . $orderDir);

		$db->setQuery($query, $offset, $limit);

		$results = $db->loadObjectList();

		if ($results)
		{
			foreach ($results as $result)
			{
				$orderNotes[] = new Ordernote($result);

			}

			return $orderNotes;
		}


		return null;
	}

	/**
	 * @param $order_id
	 * @param $note
	 *
	 *
	 * @since 2.0
	 */

	public static function note($order_id, $note)
	{

		$object             = new stdClass();
		$object->id         = 0;
		$object->order_id   = $order_id;
		$object->note       = $note;
		$object->created_by = Factory::getUser()->id;
		$object->created    = Utilities::getDate();


		Factory::getDbo()->insertObject('#__protostore_order_notes', $object);

	}

	/**
	 * @param $note_id
	 * @param $note
	 *
	 *
	 * @since 2.0
	 */

	public static function updateNote($note_id, $note)
	{
		$object       = new stdClass();
		$object->id   = $note_id;
		$object->note = $note;


		Factory::getDbo()->updateObject('#__protostore_order_notes', $object, 'id');


	}


	/**
	 * @param   int          $int
	 * @param   string|null  $currency
	 *
	 * @return string
	 *
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */

	public static function intToFormat(int $int, string $currency = null): string
	{

		return CurrencyFactory::formatNumberWithCurrency($int, $currency);

	}

	/**
	 * @param   int          $int
	 * @param   string|null  $currency
	 *
	 * @return string
	 *
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */

	public static function intToFloat(int $int, string $currency = null): string
	{

		return CurrencyFactory::toFloat($int, $currency);


	}

	/**
	 *
	 * Gets the currency for the order... we *CAN'T* instantiate the Order class here with self::get() as
	 * we need this function for the OrderedProduct class...
	 * calling the Order would cause an infinite loop
	 *
	 * @param $id
	 *
	 * @return string
	 *
	 * @since 2.0
	 */


	public static function getOrderCurrency($id): string
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('currency');
		$query->from($db->quoteName('#__protostore_order'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		return $db->loadResult();


	}

	/**
	 * @param $user_id
	 *
	 * @return User
	 *
	 * @since 2.0
	 */


	public static function getUser($user_id): User
	{

		return Factory::getUser($user_id);

	}

	/**
	 * @param $customer_id
	 *
	 * @return mixed|null
	 *
	 * @since 2.0
	 */


	public static function getCustomer($customer_id)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select(array('name', 'email'));
		$query->from($db->quoteName('#__protostore_customer'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($customer_id));

		$db->setQuery($query);

		return $db->loadObject();

	}

	/**
	 * @param   string  $name
	 *
	 * @return string
	 *
	 * @since 2.0
	 */

	public static function getAvatarAbb(string $name): string
	{

		$words   = preg_split("/[\s,_-]+/", $name);
		$acronym = "";

		foreach ($words as $w)
		{
			$acronym .= $w[0];
		}

		return $acronym;
	}

	/**
	 * @param   string  $status
	 *
	 * @return string
	 *
	 * @since 2.0
	 */


	public static function getStatusFormatted(string $status): string
	{

		$language = Factory::getLanguage();
		$language->load('com_protostore');

		switch ($status)
		{
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
			case 'D':
				return Text::_('COM_PROTOSTORE_ORDER_DENIED');

		}

		return '';

	}

	/**
	 * @param   int  $order_id
	 *
	 * @return mixed|null
	 *
	 * @since 2.0
	 */


	public static function getTracking(int $order_id)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_order_tracking'));
		$query->where($db->quoteName('order_id') . ' = ' . $db->quote($order_id));

		$db->setQuery($query);

		return $db->loadObject();

	}

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */


	public static function saveNewNote(Input $data): bool
	{

		$db = Factory::getDbo();

		$object             = new stdClass();
		$object->id         = 0;
		$object->order_id   = $data->getInt('orderid');
		$object->note       = $data->getString('text');
		$object->created_by = Factory::getUser()->id;
		$object->created    = Utilities::getDate();

		$result = $db->insertObject('#__protostore_order_notes', $object);

		if ($result)
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	/**
	 * @param   int  $order_id
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 2.0
	 */


	public static function togglePaid(int $order_id): bool
	{

		$order = self::get($order_id);

		$order->order_paid = ($order->order_paid === 0 ? 1 : 0);

//		return $order->order_paid;

		return self::update($order);


	}

	/**
	 * @param   string  $status
	 * @param   int     $order_id
	 * @param   bool    $sendEmail
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 2.0
	 */


	public static function updateStatus(string $status, int $order_id, bool $sendEmail = false): bool
	{

		$order = self::get($order_id);

		$order->order_status = $status;

		$update = self::update($order);

		if ($update)
		{

			if ($sendEmail)
			{
				// send email
				PluginHelper::importPlugin('protostoresystem');
				Factory::getApplication()->triggerEvent('onSendProtoStoreEmail', array(Utilities::getOrderStatusFromCharacterCode($status), $order_id));
			}

			return true;
		}

		return false;

	}


	/**
	 * @param   string  $tracking_code
	 * @param   string  $tracking_link
	 * @param   string  $tracking_provider
	 * @param   int     $order_id
	 * @param   bool    $sendEmail
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 2.0
	 */

	public static function updateTracking(string $tracking_code, string $tracking_link, string $tracking_provider, int $order_id, bool $sendEmail): bool
	{
		$order = self::get($order_id);

		$order->order_status      = 'S';
		$order->tracking_code     = $tracking_code;
		$order->tracking_link     = $tracking_link;
		$order->tracking_provider = $tracking_provider;


		$update = self::update($order);

		if ($update)
		{

			// now update tracking table

			if (self::saveTracking($order))
			{
				if ($sendEmail)
				{
					// send email
					PluginHelper::importPlugin('protostoresystem');
					Factory::getApplication()->triggerEvent('onSendProtoStoreEmail', array(Utilities::getOrderStatusFromCharacterCode($order->order_status), $order_id));
				}

				return true;
			}

			return false;

		}

		return false;

	}

	/**
	 * @param   Order  $order
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 2.0
	 */


	public static function update(Order $order): bool
	{

		$orderToSave = new stdClass();

		// iterate through update-able fields:
		$orderToSave->id                  = $order->id;
		$orderToSave->order_paid          = $order->order_paid;
		$orderToSave->order_status        = $order->order_status;
		$orderToSave->billing_address_id  = $order->billing_address_id;
		$orderToSave->shipping_address_id = $order->shipping_address_id;

		// log
		self::log($order->id, Text::sprintf('COM_PROTOSTORE_ORDER_UPDATE_LOG', self::getStatusFormatted($order->order_status), Factory::getUser()->name));

		//event trigger
		PluginHelper::importPlugin('protostoresystem');
		Factory::getApplication()->triggerEvent('onOrderUpdated', array($order->id));

		return Factory::getDbo()->updateObject('#__protostore_order', $orderToSave, 'id');


	}

	/**
	 * @param   Order  $order
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */

	public static function saveTracking(Order $order): bool
	{

		$db = Factory::getDbo();

		//check if already in
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_order_tracking'));
		$query->where($db->quoteName('order_id') . ' = ' . $db->quote($order->id));
		$db->setQuery($query);

		$result = $db->loadObject();

		$trackingToSave = new stdClass();

		if ($result)
		{
			//update

			$trackingToSave->order_id          = $order->id;
			$trackingToSave->tracking_code     = $order->tracking_code;
			$trackingToSave->tracking_provider = $order->tracking_provider;
			$trackingToSave->tracking_link     = $order->tracking_link;

			return $db->updateObject('#__protostore_order_tracking', $trackingToSave, 'order_id');

		}
		else
		{
			//insert

			$trackingToSave->id                = 0;
			$trackingToSave->order_id          = $order->id;
			$trackingToSave->tracking_code     = $order->tracking_code;
			$trackingToSave->tracking_provider = $order->tracking_provider;
			$trackingToSave->tracking_link     = $order->tracking_link;
			$trackingToSave->created           = Utilities::getDate();

			return $db->insertObject('#__protostore_order_tracking', $trackingToSave);
		}


	}

	/**
	 * @param   string  $paymentMethod
	 * @param   string  $vendorToken
	 *
	 * @return Order
	 *
	 * @throws Exception
	 * @since 2.0
	 *
	 *
	 */


	public static function createOrderFromCart(string $paymentMethod, string $vendorToken = NULL): ?Order
	{

		// init vars
		$db        = Factory::getDbo();
		$date      = Utilities::getDate();
		$cookie_id = Utilities::getCookieID();
		$customer  = CustomerFactory::get();
		$cart      = CartFactory::get();
		$currency  = CurrencyFactory::getCurrent();

		// Build Order Object
		$object     = new stdClass();
		$object->id = 0;

		if (!$customer)
		{
			//Todo - "guest checkout" - for the minute lets generate a uniqid() to identify the order for guest customers
			$object->guest_pin = uniqid();
		}

		$object->hash        = bin2hex(random_bytes(18));
		$object->customer_id = $customer->id;
		$object->order_date  = $date;
		// todo - look into a better way of generating order numbers. Give admins a choice on how they are styled
		$object->order_number   = self::_generateOrderId(rand(10000, 99999));
		$object->order_paid     = 0;
		$object->order_status   = 'P';
		$object->order_total    = $cart->totalInt;
		$object->order_subtotal = $cart->subtotalInt;

		$object->shipping_total      = $cart->totalShipping;
		$object->tax_total           = $cart->taxInt;
		$object->currency            = $currency->iso;
		$object->payment_method      = $paymentMethod;
		$object->vendor_token        = $vendorToken;
		$object->billing_address_id  = $cart->billing_address_id;
		$object->shipping_address_id = $cart->shipping_address_id;
		$object->published           = 1;

		if (CouponFactory::isCouponApplied())
		{
			$coupon                = CouponFactory::getCurrentAppliedCoupon();
			$object->discount_code = $coupon->coupon_code;
		}

		$object->discount_total = $cart->totalDiscountInt;

		if ($currentNote = CheckoutnoteFactory::getCurrentNote())
		{
			$object->customer_notes = $currentNote->note;
		}
		else
		{
			$object->customer_notes = '';
		}


		// insert the new Order object and retrieve the Order ID via insertid method
		$result = $db->insertObject('#__protostore_order', $object);

		if (!$result)
		{
			return null;
		}

		$order_id = $db->insertid();


		// now insert the products

		/** @var CartItem $cartItem */
		foreach ($cart->cartItems as $cartItem)
		{

			$object                = new stdClass();
			$object->id            = 0;
			$object->order_id      = $order_id;
			$object->j_item        = $cartItem->joomla_item_id;
			$object->j_item_cat    = $cartItem->product->joomlaItem->catid;
			$object->j_item_name   = $cartItem->product->joomlaItem->title;
			$object->item_options  = json_encode($cartItem->selected_options);
			$object->variant_id    = $cartItem->variant_id;
			$object->price_at_sale = $cartItem->bought_at_price;
			$object->amount        = $cartItem->amount;

			$db->insertObject('#__protostore_order_products', $object);

			if ($cartItem->manage_stock_enabled == 1)
			{

				$currentStock  = ProductFactory::getCurrentStock($cartItem->product->joomla_item_id);
				$newStockLevel = ($currentStock - $cartItem->amount);

				if ($newStockLevel <= 0)
				{
					$newStockLevel = 0;
				}

				$stockUpdate        = new stdClass();
				$stockUpdate->id    = $cartItem->product->id;
				$stockUpdate->stock = $newStockLevel;

				$db->updateObject('#__protostore_product', $stockUpdate, 'id');
			}

		}


		// clear the items from the cart
		CartFactory::clearItems($cart);

		// clear the coupons
		CartFactory::clearCoupons($cart);

		// get the plugin functions
		PluginHelper::importPlugin('protostoresystem');

		try
		{
			Factory::getApplication()->triggerEvent('onSendProtoStoreEmail', array('pending', $order_id));
		}
		catch (Exception $e)
		{

		}

		// insert a log of this order
		self::log($order_id, Text::_('COM_PROTOSTORE_ORDER_IS_CREATED_LOG'));


		// fire the event
		PluginHelper::importPlugin('protostoresystem');

		try
		{
			Factory::getApplication()->triggerEvent('onOrderCreated', array($order_id));
		}
		catch (Exception $e)
		{

		}


		return self::get($order_id);

	}

	/**
	 * @param $seed
	 *
	 * @return string
	 *
	 * @since 1.0
	 */


	private static function _generateOrderId($seed): string
	{

		$charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$base    = strlen($charset);
		$result  = '';
		$len     = 5;
		$now     = explode(' ', microtime())[1];
		while ($now >= $base)
		{
			$i      = $now % $base;
			$result = $charset[$i] . $result;
			$now    /= $base;
		}
		$rand = substr($result, -$len);

		return strtoupper($rand . $seed);
	}

	/**
	 *
	 * @return array
	 *
	 * @since 2.0
	 */


	public static function getStatuses(): array
	{

		$statuses = array();

		$statuses[0]['id']    = 'P';
		$statuses[0]['title'] = Text::_('COM_PROTOSTORE_ORDER_PENDING');

		$statuses[1]['id']    = 'C';
		$statuses[1]['title'] = Text::_('COM_PROTOSTORE_ORDER_CONFIRMED');

		$statuses[2]['id']    = 'X';
		$statuses[2]['title'] = Text::_('COM_PROTOSTORE_ORDER_CANCELLED');

		$statuses[3]['id']    = 'R';
		$statuses[3]['title'] = Text::_('COM_PROTOSTORE_ORDER_REFUNDED');

		$statuses[4]['id']    = 'S';
		$statuses[4]['title'] = Text::_('COM_PROTOSTORE_ORDER_SHIPPED');

		$statuses[5]['id']    = 'F';
		$statuses[5]['title'] = Text::_('COM_PROTOSTORE_ORDER_COMPLETED');

		$statuses[6]['id']    = 'D';
		$statuses[6]['title'] = Text::_('COM_PROTOSTORE_ORDER_DENIED');


		return $statuses;
	}


	/**
	 * @param   string  $payment_method
	 *
	 * @return string
	 *
	 * @since 2.0
	 */

	public static function getPaymentMethodIcon(string $payment_method): string
	{

		$pluginName = str_replace(' ', '', strtolower($payment_method));

		return \Joomla\CMS\Uri\Uri::root() . "plugins/system/protostore_" . $pluginName . "/modules/" . $pluginName . "/elements/protostore_" . $pluginName . "/images/protostore_" . $pluginName . ".svg";
	}


}
