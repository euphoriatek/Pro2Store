<?php

/**
 * @package     Pro2Store - Quantity
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


use Protostore\Product\ProductFactory;
use Protostore\Utilities\Utilities;

return ['transforms' => ['render' => function ($node, array $params) {


    $product =  ProductFactory::get(Utilities::getCurrentItemId());

    if ($product->published == 0) {
        return false;
    }

    $node->props['instock'] = true;
    if ($product->manage_stock == 1) {
        // if we have stock... fine...
        if ($product->stock > 0) {
            $node->props['max'] = $product->stock;
        } else {
            $node->props['instock'] = false;
        }
    } else {
        $node->props['max'] = 999999;
    }

},]];

