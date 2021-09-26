<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */


namespace Protostore\Cart;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Brick\Money\Exception\UnknownCurrencyException;
use Joomla\CMS\Factory;

use Joomla\CMS\Language\Text;
use Protostore\Address\AddressFactory;
use Protostore\Coupon\CouponFactory;
use Protostore\Price\PriceFactory;
use Protostore\Product\Product;
use Protostore\Product\ProductFactory;
use Protostore\Productoption\Productoption;
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
	 * @since 2.0
	 */

	public static function get(): Cart
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
	 * @return Cart
	 *
	 * @since 2.0
	 */

	public static function init(): Cart
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
	 *
	 *
	 *
	 * AVOID USING self::get() here as it seems to cause infinite loops. Not sure why!
	 *
	 * @return false|mixed
	 *
	 * @since 1.5
	 */


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
		$query->from($db->quoteName('#__protostore_cart_item'));
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
	 * @return null|array
	 *
	 * @since 2.0
	 */


	public static function getCartItems($carId): ?array
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_cart_item'));

		$query->where($db->quoteName('cart_id') . ' = ' . $db->quote($carId));


		$db->setQuery($query);

		$items = $db->loadObjectList();

		if ($items)
		{
			$cartItems = array();

			foreach ($items as $item)
			{
				$cartItems[] = new Cartitem($item);
			}

			return $cartItems;
		}


		return null;


	}

	/**
	 * @param   array|null  $cartItems
	 *
	 * @return int
	 *
	 * @since 1.5
	 */

	public static function getCount(?array $cartItems): int
	{

		$count = 0;
		if ($cartItems)
		{
			foreach ($cartItems as $item)
			{
				$count += $item->amount;
			}
		}


		return $count;

	}

	/**
	 * @param   string  $options
	 *
	 * @return null|array
	 *
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */


	public static function getSelectedOptions(string $options): ?array
	{
		$selectedOptions = array();
		$option_ids      = explode(',', $options);

		foreach ($option_ids as $option_id)
		{
			$selectedOption = ProductoptionFactory::get($option_id);

			if ($selectedOption)
			{
				$selectedOptions[] = $selectedOption;
			}


		}

		if (!empty($selectedOptions))
		{
			return $selectedOptions;
		}

		return null;

	}

	/**
	 * @param   int|null  $variant_id
	 *
	 * @return SelectedVariant|null
	 *
	 * @since 2.0
	 */


	public static function getSelectedVariant(int $variant_id = null): ?SelectedVariant
	{


		if (!$variant_id)
		{
			return null;
		}

		$db = Factory::getDbo();


		// now check if there is already a cart for this cookie
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_variant_data'));


		$query->where($db->quoteName('id') . ' = ' . $db->quote($variant_id));


		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			return new SelectedVariant($result);
		}

		return null;


	}


	public static function getVariantLabels(string $labels): ?array
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('name');
		$query->from($db->quoteName('#__protostore_product_variant_label'));
		$query->where($db->quoteName('id') . ' IN (' . $labels . ')');

		$db->setQuery($query);

		$results = $db->loadAssocList();

		if ($results)
		{
			return $results;
		}

		return null;


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

				$query->delete($db->quoteName('#__protostore_cart_item'));
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
			$query->from($db->quoteName('#__protostore_cart_item'));
			$query->where($db->quoteName('id') . ' = ' . $db->quote($cartItemId));
			$db->setQuery($query);
			$result = $db->loadObject();


			for ($x = 1; $x <= $change; $x++)
			{
				$result->id = 0;
				$db->insertObject('#__protostore_cart_item', $result);

			}

		}


	}

	/**
	 * @param $id
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */


	public static function removeAll($id): bool
	{

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('id') . " = " . $db->quote($id),
		);

		$query->delete($db->quoteName('#__protostore_cart_item'));
		$query->where($conditions);
		$db->setQuery($query);
		$done = $db->execute();

		if ($done)
		{
			return true;
		}

		return false;
	}

	/**
	 * @param $cartItemId
	 * @param $itemId
	 * @param $newAmount
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */


	public static function changeCount($cartItemId, $itemId, $newAmount): bool
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

	/**
	 * @param   int         $itemid
	 * @param   int         $amount
	 * @param   array|null  $options
	 * @param   array|null  $variant_ids
	 *
	 * @return null|array
	 *
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */

	public static function addToCart(int $itemid, int $amount, array $options = null, array $variant_ids = null): ?array
	{

		// init response
		$response = array();

		// get the product
		$product = ProductFactory::get($itemid);
		$cart_id = self::getCurrentCartId();

		// check stock right away... sorts out a lot of checking later.
		if ($product->manage_stock == 1)
		{
			if ($product->stock = 0)
			{
				//out of stock
				$response['status']  = 'ko';
				$response['message'] = Text::_('COM_PROTOSTORE_OUT_OF_STOCK');

				return $response;
			}
		}


		// check if there's a variant
		if ($variant_ids)
		{
			$response = self::addVariantToCart($product, $cart_id, $amount, $variant_ids, $options);
		}
		else
		{
			$response = self::addNonVariantToCart($product, $cart_id, $amount, $options);
		}
//
//		// init
//		$response = array();
//		$cartId = self::getCurrentCartId();
//
//		// check if product has tracking enabled
//		// if not, carry on as normal
//		// if so, check if in stock
//		// return false if not
//		// if so, check the current cart to see if the items are accounted for
//		// return false if the remaining stock is in the cart already
//		// count the items
//		// compare with the amount in the request
//		// add only up top the max stock
//		// return
//
//		// check that there is an item id...
//		if ($itemid == null || $itemid == '' || $itemid == 0)
//		{
//			return false;
//		}
//
//		if (!$amount)
//		{
//			return false;
//		}
//
//
//		// get the options to calculate the price
//		$data                = array();
//		$data['options']     = array();
//		$i                   = 0;
//		$selected_option_ids = array();
//		if (!empty($itemOptions))
//		{
//			foreach ($itemOptions as $option)
//			{
//				$data['options'][$i]['optionid'] = $option['optionid'];
//				$selected_option_ids[]           = $option['optionid'];
//				$i++;
//			}
//		}
//
//		$item_options = json_encode($data);
//
//		$cartId = self::getCurrentCartId();
//
//
//		// check if this item is already in the cart
//		$cart_item = self::getCurrentCartItem($cartId, $itemid, $item_options);
//
//
//		// first, get the product
//		$product = ProductFactory::get($itemid);
//
//		// check if product has tracking enabled
//		if ($product->manage_stock == 1)
//		{
//
//			// if so, check if in stock
//			if ($product->stock >= 1)
//			{
//				// if there is stock available, check the current cart to see if the items are accounted for
//
//				//first, does current cart exist?
//				if ($cart_item)
//				{
//					// now, check the current cart to see if the items are accounted for
//					// doStockCompare returns the available stock as an int - or false if all stock is used up.
//					$amountThatCanBeAdded = self::doStockCompare($product, $cart_item->id);
//
//
//					if ($amountThatCanBeAdded)
//					{
//						// check if the amount requested is lower than the amount possible
//						if ($amount <= $amountThatCanBeAdded)
//						{
//							//add this amount
//							self::addAmountToExistingCart($cart_item, $cart_item->amount, $amount);
//							$response['status'] = 'ok';
//
//							return $response;
//						}
//						else
//						{
//							//  only add up to the the max amount that can be added
//							self::addAmountToExistingCart($cart_item, $cart_item->amount, $amountThatCanBeAdded);
//							$response['status'] = 'ok';
//
//							return $response;
//						}
//
//
//					}
//					else
//					{
//						//all stock is added to cart already
//						$response['status'] = 'ko';
//						$response['error']  = 'There was an error adding to cart, ALL STOCK ALREADY ADDED'; // TODO - TRANSLATE
//
//						return $response;
//					}
//
//				}
//				else
//				{
//					// this product is not already in the cart, so simply add to cart up to the available stock
//
//					if ($amount <= $product->stock)
//					{
//						//
//						$insert = self::addToCart_afresh($cartId, $data, $itemid, $amount, $selected_option_ids, $variant->id);
//						if ($insert)
//						{
//							$response['status'] = 'ok';
//
//							return $response;
//						}
//						else
//						{
//							$response['status'] = 'ko';
//							$response['error']  = 'There was an error adding to cart, AMOUNT LESS THAN STOCK'; // TODO - TRANSLATE
//
//							return $response;
//						}
//
//
//					}
//					else
//					{
//						//  only add up to the the max amount that can be added
//						$insert = self::addToCart_afresh($cartId, $data, $itemid, $product->stock, $selected_option_ids, $variant->id);
//						if ($insert)
//						{
//							$response['status'] = 'ok';
//
//							return $response;
//						}
//						else
//						{
//							$response['status'] = 'ko';
//							$response['error']  = 'There was an error adding to cart, ADDING STOCK'; // TODO - TRANSLATE
//
//							return $response;
//						}
//					}
//
//				}
//
//
//			}
//			else
//			{
//
//				//out of stock
//				$response['status']  = 'ko';
//				$response['message'] = Text::_('COM_PROTOSTORE_OUT_OF_STOCK');
//
//				return $response;
//			}
//
//		}
//		else
//		{
//			// FUCK ME!! WE STILL NEED TO ADD IT IF WE'RE NOT TRACKING STOCK!
//
//
//			// check if the item is already in cart
//			//if so, increment
//			//if not, add afresh
//
//			if ($cartitem = self::getCurrentCartItem($cartId, $itemid, $item_options))
//			{
//
//
//				$newAmount = $cartitem->amount + $amount;
//
//				self::updateExistingCartAmount($cartitem->id, $newAmount);
//				$response['status'] = 'ok';
//
//				return $response;
//
//			}
//			else
//			{
//				$insert = self::addToCart_afresh($cartId, $data, $itemid, $amount, $selected_option_ids, $variant->id);
//				if ($insert)
//				{
//					$response['status'] = 'ok';
//
//					return $response;
//				}
//			}
//
//
//		}

		return $response;
	}


	/**
	 *
	 * this function adds a variant selection to the cart.
	 *
	 * @param   Product     $product
	 * @param   int         $cart_id
	 * @param   int         $amount
	 * @param   array|null  $options
	 * @param   array       $variant_ids
	 *
	 * @return array
	 *
	 * @since 2.0
	 *
	 *        todo - stock management
	 */

	public static function addVariantToCart(Product $product, int $cart_id, int $amount, array $variant_ids, array $options = null): array
	{

		// init response
		$response = array();

		// get the variant row containing the price, sku, and stock etc.
		$variantRow = self::getVariantRow($variant_ids);

		if (!$variantRow)
		{
			$response['status'] = 'ko';
			$response['error']  = "CANNOT FIND VARIANT";

			return $response;
		}

		//We need to check if an item with the same variant is already in the cart, so go get the cart item
		$cart_item = self::getCurrentCartItem($cart_id, $product->joomla_item_id, $variantRow->id, $options);

		//first, does current cart exist?
		if ($cart_item)
		{
			// now, check the current cart to see if the items are accounted for
			// doVariantStockCompare returns the available stock as an int - or NULL if all stock is used up.
			$amountThatCanBeAdded = self::doVariantStockCompare($variantRow->stock, $cart_item->id);


			// $amountThatCanBeAdded will be NULL if nothing can be added, so we can do a boolean check here.
			if (!$amountThatCanBeAdded)
			{
				//all stock is added to cart already
				$response['status'] = 'ko';
				$response['error']  = Text::_('COM_PROTOSTORE_ADD_PRODUCT_INVENTORY_ALL_STOCK_ADDED_TO_CART');

				return $response;
			}

			// check if the amount requested is lower than the amount possible
			if ($amount <= $amountThatCanBeAdded)
			{
				//add this amount
				self::addAmountToExistingCart($cart_item, $amount);
				$response['status'] = 'added';
			}
			else
			{
				//  only add up to the max amount that can be added
				self::addAmountToExistingCart($cart_item, $amountThatCanBeAdded);
				$response['status'] = 'added2';
			}

		}
		else
		{

			$price          = $variantRow->price;
			$priceToBeAdded = 0;
			if ($options)
			{
				$priceToBeAdded = self::getOptionsPriceToBeAdded($price, $options);
			}

			$price += $priceToBeAdded;

			// this product is not already in the cart, so simply add to cart up to the available stock
			if ($amount <= $variantRow->stock)
			{

				$insert = self::addToCart_afresh($cart_id, $product->joomla_item_id, $variantRow->id, $options, $price, $amount);
				if (!$insert)
				{
					$response['status'] = 'ko';
					$response['error']  = 'There was an error adding to cart, AMOUNT LESS THAN STOCK'; // TODO - TRANSLATE

					return $response;
				}


			}
			else
			{
				//  only add up to the max amount that can be added
				$insert = self::addToCart_afresh($cart_id, $product->joomla_item_id, $variantRow->id, $options, $price, $amount);
				if (!$insert)
				{
					$response['status'] = 'ko';
					$response['error']  = 'There was an error adding to cart, ADDING STOCK'; // TODO - TRANSLATE

					return $response;
				}

			}

		}


		return $response;

	}

	/**
	 * @param   array  $variant_ids
	 *
	 *
	 * @return mixed|null
	 * @since 2.0
	 */

	private static function getVariantRow(array $variant_ids)
	{
		$variant_ids = implode(',', $variant_ids);

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_variant_data'));
		$query->where($db->quoteName('label_ids') . ' = ' . $db->quote($variant_ids));

		$db->setQuery($query);

		return $db->loadObject();
	}


	/**
	 * @param   Product     $product
	 * @param   int         $cart_id
	 * @param   int         $amount
	 * @param   array|null  $options
	 *
	 * @return array
	 *
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */

	public static function addNonVariantToCart(Product $product, int $cart_id, int $amount, array $options = null): array
	{

		// init response
		$response = array();


		//We need to check if an item with the same variant is already in the cart, so go get the cart item
		$cart_item = self::getCurrentCartItem($cart_id, $product->joomla_item_id, null, $options);

		//first, does current cart exist?
		if ($cart_item)
		{
			// now, check the current cart to see if the items are accounted for
			// doStockCompare returns the available stock as an int - or NULL if all stock is used up.
			$amountThatCanBeAdded = self::doStockCompare($product, $cart_item->id);


			// $amountThatCanBeAdded will be NULL if nothing can be added, so we can do a boolean check here.
			if (!$amountThatCanBeAdded)
			{
				//all stock is added to cart already
				$response['status'] = 'ko';
				$response['error']  = Text::_('COM_PROTOSTORE_ADD_PRODUCT_INVENTORY_ALL_STOCK_ADDED_TO_CART');

				return $response;
			}

			// check if the amount requested is lower than the amount possible
			if ($amount <= $amountThatCanBeAdded)
			{
				//add this amount
				self::addAmountToExistingCart($cart_item, $amount);
				$response['status'] = 'added';
			}
			else
			{
				//  only add up to the max amount that can be added
				self::addAmountToExistingCart($cart_item, $amountThatCanBeAdded);
				$response['status'] = 'added2';
			}

		}
		else
		{

			//get price with options
			$price          = $product->base_price;
			$priceToBeAdded = 0;
			if ($options)
			{
				$priceToBeAdded = self::getOptionsPriceToBeAdded($price, $options);
			}

			$price += $priceToBeAdded;
			// this product is not already in the cart, so simply add to cart up to the available stock
			if ($amount <= $product->stock)
			{

				$insert = self::addToCart_afresh($cart_id, $product->joomla_item_id, 0, $options, $price, $amount);
				if (!$insert)
				{
					$response['status'] = 'ko';
					$response['error']  = 'There was an error adding to cart, AMOUNT LESS THAN STOCK'; // TODO - TRANSLATE

					return $response;
				}


			}
			else
			{
				//  only add up to the max amount that can be added
				$insert = self::addToCart_afresh($cart_id, $product->joomla_item_id, 0, $options, $price, $amount);
				if (!$insert)
				{
					$response['status'] = 'ko';
					$response['error']  = 'There was an error adding to cart, ADDING STOCK'; // TODO - TRANSLATE

					return $response;
				}

			}

		}


		return $response;


	}


	/**
	 * @param   int         $cart_id
	 * @param   int         $itemid
	 * @param   int|null    $variant_id
	 * @param   array|null  $options
	 *
	 * @return CartItem|null
	 *
	 * @since 2.0
	 */


	private static function getCurrentCartItem(int $cart_id, int $itemid, int $variant_id = null, array $options = null): ?CartItem
	{

		$option_ids = array();
		if ($options)
		{
			foreach ($options as $option)
			{
				$option_ids[] = $option['id'];
			}
		}


		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_cart_item'));
		$query->where($db->quoteName('cart_id') . ' = ' . $db->quote($cart_id));
		$query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($itemid));

		$query->where($db->quoteName('variant_id') . ' = ' . $db->quote($variant_id));


		$query->where($db->quoteName('item_options') . ' = ' . $db->quote(implode(',', $option_ids)));


		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			return new CartItem($result);
		}

		return null;
	}


	/**
	 * @param   int    $basePrice
	 * @param   array  $options
	 *
	 * @return int
	 *
	 * @since 2.0
	 */


	public static function getOptionsPriceToBeAdded(int $basePrice, array $options): int
	{

		$toBeAdded = 0;

		foreach ($options as $option)
		{
			if ($option['modifier_type'] == 'perc')
			{
				$toBeAdded += (Utilities::getPercentOfNumber($basePrice, $option['modifier_value']) / 100);

			}
			if ($option['modifier_type'] == 'amount')
			{
				$toBeAdded += $option['modifier_value'];
			}

		}

		return $toBeAdded;

	}

	/**
	 * @param   Product  $product
	 * @param   int      $cart_itemid
	 *
	 * @return int|null
	 *
	 * @since 1.5
	 */


	public static function doStockCompare(Product $product, int $cart_itemid): ?int
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('amount');
		$query->from($db->quoteName('#__protostore_cart_item'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($cart_itemid));

		$db->setQuery($query);

		$currentAmount = $db->loadResult();

		if ($currentAmount >= $product->stock)
		{
			return null;
		}
		else
		{
			return $product->stock - $currentAmount;
		}

	}

	/**
	 * @param   int  $variantStock
	 * @param   int  $cart_itemid
	 *
	 * @return int|null
	 *
	 * @since 2.0
	 */


	public static function doVariantStockCompare(int $variantStock, int $cart_itemid): ?int
	{

		$db = Factory::getDbo();

		//get the current cart amount
		$query = $db->getQuery(true);

		$query->select('amount');
		$query->from($db->quoteName('#__protostore_cart_item'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($cart_itemid));

		$db->setQuery($query);

		$currentAmount = $db->loadResult();


		if ($currentAmount >= $variantStock)
		{
			return null;
		}
		else
		{
			return $variantStock - $currentAmount;
		}

	}

	/**
	 * @param   CartItem  $cart_item
	 * @param   int       $amountToBeAdded
	 *
	 *
	 * @since 1.5
	 */

	private static function addAmountToExistingCart(CartItem $cart_item, int $amountToBeAdded)
	{


		$object = new stdClass();

		$object->id     = $cart_item->id;
		$object->amount = ($cart_item->amount + $amountToBeAdded);


		Factory::getDbo()->updateObject('#__protostore_cart_item', $object, 'id');

	}

	/**
	 * @param   int  $cartItemId
	 * @param   int  $newAmount
	 *
	 * @since 2.0
	 */

	private static function updateExistingCartAmount(int $cartItemId, int $newAmount)
	{


		$object = new stdClass();

		$object->amount = $newAmount;
		$object->id     = $cartItemId;

		Factory::getDbo()->updateObject('#__protostore_cart_item', $object, 'id');

	}

	/**
	 * @param   int         $cart_id
	 * @param   int         $j_item_id
	 * @param   int|null    $variant_id
	 * @param   array|null  $options
	 * @param   int         $price
	 * @param   int         $amount
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */


	private static function addToCart_afresh(int $cart_id, int $j_item_id, ?int $variant_id, ?array $options, int $price, int $amount): bool
	{

		$option_ids = array();

		foreach ($options as $option)
		{
			$option_ids[] = $option['id'];
		}

		$object = new stdClass();

		$object->id              = 0;
		$object->cart_id         = $cart_id;
		$object->joomla_item_id  = $j_item_id;
		$object->variant_id      = $variant_id;
		$object->item_options    = implode(',', $option_ids);
		$object->added           = Utilities::getDate();
		$object->amount          = $amount;
		$object->bought_at_price = $price;

		$insert = Factory::getDbo()->insertObject('#__protostore_cart_item', $object);

		if (!$insert)
		{
			return false;
		}

		return true;

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

	public static function isGuestAddressSet(): bool
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('shipping_address_id');
		$query->from($db->quoteName('#__protostore_cart'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote(self::getCurrentCartId()));

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


	/**
	 *
	 * Called when logging out to clear all remaining data
	 * Called when guest user clicks "Start Over"
	 *
	 * @return bool
	 * @since 2.0
	 */

	public static function destroyCartAddress(): bool
	{

		$db = Factory::getDbo();

		$object                      = new stdClass();
		$object->id                  = self::getCurrentCartId();
		$object->shipping_address_id = '';
		$object->billing_address_id  = '';

		$result = $db->updateObject('#__protostore_cart', $object, 'id');

		if ($result)
		{
			return true;
		}

		return false;
	}

	/**
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */


	public static function setBillingAsShipping(): bool
	{

		$currentCartId = self::getCurrentCartId();

		$object = new stdClass();

		$object->id                 = $currentCartId;
		$object->billing_address_id = AddressFactory::getCurrentShippingAddress()->id;

		$result = Factory::getDbo()->updateObject('#__protostore_cart', $object, 'id');

		if ($result)
		{
			return true;
		}

		return false;


	}

	/**
	 * @param $type
	 * @param $cartId
	 *
	 * @return mixed|null
	 *
	 * @since 2.0
	 */


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


	private static function getCartAddressID($cartId, $type = 'shipping')
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select($type . '_address_id');
		$query->from($db->quoteName('#__protostore_cart'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($cartId));

		$db->setQuery($query);

		return $db->loadResult();

	}

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


	public static function countCartItems($cartId)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('COUNT(*)');
		$query->from($db->quoteName('#__protostore_cart_item'));
		$query->where($db->quoteName('cart_id') . ' = ' . $db->quote($cartId));

		$db->setQuery($query);

		return $db->loadResult();

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

	/**
	 * @param   int     $address_id
	 * @param   string  $type
	 *
	 * @return bool|void
	 *
	 * @since 2.0
	 */


	public static function setCartAddress(int $address_id, string $type): bool
	{


		$cart = self::get();

		switch ($type)
		{

			case 'shipping':

				$object = new stdClass();

				$object->id                  = $cart->id;
				$object->shipping_address_id = $address_id;

				$result = Factory::getDbo()->updateObject('#__protostore_cart', $object, 'id');

				if ($result)
				{
					return true;
				}

				return false;


			case 'billing':

				$object = new stdClass();

				$object->id                 = $cart->id;
				$object->billing_address_id = $address_id;

				$result = Factory::getDbo()->updateObject('#__protostore_cart', $object, 'id');

				if ($result)
				{
					return true;
				}

				return false;

		}

		return false;

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


	/**
	 *
	 * Function - getSubTotal
	 *
	 * Returns the subtotal for any given cart as an integer
	 * (Moved from Total in 1.6)
	 *
	 * @param   Cart  $cart
	 *
	 * @return int
	 * @since 2.0
	 */

	public static function getSubTotal(Cart $cart): int
	{


		// init total var at 0
		$total = 0;

		// iterate through the cart items and sum their totals.
		if ($results = $cart->cartItems)
		{

			// loop through the cart list
			foreach ($results as $result)
			{

				$total += (int) $result->totalCost;
			}

		}

		return $total;

	}


	/**
	 * @param   Cart  $cart
	 *
	 * Returns the grand total for any given cart as an integer
	 * (Moved from Total in 1.6)
	 *
	 * @return int
	 *
	 * @since 2.0
	 */

	public static function getGrandTotal(Cart $cart): int
	{

		// get the current subtotal
		$total = $cart->subtotalInt;

		// look to see if any coupons are applied
		$couponDiscount = CouponFactory::calculateDiscount($cart);

		// if the coupon discount is greater than the actual total,
		// then set the coupon discount top the value of the total to
		// avoid negative values.
		if ($couponDiscount > $total)
		{
			$couponDiscount = $total;
		}


		// now return the value (in int) of the total minus the discount total
		return $total - $couponDiscount;

	}

	/**
	 * @param   Cart  $cart
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */


	public static function clearItems(Cart $cart): bool
	{

		$db = Factory::getDbo();

		$query      = $db->getQuery(true);
		$conditions = array(
			$db->quoteName('cart_id') . ' = ' . $db->quote($cart->id),
		);
		$query->delete($db->quoteName('#__protostore_cart_item'));
		$query->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();

		if ($result)
		{
			return true;
		}

		return false;

	}

	/**
	 * @param   Cart  $cart
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */


	public static function clearCoupons(Cart $cart): bool
	{

		$db = Factory::getDbo();

		$query      = $db->getQuery(true);
		$conditions = array(
			$db->quoteName('cookie_id') . ' = ' . $db->quote($cart->cookie_id),
		);
		$query->delete($db->quoteName('#__protostore_coupon_cart'));
		$query->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();

		if ($result)
		{
			return true;
		}

		return false;

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

		$cart = self::get();


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
		$object->order_total    = self::getGrandTotal($cart);
		$object->order_subtotal = self::getSubTotal($cart);

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
		$query->delete($db->quoteName('#__protostore_cart_item'));
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


}
