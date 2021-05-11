<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Protostore\Tax;
// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

use Protostore\Address\Address;
use Protostore\Cart\Cart;
use Protostore\Cart\CartFactory;
use Protostore\Product\Product;
use Protostore\Utilities\Utilities;
use Protostore\Shipping\Shipping;
use Protostore\Config\Config;

use Brick\Money\Money;
use Brick\Money\Context\CashContext;
use Brick\Math\RoundingMode;

class Tax
{

	private $db;

	public function __construct($id)
	{
		$this->db = Factory::getDbo();


	}


	public static function calculateTotalTax()
	{

		$config = new Config();
		$config = $config->config;

		$cart      = CartFactory::get();

		$cartitems = $cart->cartItems;


		$totalTaxableValue = 0;

		foreach ($cartitems as $item)
		{
			$totalTaxableValue += self::getItemTaxableValue($item);

		}

		if ($config->get('add_tax_to_shipping'))
		{
			$totalTaxableValue += Shipping::getTotalShippingFromPlugin();
		}

		$total = self::getTotalTax($totalTaxableValue);

		//now convert to integer
		// currency doesn't matter at this point... we just need the int
		$amount = Money::ofMinor($total, 'EUR', new CashContext(1), RoundingMode::DOWN);
		$amount = $amount->getMinorAmount()->toInt();


		return $amount;

	}

	public static function getItemTaxableValue($cartitem)
	{
		$product = new Product($cartitem->joomla_item_id);
		if ($product->taxable == 0)
		{
			return 0;
		}
		else
		{
			return $cartitem->bought_at_price;
		}

	}


	public static function getItemTax($cartitem)
	{

		$product = new Product($cartitem->joomla_item_id);
		$price   = $cartitem->bought_at_price / 100;

		if ($product->taxable == 0)
		{
			return 0;
		}

		if ($selectedShippingAddress = Address::getAssignedShippingAddressID())
		{

			$db = Factory::getDbo();

			// get the full address using the class
			$address = new Address($selectedShippingAddress);


			// first get zone tax rate
			$query = $db->getQuery(true);

			$query->select('taxrate');
			$query->from($db->quoteName('#__protostore_zone'));
			$query->where($db->quoteName('id') . ' = ' . $db->quote($address->zone_id));

			$db->setQuery($query);

			$zoneTaxrate = $db->loadResult();

			if ($zoneTaxrate)
			{
				// if we have a zone tax rate... return the added tax
				return Utilities::getPercentOfNumber($price, $zoneTaxrate);
			}
			else
			{

				// there is no zone tax rate... perhaps there is a country level tax rate.
				// get country tax rate
				$query = $db->getQuery(true);

				$query->select('taxrate');
				$query->from($db->quoteName('#__protostore_country'));
				$query->where($db->quoteName('id') . ' = ' . $db->quote($address->country_id));

				$db->setQuery($query);

				$countryTaxrate = $db->loadResult();

				if ($countryTaxrate)
				{

					// if there is a country tax rate, return the added tax
					return Utilities::getPercentOfNumber($price, $countryTaxrate);
				}

			}

			// or you know... whatever....
			return 0;


		}

	}


	public static function getTotalTax($total)
	{


		if ($selectedShippingAddress = Address::getAssignedShippingAddressID())
		{

			$db = Factory::getDbo();

			// get the full address using the class
			$address = new Address($selectedShippingAddress);


			// first get zone tax rate
			$query = $db->getQuery(true);

			$query->select('taxrate');
			$query->from($db->quoteName('#__protostore_zone'));
			$query->where($db->quoteName('id') . ' = ' . $db->quote($address->zone_id));

			$db->setQuery($query);

			$zoneTaxrate = $db->loadResult();

			if ($zoneTaxrate)
			{
				// if we have a zone tax rate... return the added tax
				return Utilities::getPercentOfNumber($total, $zoneTaxrate);
			}
			else
			{

				// there is no zone tax rate... perhaps there is a country level tax rate.
				// get country tax rate
				$query = $db->getQuery(true);

				$query->select('taxrate');
				$query->from($db->quoteName('#__protostore_country'));
				$query->where($db->quoteName('id') . ' = ' . $db->quote($address->country_id));

				$db->setQuery($query);

				$countryTaxrate = $db->loadResult();

				if ($countryTaxrate)
				{

					// if there is a country tax rate, return the added tax
					return Utilities::getPercentOfNumber($total, $countryTaxrate);
				}

			}

			// or you know... whatever....
			return 0;


		}
		else
		{
			return 0;
		}

	}
}
