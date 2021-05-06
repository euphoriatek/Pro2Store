<?php
/**
 * @package     Pro2Store - Total
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


use Protostore\Price\Price;
use Protostore\Product\Product;
use Protostore\Utilities\Utilities;
use Protostore\Currency\Currency;


return ['transforms' => ['render' => function ($node, array $params) {


    $node->props['item_id'] = Utilities::getCurrentItemId();


	$currencyHelper = new Currency();
	$currency       = new Protostore\Currency\Currency;
	$product        = new Product($node->props['item_id']);

	if($product->published == 0) {
	    return false;
    }


	$price_type = $node->props['price_type'];

	switch ($price_type)
	{
		case "base_price":
			$node->props['price_type_data'] = Currency::translate(Price::getBasePrice(), $currencyHelper);
			break;
		case "discount_amount":
			$node->props['price_type_data'] = Currency::translate(Price::calculateItemDiscount($node->props['item_id'], false), $currencyHelper);
			break;
		case "discount_percentage":
			$node->props['price_type_data'] = "no percentage applied";
			if ($product->discount_type === 'perc')
			{
				$node->props['price_type_data'] = $product->discount;
			}

			break;
		case "price_after_discount":
			$node->props['price_type_data'] = Currency::translate(Price::calculatePriceAfterDiscount($node->props['item_id']), $currencyHelper);
			break;

	}


},]];

