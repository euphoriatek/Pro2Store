<?php

/**
 * @package     Pro2Store - Add To Cart
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use Protostore\Product\ProductFactory;
use Protostore\Utilities\Utilities;
use Protostore\Config\Config;

return [

    // Define transforms for the element node
    'transforms' => [


        // The function is executed before the template is rendered
        'render' => function ($node, array $params) {

            $node->props['item_id'] = Utilities::getCurrentItemId();

//            $product = new Product($node->props['item_id']);
            $product = ProductFactory::get($node->props['item_id']);

            if ($product->published == 0) {
                return false;
            }

            // check if we are managing stock on this product
            // set 'instock' to true even if we are not managing stock
            $node->props['instock'] = true;
            if ($product->manage_stock == 1) {
                // if we have stock... fine...
                if ($product->stock > 0) {

                } else {
                    $node->props['instock'] = false;
                }
            }

            $config = new Config();
            $node->props['checkoutlink'] = Route::_('index.php?Itemid=' . $config->checkoutItemid);
            $node->props['baseUrl'] = Uri::base();

        },

    ]

];

?>
