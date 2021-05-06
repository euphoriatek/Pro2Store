<?php
/**
 * @package     Pro2Store - Total
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


use Joomla\CMS\Uri\Uri;

use Protostore\Price\Price;
use Protostore\Utilities\Utilities;
use Protostore\Currency\Currency;
use Protostore\Product\Product;

return ['transforms' => ['render' => function ($node, array $params) {


    $currencyHelper = new Currency();
    $currency = new Protostore\Currency\Currency;

    $node->props['baseUrl'] = Uri::base();
    $node->props['item_id'] = Utilities::getCurrentItemId();
    $node->props['price'] = Currency::translate(Price::getBasePrice($node->props['item_id']), $currencyHelper);
    $node->props['item_discount'] = Currency::translate(Price::calculateItemDiscount($node->props['item_id']), $currencyHelper);
    $node->props['price_after_discount'] = Currency::translate(Price::calculatePriceAfterDiscount($node->props['item_id']), $currencyHelper);
    $node->props['currency'] = $currency->currency->currencysymbol;

    $product = new Product($node->props['item_id']);

    if ($product->published == 0) {
        return false;
    }


},]];

