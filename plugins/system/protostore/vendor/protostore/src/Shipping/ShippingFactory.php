<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// no direct access

namespace Protostore\Shipping;

defined('_JEXEC') or die('Restricted access');

use Brick\Money\Exception\UnknownCurrencyException;
use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Protostore\Address\Address;
use Protostore\Address\AddressFactory;
use Protostore\Cart\Cart;
use Protostore\Cart\CartFactory;
use Protostore\Cart\CartItem;
use Protostore\Currency\CurrencyFactory;
use Protostore\Language\LanguageFactory;

use Protostore\Product\Product;
use Protostore\Product\ProductFactory;


class ShippingFactory
{

	/**
	 * @param   Cart  $cart
	 *
	 * @return int
	 *
	 * @throws Exception
	 * @since 2.0
	 */

	public static function getShipping(Cart $cart): int
	{
		PluginHelper::importPlugin('protostoreshipping');

		if ($cart->shipping_type)
		{
			$shippingType = $cart->shipping_type;
		}
		else
		{
			$shippingType = "defaultshipping";
		}

		$shippingTotal = Factory::getApplication()->triggerEvent('onCalculateShipping' . $shippingType, array('cart' => $cart));
		if ($shippingTotal)
		{
			return (int) $shippingTotal[0];
		}

		return 0;

	}


	/**
	 * @param   Cart  $cart
	 *
	 *
	 * @return string
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */


	public static function getShippingFormatted(Cart $cart): string
	{
		if (Factory::getUser()->guest)
		{
			if (CartFactory::isGuestAddressSet())
			{
				$totalShipping = CurrencyFactory::translate($cart->totalShipping);
			}
			else
			{
				LanguageFactory::load();
				$totalShipping = Text::_('COM_PROTOSTORE_ELM_CARTSUMMARY_SELECT_SHIPPING_ADDRESS');
			}


		}
		else
		{
			$totalShipping = CurrencyFactory::translate($cart->totalShipping);
		}

		return $totalShipping;
	}


	/**
	 *
	 * This function calculates the total shipping for an order.
	 *
	 * @param   Cart  $cart
	 *
	 * @return int
	 *
	 * @since 2.0
	 */


	public static function calculateTotalShipping(Cart $cart): int
	{

		$cartitems = $cart->cartItems;


		$total = 0;

		if ($cartitems)
		{


			/* @var CartItem $item */
			foreach ($cartitems as $item)
			{

				$itemShipping = self::getItemFlatShipping($item);
				// multiply the item shipping by the count
				$total += $itemShipping * $item->amount;

			}
		}

		// get the total weight of the "weight" activated items.
		// then get the shipping total for that combined weight
		// then add it to the $total from above.
		$weight = 0;
		if ($cartitems)
		{
			foreach ($cartitems as $item)
			{
				$itemWeight = self::getItemWeight($item);

				// multiply the item weight by the count
				$weight += $itemWeight * $item->amount;

			}
		}

		// $weight now has the total of the cart weight for items that calculate shipping based on their weight.
		$weightTotal = 0;
		if ($weight > 0)
		{
			$weightTotal = self::getWeightShippingTotal($weight);
		}


		return $total + $weightTotal;


	}


	/**
	 *
	 * This function returns the shipping total for a single item
	 *
	 * @param   CartItem  $cartitem
	 *
	 * @return int
	 *
	 * @since 2.0
	 */

	public static function getItemFlatShipping(CartItem $cartitem): int
	{


		$product = ProductFactory::get($cartitem->joomla_item_id);

		if ($product->shipping_mode == 'flat')
		{
			return $product->flatfee;

		}
		else
		{
			return 0;
		}


	}


	/**
	 * @param   CartItem  $cartitem
	 *
	 * @return int
	 *
	 * @since 2.0
	 */


	public static function getItemWeight(CartItem $cartitem): int
	{

		$product = ProductFactory::get($cartitem->joomla_item_id);

		if ($product->shipping_mode == 'weight')
		{
			// return the weight for the item
			return $product->weight;
		}

		return 0;

	}

	/**
	 *
	 * Takes a weight value and calculates the shipping cost (int) based on the current shipping address
	 *
	 * example: Send in the total weight of the cart (i.e. the products with weight enabled) and get the shipping total back
	 *
	 * @param $weight
	 *
	 * @return int
	 *
	 * @since 2.0
	 */


	public static function getWeightShippingTotal($weight): int
	{


		// now get the customers selected shipping address
		if ($address = AddressFactory::getCurrentShippingAddress())
		{

			$db = Factory::getDbo();

			// check if we need to ship to a zone first
			$db->setQuery('SELECT * from ' . $db->quoteName('#__protostore_zone_shipping_rate') . ' where ' . $weight . ' between `weight_from` and `weight_to` AND `zone_id` = ' . $address->zone . ' AND `published` = 1;');

			$zoneResult = $db->loadObject();

			//if so, return that shipping value
			if ($zoneResult)
			{
				return $zoneResult->cost + $zoneResult->handling_cost;
			}
			else
			{

				// so, if no zone shipping is found, try to apply some country level shipping
				$db->setQuery('SELECT * from ' . $db->quoteName('#__protostore_shipping_rate') . ' where ' . $weight . ' between `weight_from` and `weight_to` AND `country_id` = ' . $address->country . ' AND `published` = 1;');

				$countryResult = $db->loadObject();

				if ($countryResult)
				{
					return $countryResult->cost + $countryResult->handling_cost;
				}
				else
				{
					//if no zone shipping or country shipping
					return 0;
				}
			}


		}

		// just return zero if nothing happens.
		return 0;

	}


}
