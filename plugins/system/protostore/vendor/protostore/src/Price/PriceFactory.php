<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Price;
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\Input\Input;

use Protostore\Product\Product;
use Protostore\Product\ProductFactory;
use Protostore\Productoption\ProductoptionFactory;
use Protostore\Currency\CurrencyFactory;
use Protostore\Utilities\Utilities;

use Brick\Money\Exception\UnknownCurrencyException;

class PriceFactory
{


	/**
	 * @param   Input  $data
	 * ** use json magic method to retrieve input data
	 *
	 *                      Input contains:
	 *
	 *                      joomla_item_id
	 *                      selectedVariants
	 *                      multiplier
	 *
	 *                      todo - what about discounts?
	 *
	 * @return Price
	 *
	 * @throws UnknownCurrencyException
	 * @since 1.6
	 */

	public static function calculatePriceFromInputData(Input $data): ?Price
	{

		// init
		$price = array();

		// get post data (using json magic)
		$joomla_item_id = $data->json->getInt('joomla_item_id', false);

		// make sure there's an actual product here.
		if(!$joomla_item_id) {
			return null;
		}

		$selected       = $data->json->get('selectedVariants', false);
		$multiplier     = $data->json->getInt('multiplier', 1);


		// now check we even have variants selected

		if ($selected)
		{


			// make a comma seperated string from the selected Ids, to allow database lookup
			$selected = implode(',', $selected);


			// query the database and get the row for this selected variant
			$db    = Factory::getDbo();
			$query = $db->getQuery(true);

			$query->select('*');
			$query->from($db->quoteName('#__protostore_product_variant_data'));
			$query->where($db->quoteName('label_ids') . ' = ' . $db->quote($selected));
			// not totally needed, but why not...
			$query->where($db->quoteName('product_id') . ' = ' . $db->quote($joomla_item_id));

			$db->setQuery($query);

			$selectedVariant = $db->loadObject();


			// process the data and form it to the Price Object
			$price['int']    = ($selectedVariant->price * $multiplier);
			$price['string'] = CurrencyFactory::translate(($selectedVariant->price * $multiplier));

		}
		else
		{

			$product = ProductFactory::get($joomla_item_id);

			$price['int']    = ($product->base_price * $multiplier);
			$price['string'] = CurrencyFactory::translate(($product->base_price * $multiplier));


		}

		return new Price($price);


	}


	/**
	 * @param   array  $ids
	 * @param          $itemid
	 * @param   int    $multiplier
	 *
	 * @return int
	 * @deprecated 1.5
	 * @since      1.0
	 */


	public static function calculatePrice($ids = array(), $itemid, $multiplier = 1): int
	{


		// first, get the product
		$product = ProductFactory::get($itemid);

		// if the base price is zero, then return zero
		if ($product->base_price === 0)
		{
			return 0;
		}

		// init vars
		$change_value            = 0;
		$amountToAddonViaOptions = 0;

		// if there are option ids set, then iterate them and calculate the price difference
		if ($ids)
		{

			// get the selected options
			$pulledSelectedOptions = ProductoptionFactory::getListFromGivenIds($ids);

			//iterate them
			foreach ($pulledSelectedOptions as $option)
			{
				if ($option->modifiervalue)
				{

					if ($option->modifiertype == 'perc')
					{
						$change_value += Utilities::getPercentOfNumber($product->base_price, $option->modifiervalue);
					}
					elseif ($option->modifiertype == 'amount')
					{
						$change_value = $option->modifiervalue;
					}

					if ($option->modifier === 'add')
					{
						$amountToAddonViaOptions = ($amountToAddonViaOptions + $change_value);
					}
					else
					{
						$amountToAddonViaOptions = (($amountToAddonViaOptions - $change_value));
					}

				}
			}
		}


		// get the discount
		$priceWithDiscount = $product->base_price - self::calculateItemDiscount($product);

		// now create a new price by adding the price with discount, to the price calculated from the options
		// remember, $amountToAddonViaOptions is zero, if no options are selected.
		$newPrice = $priceWithDiscount + $amountToAddonViaOptions;

		// now consider the multipier
		$newPrice = $newPrice * $multiplier;

		// quick check to prevent negative prices (if the discount is wrong)
		if ($newPrice < 0)
		{
			$newPrice = 0;
		}


		// finally, return the price.
		return (int) $newPrice;


	}


	/**
	 * @param   Product  $product
	 *
	 * @return int
	 *
	 * @since 1.6
	 */


	public static function calculateItemDiscount(Product $product): int
	{


		// Make sure we have a discount on this product, if not, just return zero

		if ($product->discount && $product->discount_type)
		{

			// init the return int
			$change_value = 0;


			// if percent, do the percentage calculation
			if ($product->discount_type == 'perc')
			{
				$change_value += (($product->discount / 100) * $product->base_price);
			}
			else
			{
				// otherwise, just return the int
				$change_value += $product->discount;
			}


			return (int) $change_value;

		}

		return 0;


	}


	/**
	 * @param   Product  $product
	 *
	 * @return int
	 *
	 * @since 1.6
	 */

	public static function calculatePriceAfterDiscount(Product $product)
	{


		if ($product->discount && $product->discount_type)
		{
			$change_value = 0;

			// if percent, do the percentage calculation
			if ($product->discount_type == 'perc')
			{
				$change_value += (($product->discount / 100) * $product->base_price);
			}
			else
			{
				// otherwise, just return the int
				$change_value += $product->discount;
			}

			return $product->base_price - $change_value;


		}

		return $product->base_price;


	}


	public static function formatPriceForDB($price)
	{
		if (strpos($price, '.') == false)
		{
			$price = $price . '.00';
		}

		return preg_replace("/[^0-9.]/", "", $price);
	}


}
