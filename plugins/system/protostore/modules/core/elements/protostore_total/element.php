<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
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

