<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

use Joomla\CMS\Language\Text;

use Protostore\Product\ProductFactory;
use Protostore\Currency\CurrencyFactory;
use Protostore\Price\PriceFactory;
use Protostore\Utilities\Utilities;


return ['transforms' => ['render' => function ($node, array $params) {


	$node->props['item_id'] = Utilities::getCurrentItemId();

	$product = ProductFactory::get($node->props['item_id']);

	if (!is_null($product))
	{
		if ($product->published == 0)
		{
			return false;
		}

		$price_type = $node->props['price_type'];

		switch ($price_type)
		{
			case "base_price":
				$node->props['price_type_data'] = CurrencyFactory::translate($product->base_price);
				break;
			case "discount_amount":
				$node->props['price_type_data'] = CurrencyFactory::translate(PriceFactory::calculateItemDiscount($product));
				break;
			case "discount_percentage":
				\Protostore\Language\LanguageFactory::load();
				$node->props['price_type_data'] = Text::_('COM_PROTOSTORE_ELM_PRICE_NO_PERCENTAGE_APPLIED');
				if ($product->discount_type === 'perc')
				{
					$node->props['price_type_data'] = $product->discount;
				}

				break;
			case "price_after_discount":
				$node->props['price_type_data'] = CurrencyFactory::translate(PriceFactory::calculatePriceAfterDiscount($product));
				break;

		}

	}
	else
	{
		return false;
	}


},]];

