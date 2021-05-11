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
use Protostore\Price\Price;
use Protostore\Product\ProductFactory;
use Protostore\Productoption\ProductoptionFactory;
use Protostore\Shipping\Shipping;
use Protostore\Utilities\Utilities;

use stdClass;

class CartFactory
{


	/**
	 *
	 * gets the current cart... last line initiates a new cart if none is currently found. This is self referencing.
	 *
	 * @return Cart
	 *
	 * @since version
	 */

	public static function get()
	{

		$db   = Factory::getDbo();
		$user = Factory::getUser();

		// now check if there is already a cart for this cookie
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_cart'));

		if ($user->guest)
		{
			$query->where($db->quoteName('cookie_id') . ' = ' . $db->quote(Utilities::getCookieID()));
		}
		else
		{
			$query->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));
		}

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			return new Cart($result);
		}

		//so if there is no cart matching the user id or cookie, run init...
		// remember... init reference this function so it will re-run until a cart is created and found.

		return self::init();

	}

	/**
	 *
	 * creates a new cart and then runs the get function.
	 *
	 * @return Cart|void
	 *
	 * @since version
	 */

	public static function init()
	{

		$db       = Factory::getDbo();
		$user     = Factory::getUser();
		$shipping = Shipping::getPrioritisedShipping(); // todo - move to ShippingFactory

		$object                = new stdClass();
		$object->id            = 0;
		$object->user_id       = $user->id;
		$object->shipping_type = ($shipping->name ? $shipping->name : 'defaultshipping');
		if ($user->guest)
		{
			$object->cookie_id = Utilities::getCookieID();
		}
		else
		{
			$object->cookie_id = 0;
		}

		$db->insertObject('#__protostore_cart', $object);

		return self::get();

	}


	/**
	 * @param $id
	 *
	 * @return false|CartItem
	 *
	 * @since 1.5
	 */

	public static function getCartitem($id)
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_carts'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			return new CartItem($result);
		}

		return false;


	}

	/**
	 *
	 * gets the cart items for the cart id supplied
	 *
	 * @param $carId
	 *
	 * @return array
	 *
	 * @since 1.5
	 */


	public static function getCartItems($carId)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_carts'));

		$query->where($db->quoteName('cart_id') . ' = ' . $db->quote($carId));


		$db->setQuery($query);

		$items = $db->loadObjectList();

		$cartItems = array();

		foreach ($items as $item)
		{
			$cartItems[] = new Cartitem($item);
		}

		return $cartItems;

	}

	/**
	 * @param $cartItems
	 *
	 * @return int
	 *
	 * @since 1.5
	 */

	public static function getCount($cartItems): int
	{

		$count = 0;

		foreach ($cartItems as $item)
		{
			$count += $item->amount;
		}

		return $count;

	}

	/**
	 * @param $item_options
	 *
	 * @return array
	 *
	 * @since 1.5
	 */


	public static function getSelectedOptions($item_options)
	{
		$selectedOptions = array();
		$itemOptions     = json_decode($item_options);

		foreach ($itemOptions as $option)
		{
			foreach ($option as $selectedOption)
			{
				$selectedOptions[] = ProductoptionFactory::get($selectedOption->optionid);
			}
		}


		return $selectedOptions;
	}


	public static function change($change, $cart_id, $item_options_raw, $cartItemId)
	{

		$db = Factory::getDbo();


		if ($change < 0)
		{
			// remove

			$change = abs($change);

			for ($x = 1; $x <= $change; $x++)
			{

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


		}
		else
		{
			// create

			$query = $db->getQuery(true);

			$query->select('*');
			$query->from($db->quoteName('#__protostore_carts'));
			$query->where($db->quoteName('id') . ' = ' . $db->quote($cartItemId));
			$db->setQuery($query);
			$result = $db->loadObject();


			for ($x = 1; $x <= $change; $x++)
			{
				$result->id = 0;
				$db->insertObject('#__protostore_carts', $result);

			}

		}


	}


	public static function removeAll($id)
	{

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('id') . " = " . $db->quote($id),
		);

		$query->delete($db->quoteName('#__protostore_carts'));
		$query->where($conditions);
		$db->setQuery($query);
		$done = $db->execute();

		if ($done)
		{
			return true;
		}

		return false;
	}


	public static function changeCount($cartItemId, $itemId, $newAmount)
	{

		$product = ProductFactory::get($itemId);

		if ($available = self::doStockCompare($product, $cartItemId))
		{

			if ($available >= $newAmount)
			{
				self::updateExistingCartAmount($cartItemId, $newAmount);

				return true;
			}

			return false;
		}

		return false;


	}

	public static function addToCart($itemid, $amount, $itemOptions)
	{

		// init
		$response = array();

		// check if product has tracking enabled
		// if not, carry on as normal
		// if so, check if in stock
		// return false if not
		// if so, check the current cart to see if the items are accounted for
		// return false if the remaining stock is in the cart already
		// count the items
		// compare with the amount in the request
		// add only up top the max stock
		// return

		// check that there is an item id...
		if ($itemid == null || $itemid == '' || $itemid == 0)
		{
			return false;
		}

		if (!$amount)
		{
			return false;
		}


		// get the options to calculate the price
		$data                = array();
		$data['options']     = array();
		$i                   = 0;
		$selected_option_ids = array();
		if (!empty($itemOptions))
		{
			foreach ($itemOptions as $option)
			{
				$data['options'][$i]['optionid'] = $option['optionid'];
				$selected_option_ids[]           = $option['optionid'];
				$i++;
			}
		}

		$item_options = json_encode($data);

		$cartId = self::get()->id;


		// check if this item is already in the cart
		$cart_item = self::getCurrentCartItem($cartId, $itemid, $item_options);


		// first, get the product
		$product = ProductFactory::get($itemid);

		// check if product has tracking enabled
		if ($product->manage_stock == 1)
		{

			// if so, check if in stock
			if ($product->stock >= 1)
			{
				// if so, check the current cart to see if the items are accounted for

				//first, does current cart exist?
				if ($cart_item)
				{
					// now, check the current cart to see if the items are accounted for
					// doStockCompare returns the available stock as an int - or false if all stock is used up.
					$amountThatCanBeAdded = self::doStockCompare($product, $cart_item->id);


					if ($amountThatCanBeAdded)
					{
						// check if the amount requested is lower than the amount possible
						if ($amount <= $amountThatCanBeAdded)
						{
							//add this amount
							self::addAmountToExistingCart($cart_item, $cart_item->amount, $amount);
							$response['status'] = 'ok';

							return $response;
						}
						else
						{
							//  only add up to the the max amount that can be added
							self::addAmountToExistingCart($cart_item, $cart_item->amount, $amountThatCanBeAdded);
							$response['status'] = 'ok';

							return $response;
						}


					}
					else
					{
						//all stock is added to cart already
						$response['status'] = 'ko';
						$response['error']  = 'There was an error adding to cart, ALL STOCK ALREADY ADDED';

						return $response;
					}

				}
				else
				{
					// this product is not already in the cart, so simply add to cart up to the available stock

					if ($amount <= $product->stock)
					{
						//
						$insert = self::addToCart_afresh($cartId, $data, $itemid, $amount, $selected_option_ids);
						if ($insert)
						{
							$response['status'] = 'ok';

							return $response;
						}
						else
						{
							$response['status'] = 'ko';
							$response['error']  = 'There was an error adding to cart, AMOUNT LESS THAN STOCK';

							return $response;
						}


					}
					else
					{
						//  only add up to the the max amount that can be added
						$insert = self::addToCart_afresh($cartId, $data, $itemid, $product->stock, $selected_option_ids);
						if ($insert)
						{
							$response['status'] = 'ok';

							return $response;
						}
						else
						{
							$response['status'] = 'ko';
							$response['error']  = 'There was an error adding to cart, ADDING STOCK';

							return $response;
						}
					}

				}


			}
			else
			{

				//out of stock
				$response['status']  = 'ko';
				$response['message'] = Text::_('Out of Stock'); // TODO - Translate

				return $response;
			}

		}


	}


	/**
	 * @param $cartId
	 * @param $itemid
	 * @param $item_options
	 *
	 * @return mixed|null
	 *
	 * @since 1.5
	 */


	private static function getCurrentCartItem($cartId, $itemid, $item_options)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_carts'));
		$query->where($db->quoteName('cart_id') . ' = ' . $db->quote($cartId), 'AND');
		$query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($itemid), 'AND');
		$query->where($db->quoteName('item_options') . ' = ' . $db->quote($item_options));

		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * @param            $product
	 * @param            $cart_itemid
	 *
	 * @return false|mixed|null
	 *
	 * @since 1.5
	 */


	public static function doStockCompare($product, $cart_itemid)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('amount');
		$query->from($db->quoteName('#__protostore_carts'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($cart_itemid));

		$db->setQuery($query);

		$currentAmount = $db->loadResult();

		if ($currentAmount >= $product->stock)
		{
			return false;
		}
		else
		{
			return $product->stock - $currentAmount;
		}

	}

	/**
	 * @param $cart_item
	 * @param $currentAmount
	 * @param $amountToBeAdded
	 *
	 *
	 * @since 1.5
	 */

	private static function addAmountToExistingCart($cart_item, $currentAmount, $amountToBeAdded)
	{


		$object = new stdClass();

		$cart_item->amount = ((int) $currentAmount + (int) $amountToBeAdded);
		$object->id        = $cart_item->id;

		Factory::getDbo()->updateObject('#__protostore_carts', $object, 'id');

	}

	/**
	 * @param $cart_item
	 * @param $currentAmount
	 * @param $amountToBeAdded
	 *
	 *
	 * @since 1.5
	 */

	private static function updateExistingCartAmount($cartItemId, int $amountToBeAdded)
	{


		$object = new stdClass();

		$object->amount = $amountToBeAdded;
		$object->id     = $cartItemId;

		Factory::getDbo()->updateObject('#__protostore_carts', $object, 'id');

	}


	private static function addToCart_afresh($cartId, $data, $itemid, $amount, $selected_option_ids)
	{

		$object = new stdClass();

		$object->id              = 0;
		$object->cart_id         = $cartId;
		$object->item_options    = json_encode($data);
		$object->added           = Utilities::getDate();
		$object->joomla_item_id  = $itemid;
		$object->amount          = $amount;
		$object->bought_at_price = Price::calculatePrice($selected_option_ids, $itemid);

		$insert = Factory::getDbo()->insertObject('#__protostore_carts', $object);

		if ($insert)
		{
			return true;
		}
		else
		{
			return false;
		}

	}


	/**
	 * @param   Cart  $cart
	 *
	 *
	 * @since 1.5
	 */

	public static function save(Cart $cart)
	{

		$object                      = new stdClass();
		$object->id                  = $cart->id;
		$object->user_id             = $cart->user_id;
		$object->cookie_id           = $cart->cookie_id;
		$object->coupon_id           = $cart->coupon_id;
		$object->billing_address_id  = $cart->billing_address_id;
		$object->shipping_address_id = $cart->shipping_address_id;

		Factory::getDbo()->updateObject('#__protostore_cart', $object, 'id');
	}


	/**
	 *
	 * @return bool
	 *
	 * @since 1.5
	 */

	public static function isGuestAddressSet(Cart $cart)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('shipping_address_id');
		$query->from($db->quoteName('#__protostore_cart'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($cart->id));

		$db->setQuery($query);

		$result = $db->loadResult();

		if ($result)
		{
			return true;
		}
		else
		{
			return false;
		}


	}




	/****** OLD FUNCTIONS *****/
	/****** OLD FUNCTIONS *****/
	/****** OLD FUNCTIONS *****/
	/****** OLD FUNCTIONS *****/
	/****** OLD FUNCTIONS *****/
	/****** OLD FUNCTIONS *****/
	/****** OLD FUNCTIONS *****/
	/****** OLD FUNCTIONS *****/
	/****** OLD FUNCTIONS *****/
	/****** OLD FUNCTIONS *****/
	/****** OLD FUNCTIONS *****/
	/****** OLD FUNCTIONS *****/

	private function getShippingType($shippingType)
	{

		if ($shippingType)
		{
			return $shippingType;
		}
		else
		{
			return 'defaultshipping';
		}
	}


	/**
	 * @param   mixed  $shipping_address_id
	 */
	public function setShippingAddressId($shipping_address_id)
	{
		$this->shipping_address_id = $shipping_address_id;
	}

	/**
	 * @param   mixed  $billing_address_id
	 */
	public function setBillingAddressId($billing_address_id)
	{
		$this->billing_address_id = $billing_address_id;
	}


	public static function getCurrentCartId()
	{

		$db   = Factory::getDbo();
		$user = Factory::getUser();

		// now check if there is already a cart for this cookie
		$query = $db->getQuery(true);

		$query->select('id');
		$query->from($db->quoteName('#__protostore_cart'));

		if ($user->guest)
		{
			$query->where($db->quoteName('cookie_id') . ' = ' . $db->quote(Utilities::getCookieID()));
		}
		else
		{
			$query->where($db->quoteName('user_id') . ' = ' . $db->quote($user->id));
		}

		$db->setQuery($query);

		$currentCartId = $db->loadResult();

		if ($currentCartId)
		{
			return $currentCartId;
		}
		else
		{
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

		$object                      = new stdClass();
		$object->id                  = Cart::getCurrentCartId();
		$object->shipping_address_id = '';
		$object->billing_address_id  = '';

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

		$object            = new stdClass();
		$object->id        = Cart::getCurrentCartId();
		$object->cookie_id = '';

		$result = $db->updateObject('#__protostore_cart', $object, 'id');

	}


	public static function setCartAddress($address_id, $type)
	{


		$currentCartId = self::getCurrentCartId();

		switch ($type)
		{

			case 'shipping':

				$object = new stdClass();

				$object->id                  = $currentCartId;
				$object->shipping_address_id = $address_id;

				$result = Factory::getDbo()->updateObject('#__protostore_cart', $object, 'id');

				if ($result)
				{
					return 'ok';
				}
				else
				{
					return 'ko';
				}

			case 'billing':

				$object = new stdClass();

				$object->id                 = $currentCartId;
				$object->billing_address_id = $address_id;

				$result = Factory::getDbo()->updateObject('#__protostore_cart', $object, 'id');

				if ($result)
				{
					return 'ok';
				}
				else
				{
					return 'ko';
				}

		}

	}


	public static function setBillingAsShipping()
	{

		$currentCartId = Cart::getCurrentCartId();

		$object = new stdClass();

		$object->id                 = $currentCartId;
		$object->billing_address_id = self::getCartAddressID('shipping');

		$result = Factory::getDbo()->updateObject('#__protostore_cart', $object, 'id');

		if ($result)
		{
			return 'ok';
		}
		else
		{
			return 'ko';
		}

	}

	public static function removeBillingAsShipping()
	{

		$db = Factory::getDbo();

		$object                     = new stdClass();
		$object->id                 = Cart::getCurrentCartId();
		$object->billing_address_id = 0;

		$result = $db->updateObject('#__protostore_cart', $object, 'id');

		if ($result)
		{
			return 'ok';
		}
		else
		{
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
		$date           = Utilities::getDate();
		$cookie_id      = Utilities::getCookieID();

		$cart = new Cart(self::getCurrentCartId());


		if (Factory::getUser()->guest)
		{
			$customer_id = 0;
		}
		else
		{
			$customer_id = Utilities::getCustomerIdByCurrentUserId();
		}


		$db = Factory::getDbo();


		// Build Order Object
		$object     = new stdClass();
		$object->id = 0;
		// Todo - "guest checkout" - Build a getCustomer function to handle guest checkout.
		$object->customer = $customer_id;

		if ($customer_id == 0)
		{
			//Todo - "guest checkout" - for the minute lets generate a uniqid() to identify the order for guest customers
			$object->guest_pin = uniqid();
		}

		$object->order_date     = $date;
		$object->order_number   = self::_generateOrderId(rand(10000, 99999));
		$object->order_paid     = 0;
		$object->order_status   = 'P';
		$object->order_total    = Total::getGrandTotal(true);
		$object->order_subtotal = Total::getSubTotal(true);
//        $object->shipping_total = Shipping::calculateTotalShipping(true);
		$object->shipping_total      = Shipping::getTotalShippingFromPlugin(true);
		$object->tax_total           = Tax::calculateTotalTax(true);
		$object->currency            = $currencyHelper->currency->iso;
		$object->payment_method      = $paymentMethod;
		$object->vendor_token        = $vendorToken;
		$object->billing_address_id  = $cart->billing_address_id;
		$object->shipping_address_id = $cart->shipping_address_id;
		$object->published           = 1;
		if ($coupon = Coupon::isCouponApplied())
		{
			$object->discount_code = $coupon->couponcode;
		}

		$object->discount_total = Coupon::calculateDiscount(Total::getSubTotal(false, true), true, false);
		if ($currentNote = Checkoutnote::getCurrentNote())
		{
			$object->customer_notes = $currentNote->note;
		}
		else
		{
			$object->customer_notes = '';
		}


		$result  = $db->insertObject('#__protostore_order', $object);
		$orderID = $db->insertid();
		//now set the order items

		$cartItems = $cart->getCartItems();

		foreach ($cartItems as $cartItem)
		{

			$object                = new stdClass();
			$object->id            = 0;
			$object->order_id      = $orderID;
			$object->j_item        = $cartItem->joomla_item_id;
			$object->j_item_cat    = $cartItem->joomla_item_cat;
			$object->j_item_name   = $cartItem->joomla_item_title;
			$object->item_options  = json_encode($cartItem->selected_options);
			$object->price_at_sale = Currency::translateToInt($cartItem->bought_at_price, $currencyHelper->currency->iso);
			$object->amount        = $cartItem->count;

			$result = $db->insertObject('#__protostore_order_products', $object);

			if ($cartItem->manage_stock_enabled == 1)
			{

				$currentStock  = Product::getCurrentStock($cartItem->product->id);
				$newStockLevel = ($currentStock - $cartItem->count);

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


		//Clear the cart
		$query      = $db->getQuery(true);
		$conditions = array(
			$db->quoteName('cart_id') . ' = ' . $db->quote($cart->id),
		);
		$query->delete($db->quoteName('#__protostore_carts'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->execute();

		//Clear coupons
		$query      = $db->getQuery(true);
		$conditions = array(
			$db->quoteName('cookie_id') . ' = ' . $db->quote($cookie_id)
		);
		$query->delete($db->quoteName('#__protostore_coupon_cart'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->execute();


		if ($sendEmail)
		{
			// get the plugin functions
			PluginHelper::importPlugin('protostoresystem');
			Factory::getApplication()->triggerEvent('onSendProtoStoreEmail', array('pending', $orderID));

		}

		new Orderlog(false, $orderID, Text::_('COM_PROTOSTORE_ORDER_IS_CREATED_LOG'));


		if ($result)
		{
			PluginHelper::importPlugin('protostoresystem');
			Factory::getApplication()->triggerEvent('onOrderCreated', array($orderID));

			return $orderID;
		}

	}


	private static function _generateOrderId($seed)
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


}
