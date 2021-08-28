<?php
/**
 * @package     Pro2Store - Total
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


use Joomla\CMS\Uri\Uri;

use Protostore\Price\PriceFactory;
use Protostore\Utilities\Utilities;
use Protostore\Currency\CurrencyFactory;
use Protostore\Product\ProductFactory;

return ['transforms' => ['render' => function ($node, array $params) {


	$product = ProductFactory::get(Utilities::getCurrentItemId());

    $node->props['baseUrl'] = Uri::base();
    $node->props['item_id'] = $product->joomlaItem->id;
    $node->props['price'] = CurrencyFactory::translate($product->base_price);
    $node->props['item_discount'] = CurrencyFactory::translate(PriceFactory::calculateItemDiscount($product));
    $node->props['price_after_discount'] = CurrencyFactory::translate(PriceFactory::calculatePriceAfterDiscount($product));
	$node->props['currency'] = CurrencyFactory::getCurrent()->currencysymbol;



    if ($product->published == 0) {
        return false;
    }


},]];

