<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Tax;
// no direct access
defined('_JEXEC') or die('Restricted access');



use Protostore\Address\AddressFactory;
use Protostore\Cart\Cart;

use Protostore\Cart\CartItem;
use Protostore\Config\ConfigFactory;
use Protostore\Country\CountryFactory;
use Protostore\Product\Product;

use Protostore\Shipping\ShippingFactory;
use Protostore\Utilities\Utilities;

use Brick\Money\Exception\UnknownCurrencyException;

use Exception;

class TaxFactory
{


	/**
	 *
	 * This function should return an intValue for the total tax that needs to be applied to the current cart.
	 * It does this by looking at each CartItem in turn, checking if tax should be applied, then asking for the tax rate of the current shipping address
	 * And then calculating the int using those values.
	 *
	 * TODO - Add "Flat Tax" feature for each product
	 *
	 * @param   Cart  $cart
	 *
	 * @return int
	 *
	 * @throws UnknownCurrencyException
	 * @throws Exception
	 * @since 1.6
	 */


	public static function getTotalTax(Cart $cart): int
	{


		$config = ConfigFactory::get();

		$cartitems = $cart->cartItems;

		$totalTaxableValue = 0;

		/* @var CartItem $item */
		foreach ($cartitems as $item)
		{
			$totalTaxableValue += self::getItemTaxableValue($item);

		}


		if ($config->get('add_tax_to_shipping'))
		{
			$totalTaxableValue += ShippingFactory::getShipping($cart);
		}

		$taxRate = self::getApplicableTaxRate($cart);

		return Utilities::getPercentOfNumber($totalTaxableValue, $taxRate);

	}

	/**
	 * @param   CartItem  $cartItem
	 *
	 * @return int
	 *
	 * @since 1.6
	 */

	public static function getItemTaxableValue(CartItem $cartItem): int
	{

		if ($cartItem->product->taxable == 1)
		{
			return $cartItem->bought_at_price;
		}

		return 0;

	}


	public static function getItemTax($cartitem)
	{

		$product = new Product($cartitem->joomla_item_id);
		$price   = $cartitem->bought_at_price / 100;

		if ($product->taxable == 0)
		{
			return 0;
		}
//
//		if ($selectedShippingAddress = Address::getAssignedShippingAddressID())
//		{
//
//			$db = Factory::getDbo();
//
//			// get the full address using the class
//			$address = new Address($selectedShippingAddress);
//
//
//			// first get zone tax rate
//			$query = $db->getQuery(true);
//
//			$query->select('taxrate');
//			$query->from($db->quoteName('#__protostore_zone'));
//			$query->where($db->quoteName('id') . ' = ' . $db->quote($address->zone_id));
//
//			$db->setQuery($query);
//
//			$zoneTaxrate = $db->loadResult();
//
//			if ($zoneTaxrate)
//			{
//				// if we have a zone tax rate... return the added tax
//				return Utilities::getPercentOfNumber($price, $zoneTaxrate);
//			}
//			else
//			{
//
//				// there is no zone tax rate... perhaps there is a country level tax rate.
//				// get country tax rate
//				$query = $db->getQuery(true);
//
//				$query->select('taxrate');
//				$query->from($db->quoteName('#__protostore_country'));
//				$query->where($db->quoteName('id') . ' = ' . $db->quote($address->country_id));
//
//				$db->setQuery($query);
//
//				$countryTaxrate = $db->loadResult();
//
//				if ($countryTaxrate)
//				{
//
//					// if there is a country tax rate, return the added tax
//					return Utilities::getPercentOfNumber($price, $countryTaxrate);
//				}
//
//			}
//
//			// or you know... whatever....
//			return 0;
//
//
//		}

	}


	/**
	 *
	 * Takes an int value and returns an int value after tax has been added based on shipping address
	 *
	 * @param   int   $totalTaxableValue
	 *
	 *
	 * @return int
	 *
	 * @since 1.6
	 */


	public static function getTotalTaxViaShippingAddress(int $totalTaxableValue): int
	{


		if ($address = AddressFactory::getCurrentShippingAddress())
		{


			$zoneTaxrate = CountryFactory::getZone($address->zone)->taxrate;

			if ($zoneTaxrate)
			{
				// if we have a zone tax rate... return the added tax
				return Utilities::getPercentOfNumber($totalTaxableValue, $zoneTaxrate);
			}
			else
			{

				// there is no zone tax rate... perhaps there is a country level tax rate.
				// get country tax rate
				$countryTaxrate = CountryFactory::get($address->country)->taxrate;

				if ($countryTaxrate)
				{

					// if there is a country tax rate, return the added tax
					return Utilities::getPercentOfNumber($totalTaxableValue, $countryTaxrate);
				}

			}

			// or you know... whatever....
			return 0;


		}

		return 0;
	}

	/**
	 *
	 * Get the tax rate for the currently selected Shipping Address
	 *
	 * @param   Cart  $cart
	 *
	 * @return int
	 *
	 * @since 1.6
	 */


	public static function getApplicableTaxRate(Cart $cart): int
	{

		if ($address = AddressFactory::get($cart->shipping_address_id))
		{


			$zoneTaxrate = CountryFactory::getZone($address->zone)->taxrate;

			if ($zoneTaxrate)
			{
				// if we have a zone tax rate... return it
				return $zoneTaxrate;
			}
			else
			{

				// there is no zone tax rate... perhaps there is a country level tax rate.
				// get country tax rate
				$countryTaxrate = CountryFactory::get($address->country)->taxrate;

				if ($countryTaxrate)
				{
					// if there is a country tax rate, return it
					return $countryTaxrate;
				}

			}

			// or you know... whatever....
			return 0;


		}

		return 0;

	}


}
